<?php
	/**
	* @author Garet C. Green
	*/

	require_once("tbszip.php");

	/**
	* Footbridge Media parser for ODT files
	*/
	class ODTParser{
		private $metaTitle = "";
		private $metaDescription = "";
		private $relativeUrlPath = "";
		private $pageH1 = "";
		private $noteToBuilderFromWriter = "";
		private $linkColorThreshold = 150;
		private $totalErrorsInContent = 0;

		/**
		* Parses ODT the file at the file path
		*
		* @param string $filePath
		* @return array
		*/
		public function parseFile(string $filePath){

			$zip = new clsTbsZip();
			$zip->open($filePath);
			$ok = $zip->FileExists("content.xml");
			$txt = $zip->FileRead("content.xml");

			// Convert all "smart" quotes to normal stuff, because wtf
			$chr_map = array(
				// Windows codepage 1252
				"\xC2\x82" => "'", // U+0082⇒U+201A single low-9 quotation mark
				"\xC2\x84" => '"', // U+0084⇒U+201E double low-9 quotation mark
				"\xC2\x8B" => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark
				"\xC2\x91" => "'", // U+0091⇒U+2018 left single quotation mark
				"\xC2\x92" => "'", // U+0092⇒U+2019 right single quotation mark
				"\xC2\x93" => '"', // U+0093⇒U+201C left double quotation mark
				"\xC2\x94" => '"', // U+0094⇒U+201D right double quotation mark
				"\xC2\x9B" => "'", // U+009B⇒U+203A single right-pointing angle quotation mark
				// Regular Unicode     // U+0022 quotation mark (")
							  // U+0027 apostrophe     (')
				"\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
				"\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
				"\xE2\x80\x98" => "'", // U+2018 left single quotation mark
				"\xE2\x80\x99" => "'", // U+2019 right single quotation mark
				"\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
				"\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
				"\xE2\x80\x9C" => '"', // U+201C left double quotation mark
				"\xE2\x80\x9D" => '"', // U+201D right double quotation mark
				"\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
				"\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
				"\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
				"\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
			);
			$chr = array_keys  ($chr_map); // but: for efficiency you should
			$rpl = array_values($chr_map); // pre-calculate these two arrays

			$reader = new XMLReader();
			$writer = new XMLWriter();
			$writer->openMemory();
			$writer->startDocument('1.0', 'UTF-8');
			$writer->setIndent(true);
			$writer->setIndentString('	');
			$reader->xml($txt);
			$result = "";
			$buffer = "";

			$savedStyles = [];
			$lastStyleIndex = "";

			while ($reader->read()){
				global $currentToken;
				$nodeName = $reader->name;
				$nodeValue = $reader->value;

				if ($nodeName === "style:style"){
					$styleIndex = $reader->getAttribute("style:name");

					if (!array_key_exists($styleIndex, $savedStyles)){
						$savedStyles[$styleIndex] = [];
					}

					$lastStyleIndex = $styleIndex;
				}elseif ($nodeName === "style:text-properties"){
					if ($lastStyleIndex !== ""){
						$styles = [];
						// Font weight
						$fontWeight = $reader->getAttribute("fo:font-weight");
						if ($fontWeight){
							$styles['font-weight'] = $fontWeight;
						}

						$fontColor = $reader->getAttribute("fo:color");
						if ($fontColor){
							$styles['font-color'] = $fontColor;
						}

						if (!isset($savedStyles[$lastStyleIndex])){
							$savedStyles[$lastStyleIndex] = $styles;
						}else{
							// This was added because sometimes a blank style node is passed of an existing style
							// It must be merged to avoid overwriting the original, or to add to a blank style
							// This is sort of a band-aid patch for an unknown bug (why are there two of the same style?)
							// It is possible a closing-node is being read?
							$savedStyles[$lastStyleIndex] = array_merge($savedStyles[$lastStyleIndex], $styles);
						}

						$lastStyleIndex = "";
					}
				}else{
					switch ($reader->nodeType){
						case XMLReader::ELEMENT:
							$DOMNode = $reader->expand();
							$numChildNodes = $DOMNode->childNodes->length;
							if ($nodeName === "text:line-break") {
								$writer->endElement();
								$writer->startElement("p");
								$writer->text("");
							} elseif ($nodeName === "text:s") {
								$writer->text(" ");
							}
							if ($numChildNodes > 0){
								if ($nodeName === "text:p" || $nodeName === "text:h"){
									$writer->startElement("p");
									$writer->text("");
								}elseif ($nodeName === "text:a"){
									//$writer->startElement("a");
									//$writer->text("");
								}elseif ($nodeName === "text:list"){
									$writer->startElement("ul");
									$writer->text("");
								}elseif ($nodeName === "text:list-item"){
									$writer->startElement("li");
									$writer->text("");
								}elseif($nodeName === "text:span"){
									$styleIndex = $reader->getAttribute("text:style-name");
									if ($styleIndex){
										if (array_key_exists($styleIndex, $savedStyles)){
											$styles = $savedStyles[$styleIndex];
											if (array_key_exists("font-weight", $styles)){
												$fontWeight = $styles['font-weight'];
												if ($fontWeight === "bold" && !array_key_exists("font-color", $styles)){
													$writer->writeRaw("<strong>");
													$writer->text("");
												}elseif ($fontWeight === "bold" && array_key_exists("font-color", $styles)){
													$fontColor = $styles['font-color'];
													// It's bold and has color
													// Check if the color is black or really close
													$success = preg_match("/\#(\w{2})(\w{2})(\w{2})/", $fontColor, $colorMatches);
													if ($success !== 0){
														$r = hexdec($colorMatches[1]);
														$g = hexdec($colorMatches[2]);
														$b = hexdec($colorMatches[3]);
														if ($r < 20 && $g < 20 && $b < 20){
															$writer->writeRaw("<strong>");
															$writer->text("");
														}else{
															if ($r > $b){
																$writer->writeRaw("<a href=\"SERVICELINK\">");
															}else{
																$writer->writeRaw("<a href=\"/\">");
															}
														}
													}
												}elseif (array_key_exists("font-color", $styles)){
													$fontColor = $styles['font-color'];
													$success = preg_match("/\#(\w{2})\w{2}\w{2}/", $fontColor, $redMatches);
													$success2 = preg_match("/\#\w{2}\w{2}(\w{2})/", $fontColor, $blueMatches);
													if ($success !== 0 && $success2 !== 0){
														$redRGB = hexdec($redMatches[1]);
														$blueRGB = hexdec($blueMatches[1]);
														if ($redRGB > $blueRGB){
															if ($redRGB > $this->linkColorThreshold){
																$writer->writeRaw("<a href=\"SERVICELINK\">");
															}
														}elseif($redRGB <= $blueRGB){
															if ($blueRGB > $this->linkColorThreshold){
																$writer->writeRaw("<a href=\"/\">");
															}
														}
													}
													$writer->text("");
												}
											}elseif (array_key_exists("font-color", $styles)){
												$fontColor = $styles['font-color'];
												$success = preg_match("/\#(\w{2})\w{2}\w{2}/", $fontColor, $redMatches);
												$success2 = preg_match("/\#\w{2}\w{2}(\w{2})/", $fontColor, $blueMatches);
												if ($success !== 0 && $success2 !== 0){
													$redRGB = hexdec($redMatches[1]);
													$blueRGB = hexdec($blueMatches[1]);
													if ($redRGB > $blueRGB){
														if ($redRGB > $this->linkColorThreshold){
															$writer->writeRaw("<a href=\"SERVICELINK\">");
														}
													}elseif ($redRGB <= $blueRGB){
														if ($blueRGB > $this->linkColorThreshold){
															$writer->writeRaw("<a href=\"/\">");
														}
													}
												}
												$writer->text("");
											}
										}
									}
								}
							}
							break;
						case XMLReader::END_ELEMENT:
							// Spans do not get their own tag, so ignore a close request for them
							if ($nodeName !== "text:span" && $nodeName !== "text:a"){
								$writer->endElement();
							}elseif ($nodeName !== "text:a"){
								//print("CLOSING $nodeName");
								$styleIndex = $reader->getAttribute("text:style-name");
								if ($styleIndex){
									if (array_key_exists($styleIndex, $savedStyles)){
										$styles = $savedStyles[$styleIndex];
										if (array_key_exists("font-weight", $styles)){
											$fontWeight = $styles['font-weight'];
											if ($fontWeight === "bold" && !array_key_exists("font-color", $styles)){
												$writer->writeRaw("</strong>");
											}elseif ($fontWeight === "bold" && array_key_exists("font-color", $styles)){
												$fontColor = $styles['font-color'];
												// It's bold and has color
												// Check if the color is black or really close
												$success = preg_match("/\#(\w{2})(\w{2})(\w{2})/", $fontColor, $colorMatches);
												if ($success !== 0){
													$r = hexdec($colorMatches[1]);
													$g = hexdec($colorMatches[2]);
													$b = hexdec($colorMatches[3]);
													if ($r < 20 && $g < 20 && $b < 20){
														$writer->writeRaw("</strong>");
														$writer->text("");
													}else{
														$writer->writeRaw("</a>");
													}
												}
											}elseif (array_key_exists("font-color", $styles)){
												$fontColor = $styles['font-color'];
												$success = preg_match("/\#(\w{2})\w{2}\w{2}/", $fontColor, $redMatches);
												$success2 = preg_match("/\#\w{2}\w{2}(\w{2})/", $fontColor, $blueMatches);
												if ($success !== 0 && $success2 !== 0){
													$redRGB = hexdec($redMatches[1]);
													$blueRGB = hexdec($blueMatches[1]);
													if ($redRGB > $this->linkColorThreshold || $blueRGB > $this->linkColorThreshold){
														$writer->writeRaw("</a>");
													}
												}
											}
										}elseif (array_key_exists("font-color", $styles)){
											$fontColor = $styles['font-color'];
											$success = preg_match("/\#(\w{2})\w{2}\w{2}/", $fontColor, $redMatches);
											$success2 = preg_match("/\#\w{2}\w{2}(\w{2})/", $fontColor, $blueMatches);
											if ($success !== 0 && $success2 !== 0){
												$redRGB = hexdec($redMatches[1]);
												$blueRGB = hexdec($blueMatches[1]);
												if ($redRGB > $this->linkColorThreshold || $blueRGB > $this->linkColorThreshold){
													$writer->writeRaw("</a>");
												}
											}
										}
									}
								}
							}
							break;
						case XMLReader::TEXT:
							$nodeValue = str_replace($chr, $rpl, html_entity_decode($nodeValue, ENT_QUOTES, "UTF-8"));

							// Replace & with &amp;
							$nodeValue = preg_replace("/&(?!amp;)/", "&amp;amp;", $nodeValue);
							$writer->writeRaw($nodeValue);
							break;
						default:
							break;
					}
				}

			}

			$result = $writer->outputMemory();

			// Replace all phone numbers
			$result = preg_replace("/\d{3}\s*[–-]\s*\d{3}\s*[–-]\s*\d{4} or \d{3}\s*[–-]\s*\d{3}\s*[–-]\s*\d{4}/u", "{{ phoneNumber which=1 }} or {{ phoneNumber which=2 }}", $result); // Replace "num or num"
			$result = preg_replace("/\d{3}\s*[–-]\s*\d{3}\s*[–-]\s*\d{4}/u", "{{ phoneNumber which=1 }}", $result);
			$result = preg_replace("/\d{1}[–-]\s*\d{3}\s*[–-]\s*\d{3}\s*[–-]\s*\d{4}/u", "{{ phoneNumber which=1 }}", $result);
			$result = preg_replace("/\(\d{3}\)[\- ]*\d{3}-\d{4}/u", "{{ phoneNumber which=1 }}", $result);

			// Replace empty paragraphs
			$result = preg_replace("/<p\/>\n/ism", "", $result);
			$result = preg_replace("/<\?xml v.*?\n/im", "", $result);

			// Make lists all one line for the second pass parsing
			$result = preg_replace("/<ul>\s*(.*?)\s*<\/ul>/ism", "<ul>$1</ul>\n", $result);
			$result = preg_replace("/<li>\s*(.*?)\s*<\/li>/ism", "<li>$1</li>", $result);
			$result = preg_replace("/<\/li>\s*<li>/ism", "</li><li>", $result);

			// Remove closing and then reopening tags because the writers have some weird fucking shit way of coloring
			$result = str_replace("</a><a href=\"SERVICELINK\">", "", $result);
			$result = str_replace("</a><a href=\"/\">", "", $result);
			$result = str_replace("</strong><strong>", "", $result);

			$result = str_replace($chr, $rpl, html_entity_decode($result, ENT_QUOTES, "UTF-8"));

			// Replace stupid dashed, like seriously mf hate this shit
			$endash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
			$result = str_replace($endash, '-', $result);

			// Replace the stupid fucking full-stop
			$result = preg_replace("/\r/", '', $result);

			// Replace the mathematical product symbol
			$result = preg_replace("/\x{00B7}/u", '', $result);

			// Remove no-break spaces
			$result = preg_replace("/\x{00A0}/u", ' ', $result);

			$finalResult = "";
			$alreadyGotThePageURI = false; // For when we already get the URI for the file
			$lines = preg_split("/((\r?\n)|(\r\n?))/", $result);


			$FANBOYSCONJUNCTION_REGEX = "/ (in|a|and|at|of|for|nor|or|but|to|the) /";

			// Alright bored reader,
			/*
				You may ask, why the hell does the variable below exist?
				Because sometimes the writers like to press the enter key in the middle of the meta description.
				Why? Who the hell knows man.
				When this variable is true, the next line being parsed will be skipped because the meta description section
				will have hopefully already parsed it. mf
			*/
			$skipNextIndex = false;

			foreach($lines as $index=>$line){
				if ($skipNextIndex === true){
					$skipNextIndex = false;
					continue;
				}

				// Remove <p> tags
				$forceH2 = false;
				$originalLine = $line;
				$line = preg_replace("/<p>/", "", $line);
				$line = preg_replace("/<\/p>/", "", $line);
				$line = trim($line);

				// Unordered list lines starting with -
				// Example: - Text text text
				// - Text text text

				// Check 2 before lines and 1 after. If those three are blank, it is an h2
				if (isset($lines[$index+1]) && isset($lines[$index-1]) && isset($lines[$index-2])){
					$lineBefore1 = $lines[$index-2];
					$lineBefore2 = $lines[$index-1];
					$lineAfter = $lines[$index+1];
					if ( strlen(preg_replace("/\s/", "", $lineBefore1)) == 0 && strlen(preg_replace("/\s/", "", $lineBefore2)) == 0 && strlen(preg_replace("/\s/", "", $lineAfter)) == 0 ){
						$forceH2 = true;
					}else{
						// Allow them to be an unordered list
						$line = preg_replace("/^\s*[–\-]\s*(.+)/uism", "<ul><li>$1</li></ul>", $line);
					}
				}else{
					// Allow to be an unordered list
					$line = preg_replace("/^\s*[–\-]\s*(.+)/uism", "<ul><li>$1</li></ul>", $line);
				}


				if (stripos($line, "<title>") !== false){
					// Is there more than on opening tag?
					if (substr_count($line, "<title>") > 1){
						// Fix the problem
						++$this->totalErrorsInContent;
						$line = preg_replace("/<title>(.*?)<title>/ims", "<title>$1</title>", trim($line));

					}

					// Capture the title for the global variable
					preg_match("/<title>(.*?)<\/title>/ims", $line, $matches);
					if (isset($matches[1])){
						$this->metaTitle = $matches[1];
						$this->metaTitle = str_replace("&nbsp;", "", $this->metaTitle);
					}else{
						return [
							"status"=>-1,
							"error"=>"The page title is malformed, please fix this in the ODT file.",
						];
					}
				}elseif (stripos($line, 'name="description"') !== false){
					// Meta description!
					// Remove any possible anchors or strongs or whatever other stupid crap
					$currentLineLength = mb_strlen($line);
					$line = str_replace("<a href=\"/\">", "", $line);
					$line = str_replace("<a href=\"SERVICELINK\">", "", $line);
					$line = str_replace("<strong>", "", $line);
					$line = str_replace("</a>", "", $line);
					$line = str_replace("</strong>", "", $line);

					if (mb_strlen($line) !== $currentLineLength){
						// Omg we fixed another error
						++$this->totalErrorsInContent;
					}
					$success = preg_match("/content\s*=\s*\"(.*?)\"/ism", $line, $matches);
					if (!$success){
						// fuck
						$nextLine = $lines[$index + 1];
						$nextLine = preg_replace("/<p>/", "", $nextLine);
						$nextLine = preg_replace("/<\/p>/", "", $nextLine);
						$nextLine = trim($nextLine);
						$skipNextIndex = true;
						++$this->totalErrorsInContent;
						preg_match("/content\s*=\s*\"(.*?)\"/ism", $line . $nextLine, $matches);
					}
					if (isset($matches[1])){
						$this->metaDescription = $matches[1];
					}else{
						return [
							"status"=>-1,
							"error"=>"The meta description is malformed. Please fix in the ODT file.",
						];
					}
				}elseif (stripos($line, 'name="keywords"') !== false){
					// Meta keywords, throw 'em away
				}elseif (stripos($line, "URL:") !== false){
					// This is to obtain the URI from when the writer prepends URL: before the file's URI
					// Remove strong tags from the URL because sometimes the writers do that >.>
					$currentLineLength = mb_strlen($line);
					$line = preg_replace("/<\/*strong>/", "", $line);

					if (mb_strlen($line) !== $currentLineLength){
						++$this->totalErrorsInContent;
					}

					$success = preg_match("/.*?\:\/\/.*?\..*?\..*?\/(.*)/", $line, $matches);

					if ($success === 0){
						$success = preg_match("/.*?\:\/\/.*?\..*?\/(.*)/", $line, $matches);
						if ($success != 0){
							$this->relativeUrlPath = strtolower($matches[1]);
						}
					}else{
						$this->relativeUrlPath = strtolower($matches[1]);
					}
				}elseif ( ($alreadyGotThePageURI === false) && ((stripos($line, "<a>") !== false || stripos($line, "http") !== false) && (stripos($filePath, "writer notes") === false))){
					// This is the url needed
					// This is in case they did not prepend URL: to the page at the top of the file and the parser ran across a stray line of text that looks like a URL
					$line = preg_replace("/<\/*strong>/", "", $line);
					$success = preg_match("/.*?\:\/\/.*?\..*?\..*?\/(.*)/", $line, $matches);
					if ($success === 0){
						$success = preg_match("/.*?\:\/\/.*?\..*?\/(.*)/", $line, $matches);
						if ($success != 0){
							$this->relativeUrlPath = strtolower($matches[1]);
							$alreadyGotThePageURI = true;
						}
					}else{
						$this->relativeUrlPath = strtolower($matches[1]);
						$alreadyGotThePageURI = true;
					}
				}elseif (stripos(strtolower($line), "note to build") !== false){
					$this->noteToBuilderFromWriter = preg_replace("/note to builder:/i", "", $line);
				}elseif (stripos($line, "<h2>") !== false){
					// Wtf they already put the H2? mf
					// Is there more than one opening tag? goddamnit
					if (substr_count(strtolower($line), "<h2>") > 1){
						// Fix fix the problem
						++$this->totalErrorsInContent;
						$line = preg_replace("/<h2>(.*?)<h2>/ims", "<h2>$1</h2>", $line);
					}

					$finalResult .= $line . "\n";
				}elseif (stripos($line, "<h1>") !== false){

					// Is there more than one opening tag?
					if (substr_count($line, "<h1>") > 1){
						// Fix fix the problem
						++$this->totalErrorsInContent;
						$line = preg_replace("/<h1>(.*?)<h1>/ims", "<h1>$1</h1>", $line);
					}

					// Is this fucking bullshit closed with a mother fucking h2? Jesus christ I swear
					// Seriously, read half this code. It's all just a fix for inconcsistent writing
					// Sure, one or two messups - but like 60% of the writing on _every_ site? fuck off
					if (stripos($line, "</h2>") !== false){
						$line = preg_replace("/<h1>(.*?)<\/h2>/ism", "<h1>$1</h1>", $line);
					}elseif (stripos($line, "<h2>") !== false){
						$line = preg_replace("/<h1>(.*?)<h2>/ism", "<h1>$1</h1>", $line);
					}

					// Capture the title for the global variable
					preg_match("/<h1>(.*?)<\/h1>/ims", $line, $matches);
					if (isset($matches[1])){
						$this->pageH1 = trim($matches[1]);
						$this->pageH1 = str_replace("&nbsp;", "", $this->pageH1);
						$finalResult .= "<h1>" . $this->pageH1 . "</h1>\n";
					}else{
						return [
							"status"=>-1,
							"error"=>"The H1 is malformed, please fix this in the ODT file.",
						];
					}
				}elseif ( // Jesus christ what in the hell is this if statement
					($forceH2 === true) || (
						(trim($line) !== "") && (strpos($line, "<li>") === false) && trim(preg_replace("/^\d+$/", "", $line)) !== "" &&
						(
							(trim(mb_strtoupper(preg_replace($FANBOYSCONJUNCTION_REGEX, " ", $line), 'utf-8')) == trim(preg_replace($FANBOYSCONJUNCTION_REGEX, " ", $line)) || trim(ucwords(strtolower(preg_replace($FANBOYSCONJUNCTION_REGEX, " ", $line)))) == trim(preg_replace($FANBOYSCONJUNCTION_REGEX, " ", $line)))
							||
							preg_match("/^[A-Z][A-ZA-Z\s\w\,\"\']*\?\s*$/is", $line) !== 0
							||
							trim(preg_replace("/([A-Z]\w+|[A-Z]+)/", "", preg_replace($FANBOYSCONJUNCTION_REGEX, "", $line))) == ""
							||
							preg_match("/^\s*Q\..+?$/", $line) !== 0 ||
							(trim(
								ucwords(
									preg_replace(
										$FANBOYSCONJUNCTION_REGEX, " ", str_replace(
											"&", " ", str_replace(
												"-", " ", $line
											)
										)
									)
								)
							)) == str_replace("&", " ", str_replace("-", " ", trim(preg_replace($FANBOYSCONJUNCTION_REGEX, " ", $line))))
						)
					)
				){
					// This is an h2
					// But is it already pre-tagged? (McKenzie)
					if (stripos($line, "h2") === false){
						$finalResult .= "<h2>$line</h2>\n";
					}else{
						++$this->totalErrorsInContent;
						$finalResult .= $line . "\n";
					}
				}elseif (stripos($line, "if") !== false && ( (stripos($line, " form") && preg_match("/form\s*\.*\s*/ism", $line)) || (stripos($line, "service request"))) != 0 && strlen($line) < 225){

					// Match phone numbers in the CTA and wrap them in anchors
					$line = str_replace("{{ phoneNumber which=1 }}", "<a class=\"req-num\" href=\"tel:+1{{ phoneNumber which=1 pure-number=1 }}\">{{ phoneNumber which=1 }}</a>", $line);

					/* The following wraps non-shortcoded phone numbers with anchors in the h3. Keeping, just in case we end up in another anti-shortcode spree.
					if (preg_match("/(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})/", $line) === 1){
						$line = preg_replace_callback("/(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})/", function($matches){
							return "<a class=\"req-num\" href=\"tel:+1-" . $matches[1] . "-" . $matches[2] . "-" . $matches[3] . "\">" . $matches[1] . "-" . $matches[2] . "-" . $matches[3] . "</a>";
						}, $line);
					}
					*/
					
					// H3 filter
					if (preg_match("/online\s*(.*?)\s*form/i", $line) === 1){
						$line = preg_replace_callback("/online\s*(.*?)\s*form/i", function($matches){
							return "<a class=\"req-form\" href=\"/contact-us\">online " . strtolower($matches[1]) . " form</a>";
						}, $line);
					}else{
						// Okay try this new wacko "online service request" format"
						$line = preg_replace_callback("/online\s*(.*?)\s*request/i", function($matches){
							return "<a class=\"req-form\" href=\"/contact-us\">online " . strtolower($matches[1]) . " request</a>";
						}, $line);
					}

					// Did the writer put <h3> or </h3> in this line? Remove it
					$currentLineLength = mb_strlen($line);
					$line = preg_replace("@<\/*\s*h3\s*>@", "", $line);

					if (mb_strlen($line) !== $currentLineLength){
						++$this->totalErrorsInContent;
					}

					//$finalResult .= "<h3>$line</h3>\n";
				}elseif (preg_match("/<ul>(.*?)<\/ul>/i", $line, $listMatches) !== 0){
					// This is an unordered list
					$liElements = $listMatches[1];
					$liElements = preg_replace("/<li>(.*?)<\/li>/", "\t<li class=\"\">$1</li>\n", $liElements);
					$finalResult .= "<ul class=\"\">\n$liElements</ul>\n";
				}else{
					// Just a normal paragraph
					if (trim($line) !== ""){
						// Make sure the a tag for this line doesn't already exist (McKenzie)
						if (stripos($line, "<h2>") === false && stripos($line, "<h3>") === false){
							// Is it a list?
							if (!preg_match("/^\s*<ul>/im", $line) && !preg_match("/^\s*<\/*li>/im", $line)){
								$finalResult .= "<p>\n\t" . $line . "\n</p>\n";
							}else{
								$finalResult .= $line . "\n";
							}
						}else{
							$finalResult .= $line . "\n";
						}
					}
				}
			}

			$this->relativeUrlPath = "/" . $this->relativeUrlPath;
			$this->relativeUrlPath = str_replace(".php", "", $this->relativeUrlPath);

			$headContent = "";

			$this->metaTitle = trim($this->metaTitle);
			$headContent .= "<title>" . $this->metaTitle . "</title>\n";
			$headContent .= "<meta name=\"description\" itemprop=\"description\" property=\"og:description\" content=\"" . $this->metaDescription . "\">";

			// Sanitize the file path of random tags
			$this->relativeUrlPath = str_replace("</a>", "", $this->relativeUrlPath);
			$this->relativeUrlPath = str_replace("<strong>", "", $this->relativeUrlPath);
			$this->relativeUrlPath = str_replace("</strong>", "", $this->relativeUrlPath);

			// Merge multiple <ul> that for some reason come in succession
			$finalResult = preg_replace("/\n<\/ul>\n<ul.+?>/ism", "", $finalResult);

			// Fixes "word<a" to "word <a"
			$finalResult = preg_replace("/(\w)(<a)/i", "$1 $2", $finalResult);

			// Fixes "<a href="[...]"> word" to "<a href="[...]">word"
			$finalResult = preg_replace("/(<a href=\".*\">)(\s)(\w)/i", "$1$3", $finalResult);

			// Fixes "word</a>another word" to "word</a> another word"
			$finalResult = preg_replace("/(\w<\/a>)(\w)/i", "$1 $2", $finalResult);

			// Fixes "word </a>another word" to "word</a> another word"
			$finalResult = preg_replace("/\s(<\/a>)(\w)/i", "$1 $2", $finalResult);

			$finalResult = str_replace("<h1>", "<h1 class=\"\">", $finalResult);
			$finalResult = str_replace("<h2>", "<h2 class=\"\">", $finalResult);

			return [
				"headContent"=>$headContent,
				"parsedContent"=>$finalResult,
				"filePathToCreate"=>$this->relativeUrlPath,
				"metaTitle"=>$this->metaTitle,
				"metaDescription"=>$this->metaDescription,
				"status"=>1,
				"errorsFixed"=>$this->totalErrorsInContent,
			];
		}
	}
?>

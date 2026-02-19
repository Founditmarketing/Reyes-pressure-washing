<?php
	require_once(__DIR__ . "/../Classes/ODTParser.php");

	header("Content-Type: application/json");

	$odtFile = $_FILES['content-file'];

	if ($odtFile){
		$filePath = $odtFile['tmp_name'];
		$fileName = $odtFile['name'];

		if (strpos($fileName, ".odt") === false){
			print(json_encode([
				"message"=>"Not a valid ODT file..$filePath",
				"status"=>-1,
			]));
			exit();
		}else{
			$parser = new ODTParser();
			$data = $parser->parseFile($filePath);
			if ($data['status'] == 1){
				print(json_encode([
					"fileData"=>$data,
					"status"=>1,
				]));
				exit();
			}else{
				print(json_encode($data));
				exit();
			}
		}
	}

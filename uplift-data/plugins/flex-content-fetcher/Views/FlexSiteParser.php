<?php
	require_once(__DIR__ . "/../../../../fbm-core/Classes/UserAccounts.php");
	$account = UserAccounts::getSessionUser();
	$pluginFolderUri = substr($subDirectoryInstall, 0, strlen($subDirectoryInstall) - 1) . CMSInternals::getUriFromPath(__DIR__ . "/..");

	if (empty($account)){
		http_response_code(403);
		exit();
	}
?>
<view_head>
	<title>Fetch & Parse Flex Website Content</title>
	<script defer src="<?= $subDirectoryInstall; ?>fbm-core/Dependencies/ace-editor/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
	<script defer src="<?= $subDirectoryInstall; ?>fbm-core/JS/EnableAceEditor.js"></script>
	<script defer src="<?= $pluginFolderUri; ?>/JS/FlexSiteContentFetcher.js?1"></script>
</view_head>
<view_body>
	<input type="hidden" id="plugin-uri" value="<?= $pluginFolderUri; ?>">
	<div class="container">
		<div class="row" id="main-dashboard">
			<div class="col-12">
				<h1 class="my-3">Flex Site Parser</h1>
			</div>

			<div class="col-12">
				<div class="card rounded shadow mb-4">
					<div class="card-header">
						<h2 class="h6 mb-0">
							Flex Site Content Fetcher
						</h2>
					</div>
					<div class="card-body">
						<p>
							Attempt to fetch and parse relevant old HTML and content from a Footbridge website designed in the Flex library.
						</p>
						<hr />
						<form id="flex-content-fetch-form">
							<div class="form-group">
								<small>Enter the old site's URL</small>
								<input type="text" class="form-control form-control-sm" placeholder="Fully qualified URL" id="external-url" name="external-url" />
							</div>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="convert-url-cases" name="convert-url-cases">
									<label class="custom-control-label" for="convert-url-cases">Convert same-domain URLs in content to lowercase</label>
								</div>
							</div>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="parsing-city-page" name="parsing-city-page">
									<label class="custom-control-label" for="parsing-city-page">Parsing city page?</label>
								</div>
							</div>
							<div id="city-page-parameters" style="display:none;" class="form-group col-12 px-0">
								<div class="form-row">
									<div class="col-xl-3">
										<div class="material-text-input">
											<label>City name</label>
											<input type="text" class="form-control" id="def-city-name">
										</div>
									</div>
									<div class="col-xl-3">
										<div class="material-text-input">
											<label>State name shorthand</label>
											<input type="text" class="form-control" id="def-state-name-shorthand">
										</div>
									</div>
									<div class="col-xl-3">
										<div class="material-text-input">
											<label>State name</label>
											<input type="text" class="form-control"  id="def-state-name">
										</div>
									</div>
									<div class="col-xl-3">
										<div class="material-text-input">
											<label>Service Areas URI</label>
											<input type="text" class="form-control"  id="def-service-areas-uri">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-sm btn-primary">
									<span>Fetch Content</span>
								</button>
							</div>
						</form>
						<form id="submit-page-build">
							<div id="result-container">
								<h2>Parsing Result</h2>
								<p>
									Result from gathering the content from the URL. Do not expect 100% accuracy.
								</p>
								<hr />
								<div id="content-images" class="d-none">
									<h2 class="h4">Images from Content</h2>
									<div class="d-flex flex-wrap"></div>
								</div>
								<div id="outputted-meta-contents-html" enable-ace-editor ace-lang="html" class="mb-4" style="height:170px;"></div>
								<div id="outputted-body-contents-html" enable-ace-editor ace-lang="html" style="height:400px;"></div>
								<br>
								<h2>Build Options</h2>
								<p>
									Options for building a page based on the fetched content.
								</p>
								<div class="form-group">
									<label for="page-name">Page Name</label>
									<input type="text" class="form-control form-control readonly" placeholder="Name" id="page-name" name="page-name" />
								</div>
								<div class="form-group">
									<label for="new-uri">New URI</label>
									<input type="text" class="form-control form-control readonly" placeholder="URI" id="new-uri" name="page-uri" />
								</div>
								<div class="form-group">
									<label for="page-layout">Page layout</label>
									<select autocomplete="off" id="page-layout" name="page-layout" class="form-control">
										<?php
											foreach(CMSInternals::getAvailableLayouts() as $layoutName){
												?>
												<option <?= $layoutName == "GeneralPage" ? "selected" : ""; ?> value="<?= $layoutName; ?>"><?= $layoutName; ?></option>
												<?php
											}
										?>
									</select>
								</div>
								<div class="form-group">
									<label for="page-type">Page type</label>
									<select autocomplete="off" id="page-type" name="page-type" class="form-control">
										<option value="General">General</option>
										<option value="Service">Service</option>
										<option value="City">City</option>
										<option value="Blog">Blog</option>
										<option value="Project">Project</option>
									</select>
								</div>
								<div style="display:none;" id="city-page-options">
									<div class="form-group row">
										<div class="col-xl-4">
											<lable for="city-name">City name</label>
											<input type="text" class="form-control" id="city-name" name="city-name">
										</div>
										<div class="col-xl-4">
											<lable for="state-name-shorthand">State name shorthand</label>
											<input type="text" class="form-control" id="state-name-shorthand" name="state-name-shorthand">
										</div>
										<div class="col-xl-4">
											<lable for="state-name">State name</label>
											<input type="text" class="form-control" id="state-name" name="state-name">
										</div>
									</div>
									<div class="custom-control custom-checkbox my-2">
										<input type="checkbox" class="custom-control-input" id="disable-generated-map" name="disable-generated-map">
										<label class="custom-control-label" for="disable-generated-map">Disable generated map</label>
									</div>
								</div>
								<div class="form-group">
									<h5>Breadcrumbs</h5>
									<div id="breadcrumbs-container" class="mb-1">

									</div>
									<button id="add-crumb" type="button" class="btn btn-primary">
										<svg width="1rem" height="1rem" viewBox="0 0 16 16" class="bi bi-plus-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
										</svg>
										<span>Add</span>
									</button>
								</div>
							</div>
							<hr>
							<div id="error" class="bg-danger text-white p-2 my-2" style="display:none;">{{ error }}</div>
							<button id="submit-build-request-button" type="submit" class="btn btn-success">
								<svg width="1rem" height="1rem" viewBox="0 0 16 16" class="bi bi-hammer" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path d="M9.812 1.952a.5.5 0 0 1-.312.89c-1.671 0-2.852.596-3.616 1.185L4.857 5.073V6.21a.5.5 0 0 1-.146.354L3.425 7.853a.5.5 0 0 1-.708 0L.146 5.274a.5.5 0 0 1 0-.706l1.286-1.29a.5.5 0 0 1 .354-.146H2.84C4.505 1.228 6.216.862 7.557 1.04a5.009 5.009 0 0 1 2.077.782l.178.129z"/>
									<path fill-rule="evenodd" d="M6.012 3.5a.5.5 0 0 1 .359.165l9.146 8.646A.5.5 0 0 1 15.5 13L14 14.5a.5.5 0 0 1-.756-.056L4.598 5.297a.5.5 0 0 1 .048-.65l1-1a.5.5 0 0 1 .366-.147z"/>
								</svg>
								<span>Build Page</span>
							</button>
							<a id="view-in-editor-button" style="display:none;" target="_blank" href="" class="btn btn-primary">
								<svg width="1rem" height="1rem" viewBox="0 0 16 16" class="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
									<path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
								</svg>
								<span>View in Editor</span>
							</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="crumb-template" style="max-width:750px; display:none;" class="form-group form-row">
		<div class="col-xl">
			<label>Crumb Label</label>
			<input name="" class="label form-control" type="text" placeholder="Label" value="">
		</div>
		<div class="col-xl">
			<label>Crumb URI</label>
			<input name="" class="uri form-control" type="text" placeholder="URI" value="">
		</div>
		<div class="col-xl d-flex">
			<button style="align-self:flex-end;" type="button" class="remove btn btn-danger">
				<svg width="1rem" height="1rem" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
				</svg>
			</button>
		</div>
	</div>
</view_body>

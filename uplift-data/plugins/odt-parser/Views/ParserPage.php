<?php
	// For resources, get the plugin folder's URI
	$pluginFolderUri = substr($subDirectoryInstall, 0, strlen($subDirectoryInstall) - 1) . CMSInternals::getUriFromPath(__DIR__ . "/..");
?>
<view_head>
	<title>Parse ODT Files to HTML</title>
	<script defer src="<?= $subDirectoryInstall; ?>fbm-core/Dependencies/ace-editor/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?= $subDirectoryInstall; ?>fbm-core/JS/EnableAceEditor.js" defer></script>
	<script src="<?= $pluginFolderUri; ?>/js/ODTHtmlParser.js?1" defer></script>
</view_head>
<view_body>
	<input type="hidden" id="plugin-folder-uri" value="<?= $pluginFolderUri; ?>">
	<div class="container">
		<div class="row" id="main-dashboard">
			<div class="col-12">
				<h1 class="my-3">ODT File Parser</h1>
			</div>

			<div class="col-12">
				<div class="card rounded shadow mb-4">
					<div class="card-header">
						<h2 class="h6 mb-0">
							Parse ODT into HTML & CMS Data
						</h2>
					</div>
					<div class="card-body">
						<div id="odt-file-box" class="drag-file-box">
							<div class="d-flex flex-column h-100 justify-content-center align-items-center">
								<div class="mb-2 non-spinner">No file chosen. Drag and drop a file.</div>
								<div class="text-center non-spinner" style="font-size:2rem;">
									<svg width="3rem" height="3rem" viewBox="0 0 16 16" class="bi bi-cloud-arrow-up-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 1 0V6.707l1.146 1.147a.5.5 0 0 0 .708-.708z"/>
									</svg>
								</div>
								<div style="display:none;" class="spinner spinner-border text-primary" role="status">
									<span class="sr-only">Loading...</span>
								</div>
							</div>
						</div>
						<form style="display:none;" id="parsed-content-file-stuff">
							<input type="hidden" id="allow-overwrites" name="allow-overwrites" value="0">
							<input type="hidden" id="page-head" name="page-head">
							<input type="hidden" id="page-content" name="page-content">
							<div class="mb-3">
								<button type="button" class="back-to-uploader btn btn-info">
									<svg width="1rem" height="1rem" viewBox="0 0 16 16" class="bi bi-arrow-left-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5.5a.5.5 0 0 0 0-1H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5z"/>
									</svg>
									<span>Back to Uploader</span>
								</button>
							</div>
							<div class="form-group">
								<label for="page-name">Page name</label>
								<input class="form-control" type="text" id="page-name" name="page-name" value="" placeholder="">
							</div>
							<div class="form-group">
								<label for="page-uri">Page uri</label>
								<input class="form-control" type="text" id="page-uri" name="page-uri" value="" placeholder="">
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
							<div class="form-group">
								<label for="page-layout">Page layout</label>
								<select autocomplete="off" id="page-layout" name="page-layout" class="form-control">
									<option value="0">- Choose Layout -</option>
									<?php
										foreach(CMSInternals::getAvailableLayouts() as $layoutName){
											?>
											<option value="<?= $layoutName; ?>"><?= $layoutName; ?></option>
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
								</select>
							</div>
							<div style="display:none;" id="city-page-options">
								<div class="form-group row">
									<div class="col-xl-4">
										<lable for="city-name">City name</label>
										<input type="text" class="form-control" id="city-name" name="city-name">
									</div>
									<div class="col-xl-4">
										<lable for="state-name">State name</label>
										<input type="text" class="form-control" id="state-name" name="state-name">
									</div>
									<div class="col-xl-4">
										<lable for="state-name-shorthand">State name shorthand</label>
										<input type="text" class="form-control" id="state-name-shorthand" name="state-name-shorthand">
									</div>
								</div>
								<div class="form-group">
									<label for="city-url">City's official website, or Wikipedia page for the city</label>
									<input class="form-control" type="text" id="city-url" name="city-url" value="" placeholder="">
								</div>
								<div class="custom-control custom-checkbox my-2">
									<input type="checkbox" class="custom-control-input" id="disable-generated-map" name="disable-generated-map">
									<label class="custom-control-label" for="disable-generated-map">Disable generated map</label>
								</div>
							</div>
							<div class="form-group">
								<label>Head contents</label>
								<div id="head-ace-editor" enable-ace-editor ace-lang="html" style="height:100px;"></div>
							</div>
							<div class="form-group">
								<label>Body contents</label>
								<div id="body-ace-editor" enable-ace-editor ace-lang="html" style="height:400px;"></div>
							</div>
							<div class="custom-control custom-checkbox my-2">
								<input type="checkbox" class="custom-control-input" id="exclude-from-sitemap" name="exclude-from-sitemap">
								<label class="custom-control-label" for="exclude-from-sitemap">Exclude from sitemap</label>
							</div>
							<div class="form-group">
								<button id="build-button" type="submit" class="btn btn-success">
									<svg width="1rem" height="1rem" viewBox="0 0 16 16" class="bi bi-arrow-left-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5.5a.5.5 0 0 0 0-1H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5z"/>
									</svg>
									<span>Auto-Build Page</span>
								</button>
							</div>
						</form>
						<section style="display:none;" id="page-build-success">
							<div class="mb-3">
								<div class="bg-success text-success-contrast p-4">Page built!</div>
							</div>
							<div>
								<button type="button" class="back-to-uploader btn btn-info">
									<svg width="1rem" height="1rem" viewBox="0 0 16 16" class="bi bi-arrow-left-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5.5a.5.5 0 0 0 0-1H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5z"/>
									</svg>
									<span>Back to Uploader</span>
								</button>
								<a id="view-in-editor" href="" type="button" class="btn btn-primary">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
										<path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
									</svg>
									<span>View in Editor</span>
								</a>
							</div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="error-modal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header bg-danger text-danger-contrast">
					<h5 class="modal-title">Error</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p id="modal-error"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">
						<span>Okay</span>
					</button>
				</div>
			</div>

		</div>
	</div>
	<div id="warning-modal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header bg-warning text-danger-contrast">
					<h5 class="modal-title">Warning</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p id="modal-warning"></p>
				</div>
				<div class="modal-footer">
					<button id="page-overwrite-confirm" type="button" class="btn btn-secondary" data-dismiss="modal">
						<span>Yes</span>
					</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">
						<span>No</span>
					</button>
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
			<input name="" class="uri form-control" type="text" placeholder="Label" value="">
		</div>
		<div class="col-xl d-flex">
			<button type="button" class="align-self-end remove btn btn-danger">
				<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
				</svg>
			</button>
			<button type="button" class="ml-2 align-self-end prefill-crumb-data btn btn-primary">
				<span>Prefill</span>
			</button>
		</div>
	</div>
</view_body>

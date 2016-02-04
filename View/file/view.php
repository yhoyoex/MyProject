<?php include ('document-js.php'); ?>
<div class="row" id="row">                   
	<div class="col-md-12"><!-- end page-header -->
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<div class="panel-heading-btn">
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-info" title="Add Document" onclick="showModalFile()"><i class="fa fa-plus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" title="Reload Data" onclick="reload_table()"><i class="fa fa-repeat"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				</div>
				<h4 class="panel-title">Document List</h4>
			</div>
			<div class="panel-body">
				<div style="padding-top:20px; padding-bottom:20px">
					<div class="form-group has-success has-feedback">
						<label class="col-md-3 control-label"></label>
						<div class="col-md-6">
							<div class="input-group custom-search-form">
								<input type="text" class="form-control input-lg search" name="keyword" id="search" placeholder="Search Document..." autofocus autocomplete="off">
								<span class="input-group-btn">
									<button class="btn btn-lg btn-success" type="button" id="btnSearch">
										<span class="glyphicon glyphicon-search"></span>
									</button>
								</span>
							</div>
							<br>
							<div><span>Type keyword and <span class="label label-inverse">ENTER</span> to new search or <span class="label label-inverse">ESC</span> to clear search box</span></div>
						</div>
					</div>
				</div>
				<div class="panel-body" id="result"></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_document" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal_file_body"></div>
</div>

<!-- #modal-View -->
<div class="modal modal-message fade" id="modal_view" tabindex="1" role="dialog" data-trigger="focus">
	<div class="modal-dialog modal_view_body" ></div>
</div>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-use-bootstrap-modal="false" >
	<div class="slides"></div>
	<h3 class="title"></h3>
	<a class="prev">‹</a>
	<a class="next">›</a>
	<a class="close">×</a>
	<a class="play-pause"></a>
	<ol class="indicator"></ol>
</div>

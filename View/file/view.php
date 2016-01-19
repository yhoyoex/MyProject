
<?php include ('datatables_js.php'); ?>
<div class="row" id="row">                   
    <div class="col-md-12"><!-- end page-header -->
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-info" title="Add Document" onclick="showModals()"><i class="fa fa-plus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" title="Reload Data" onclick="reload_table()"><i class="fa fa-repeat"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <h4 class="panel-title">Document List</h4>
            </div>
            <div class="panel-body">
            <div style="padding-top:20px; padding-bottom:20px">
                <div class="form-group has-success">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control input-lg" name="keyword" id="search" placeholder="Search Document..." autofocus >
                            <span class="input-group-btn">
                                <button class="btn btn-lg btn-success" type="button" id="btnSearch">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
                <div class="panel-body" id="result"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_files" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Files Form</h3>
            </div>
            <div class="modal-body">
                <form action="" id="form" name="form" class="form-horizontal" method="POST" role="form" autocomplete="off">
                    <div class="form-body">
                        <input type="hidden" class="form-control" value="" name="id" id="id"/>
                        <div class="form-group">
                            <label class="control-label col-md-3">File Name</label>
                            <div class="col-md-9">
                                <input name="file_name" id="file_name" placeholder="File Name" data-minlength="6" class="form-control" type="text" required>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" id="description" placeholder="Description" data-minlength="6" class="form-control" rows="3" type="text" required></textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tags</label>
                            <div class="col-md-9  typeahead">
                                <input name="tag" class="form-control" id="tags" placeholder="Input Tag" type="text" value="">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Memo</label>
                            <div class="col-md-9">
                                <textarea name="memo" id="memo" placeholder="Memo" class="form-control" rows="3" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <table id="image_table" role="presentation" class="table table-hover">
                            <tbody id="image_list" class="files"></tbody>
                        </table>
                        <div class="form-group">
                            <div class="col-md-9">
                                <div class="col-lg-7">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Add files...</span>
                                        <input type="file" class="form-control" name="files[]" id="fileInput" multiple="">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="fileupload-buttonbar">
                            <div class="modal-footer">
                                <button type="submit" name="submit" id="btnSave" onclick="validation()" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-danger delete" onclick="clearModals()" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- #modal-View -->
<div class="modal modal-message fade" id="modal_view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 align="center" class="modal-title" id="modal_view_title"></h3>
            </div>
            <div class="modal-body" id="modal_view_body">
              
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-primary" data-dismiss="modal">Download</a>
                <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Delete File</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger m-b-0">
                    <h4><i class="fa fa-info-circle"></i> Are you sure to delete this file ?</h4>
                </div>
                <input type="hidden" value="" name="id"/> 
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-sm btn-warning" data-dismiss="modal">Cancel</a>
                <a href="javascript:;" class="btn btn-sm btn-danger" onclick="submitData()" data-dismiss="modal">Delete</a>
            </div>
        </div>
    </div>
</div>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<?php include ('template_files.php'); ?>


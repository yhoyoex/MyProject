<form action="" id="form" name="form" class="form-horizontal" method="POST" role="form" autocomplete="off">
    <div class="form-body">
        <input type="hidden" class="form-control" value="" name="id" id="id"/>
        <div class="form-group">
            <label class="control-label col-md-3">File Name</label>
            <div class="col-md-9">
                <input name="file_name" id="file_name" placeholder="File Name" data-minlength="6" class="form-control" type="text" value="<?= $this->data['file_name'] ?>" required>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Description</label>
            <div class="col-md-9">
                <textarea name="description" id="description" placeholder="Description" data-minlength="6" class="form-control" rows="3" type="text" required><?= $this->data['description'] ?></textarea>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Tags</label>
            <div class="col-md-9  typeahead">
                <input name="tag" class="form-control" id="tags" placeholder="Input Tag" type="text" value="<?= $this->data["tag"] ?>" />
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Memo</label>
            <div class="col-md-9">
                <textarea name="memo" id="memo" placeholder="Memo" class="form-control" rows="3" type="text"><?= $this->data['memo'] ?></textarea>
                <span class="help-block"></span>
            </div>
        </div>
        <table id="image_table" role="presentation" class="table table-hover">
            <tbody>
                <tr class="template-download">
                <?php foreach($this->data["images"] as $image) { ?>
                    <td>
                        <span class="preview">
                            <a href="<?= URL ?>public/file/files/<?= $image['img_name'] ?>" title=" <?= $image['img_name'] ?>" download="<?= $image['img_name'] ?>"  data-gallery >
                                <img src="<?= URL ?>public/file/files/thumbnail/<?= $image['img_name'] ?>" />
                            </a>
                        </span>
                    </td>
                    <td name="img_name" id="img_name">
                        <p class="name">
                            <a href="<?= URL ?>public/file/files/<?= $image['img_name'] ?>" title="<?= $image['img_name'] ?>" download="<?= $image['img_name'] ?>" data-gallery><?= $image['img_name'] ?></a>
                        </p>
                    </td>
                    <td><?= $image['img_size'] ?></td>
                    <td>
                        <button href="javascript:void(0)" class="btn btn-danger delete" onclick="deleteImage("<?= $image['img_name'] ?>")">
                            <i class="glyphicon glyphicon-trash"></i><span> Delete</span>
                        </button>
                    </td>
                <tr>
                <?php } ?> 
            </tbody>
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

<script>
    
    tags();
    
</script>
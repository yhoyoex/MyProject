<div class="modal-header">
	<h3 align="center" class="modal-title" id="modal_view_title"><?= $this->data["file_name"] ?></h3>
</div>
<div class="modal-body" >
	<table class="table borderless">
		<tr>
			<td width="15%"><strong>Description</strong></td>
			<td><span name="description"><?= $this->data["description"] ?></span></td>
		</tr>
		<tr>
			<td><strong>File Tag</strong></td>
			<td><span name="tags"><?= "<div class='badge badge-success'>" . join("</div> <div class='badge badge-success'>", split(",+", $this->data["tag"])) . "</div>"?></span></td>    
		</tr>
		<tr>
			<td><strong>Create Date</strong></td>
			<td><span name="create"><?= date("d M Y @ H:i", strtotime($this->data["create_date"])) ?> </span> by <span name="create-by"><?= $this->data["create_user_name"] ?> </span></td>    
		</tr>
		<tr>
			<td><strong>Memo</strong></td>
			<td><span name="memo"> <?= $this->data["memo"] ?></span></td>    
		</tr>
		<tr>
			<td><strong>Updated at</strong></td>
			<td><span name="update-date"> <?= get_timeago(strtotime($this->data["archived_date"])) ?> </span> by <span name="update-by"><?= $this->data["archived_user_name"] ?> </span></td>    
		</tr>
		<tr>
			<td colspan="3">
				<span id="image_thumb">
					<?php foreach($this->data["images"] as $image) { ?>
						<a href="<?= URL ?>public/file/files/<?= $image["img_name"] ?>" title="<?= $image["img_name"] ?>" data-gallery>
							<img src="<?= URL ?>public/file/files/thumbnail/<?= $image["img_name"] ?>" width="120px" class = "img-thumbnail"/>
						</a>
					<?php } ?>
				</span>
			</td>
		</tr>
	</table>
</div>
<div class="modal-footer">
	<a href="File/download" target="_blank" class="btn btn-sm btn-primary">Download</a>
	<a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
</div>


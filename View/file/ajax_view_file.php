	<div class="modal-content" id="modal_view_body">
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
					<?php
						$image_per_row = 6;
						$image_list_count = count($this->data["images"]);
						$column_width = 12 / $image_per_row;

						$i = 1;
						$row = 1;
					
	                    foreach($this->data["images"] as $image) {
	                        // log in
	                        $server = $this->client_info['ip_server'] ;
	                        $url = "http://" . $server . "login/client_login/R";                
	                        $fields = array(
	                            "client_id" => $this->client_info['client_name'],
	                            "client_password" => $this->client_info['client_password']
	                        );                        
	                        foreach ($fields as $key => $v) {
	                            $field_string .= $key .'=' . $v . '&';
	                        }
	                        $field_string = rtrim($field_string, '&');     
	                        $curl = curl_init();
	                        curl_setopt($curl, CURLOPT_URL, $url);
	                        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	                        curl_setopt($curl, CURLOPT_HEADER, false);
	                        curl_setopt($curl, CURLOPT_POST, count($fields));
	                        curl_setopt($curl, CURLOPT_POSTFIELDS, $field_string);
	                        $token = trim(curl_exec($curl));
	                        curl_close($curl);

	                        //log out
	                        $url = "http://" . $server . "login/client_logout/";// . $token_array[$i];        
	                        foreach ($fields as $key => $v) {
	                            $field_string .= $key .'=' . $v . '&';
	                        }

	                        $curl = curl_init();
	                        curl_setopt($curl, CURLOPT_URL, $url);
	                        curl_exec($curl);
	                        curl_close($curl);
		                ?>
		                <div class="col-md-<?= $column_width ?>">
            				<div class="image-thumbnail">
	            				<div class="image-circle-top-right-download">
	            					<a href="File/get_preview_img/<?= $image["img_store_name"] ?>" download="File/get_preview_img/<?= $image["img_store_name"] ?>" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-download-alt" ></i></a>
	            				</div>
	            				<div style="margin-bottom:20px;">                        
	                				<a href="File/get_preview_img/<?= $image["img_store_name"] ?>" title="<?= $image["img_name"] ?>"data-gallery>
										<img src="http://<?= $server ?>image/download/<?= $image["img_store_name"] ?>/small?token=<?= $token ?>&client_id=hrd" class = "img-thumbnail"/>
									</a>
								</div>
	                		</div>
                		</div>
						<?php } ?>
					</td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			<a href="File/download/<?= $this->data["id"] ?>" target="_blank" class="btn btn-sm btn-success">Download attachment</a>
			<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal" id="cancel">Close</a>
		</div>
	</div>





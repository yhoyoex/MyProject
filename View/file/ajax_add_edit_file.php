<?php 
    $server = $this->client_info['ip_server'] ;
    $client_name = $this->client_info['client_name'];
    
?>
<?php include ('template_files.php'); ?>

<div class="modal-content" id="modal_file_body">
 <div class="modal-header">
     <h3 class="modal-title"></h3>
 </div>
 <div class="modal-body">
    <div class="alert alert-danger m-b-0" id="deleteAlert">
        <h4><i class="fa fa-info-circle"></i> Are you sure to delete this file ?</h4>
    </div>
    <br>
     <form action="" id="form" name="form" class="form-horizontal" method="POST" role="form" autocomplete="off">
         <div class="form-body">
             <input type="hidden" class="form-control" value="<?= $this->res["id"] ?>" name="id" id="id"/>
             <div class="form-group">
                 <label class="control-label col-md-3">File Name</label>
                 <div class="col-md-9">
                     <input name="file_name" id="file_name" placeholder="File Name" data-minlength="6" value="<?= $this->res["file_name"] ?>" class="form-control" type="text" autofocus required>
                     <div class="help-block with-errors"></div>
                 </div>
             </div>
             <div class="form-group">
                 <label class="control-label col-md-3">Description</label>
                 <div class="col-md-9">
                     <textarea name="description" id="description" placeholder="Description" data-minlength="6" class="form-control" rows="3" type="text" required><?= $this->res["description"] ?></textarea>
                     <div class="help-block with-errors"></div>
                 </div>
             </div>
             <div class="form-group" id="tagsgroup">
                 <label class="control-label col-md-3">Tags</label>
                 <div class="col-md-9  typeahead">
                     <input name="tag" class="form-control" id="tags" placeholder="Input Tag" type="text" value="<?= $this->res["tag"] ?>">
                     <div class="help-block with-errors"></div>
                 </div>
             </div>
             <div class="form-group">
                 <label class="control-label col-md-3">Memo</label>
                 <div class="col-md-9">
                     <textarea name="memo" id="memo" placeholder="Memo" class="form-control" rows="3" type="text"><?= $this->res["memo"] ?></textarea>
                     <span class="help-block"></span>
                 </div>
             </div>
             <table id="image_table" role="presentation" class="table table-hover" width="100%">
                <tbody id="image_list" class="files">
                <?php
                    foreach($this->res["images"] as $image) { 
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
                     <tr class="template-download">
                         <td>
                            <span class="preview">
                                <a href="File/get_preview_img/<?= $image["img_store_name"] ?>" class="view_image" data-title="<?= $image["img_name"] ?>" data-gallery>
                                    <img src="http://<?= $server ?>image/download/<?= $image["img_store_name"] ?>/small?token=<?= $token ?>&client_id=hrd" width="80px" class ="img-thumbnail"/>
                                </a>
                            </span>
                         </td>
                         <td name="img_name" id="img_name" width="50%">
                             <p class="name"><?= $image["img_name"] ?></p>
                         </td>
                         <td><?= $image["img_size"] ?></td>
                         <td id="deleteImageGroup">
                            <a href="javascript:void(0)" class="label label-danger delete" id="deleteImagebtn" onclick="deleteImage('<?= $image["img_name"] ?>')">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                         </td>
                     <tr>
                 <?php } ?>
                 </tbody>
             </table>
             <div class="form-group" id="addImageGroup">
                 <div class="col-md-9">
                     <div class="col-lg-7">
                         <span class="btn btn-success fileinput-button">
                             <i class="glyphicon glyphicon-plus"></i>
                             <span>Add files...</span>
                             <input type="file" class="form-control" name="files[]" id="fileInput">
                         </span>
                        <!-- <input type="text" readonly class="form-control document_photo_url" name="document_photo_url[]"  placeholder="Photo URL " style="width:100%;"> -->
                     </div>
                 </div>
             </div>
             <div class="fileupload-buttonbar">
                 <div class="modal-footer">
                    <button type="button" class="btn delete" name="submit" id="btnDeleteFile" style="visibility:hidden;"></button>
                    <button type="button" class="btn btn-default delete" onclick="clearModals()" data-dismiss="modal" id="cancel">Cancel</button>
                    <button type="submit" class="btn btn-success" name="submit" id="btnSave" onclick="validation()">Save</button>
                 </div>
             </div>
         </div>
     </form>
 </div>
</div><!-- /.modal-content -->

<script>
var tags = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: {
        ttl : 1,
        cache : false,
        url: 'File/show_AllTags',
        filter: function(list) {
            return $.map(list, function(tagsSugest) {
                return { name: tagsSugest };
            });
        }
    }
});
tags.initialize();

var elt = $('.typeahead > input');

//set tags input & sugestion tags from database (bostrap-tagsinput & typeahead.js)
elt.tagsinput({
    typeaheadjs: {
        hint: false,
                highlight: true,
        name: 'tags_name',
        displayKey:'name',
        valueKey: 'name',
        source: tags.ttAdapter()
    }
});

function deleteImage( name ) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this image!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: "No, cancel!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm){
      if (isConfirm){
        $.ajax({
            type : "POST",
            url : "File/delete_image?name=" + name,
            dataType : 'JSON'
        });
        swal("Deleted!", "Your image has been deleted!", "success");
      } else {
        swal("Cancelled", "Your image is safe :)", "error");
      }
    });
}

function get_data_from_table() {
    var TableData;
    TableData = $.toJSON(storeTblValues());
                
    $.ajax({
        type : "POST",
        url : "File/ajax_update_file",
        data : "pTableData=" + TableData,
    });
}

function storeTblValues() {
    var TableData = new Array();
    var id = document.getElementById("id").value;
    $('#image_table tr.data').each(function(row, tr){
        TableData[row]={
            "files_id"          : id,
            "img_id"            : $(tr).find('td:eq(0)').text(),
            "img_name"          : $(tr).find('td:eq(1)').text(),
            "img_size"          : $(tr).find('td:eq(3)').text(),
            "img_store_name"    : $(tr).find('td:eq(2)').text(),
        }
    }); 
    return TableData;
}

function validation(){
    $('#form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            return false;
        } 
        else {
            submitData()
            return false;
        }
    })
}


$('.modal-footer').on( "click", '#btnSave', function(e){
    $('#btnDeleteFile').trigger('click');
});

$(function () {
    'use strict';
    $('#form').fileupload({
        xhrFields: {withCredentials: false},
        url: 'File/upload_file',
        //upload_dir : 'public/file/filess',
        //dataType: 'JSON',
        disableImageResize:/Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
        autoUpload: true,
        singleFileUploads: true,
        imageOrientation : true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png|pdf)$/i,
        maxFileSize: 5000000, // Maximum File Size in Bytes - 5 MB
        minFileSize: 10000, // Minimum File Size in Bytes - 10 KB
        previewMaxWidth: 50,
        previewMaxHeight: 50,
        previewCrop: true,
       
    });

    $('#form').bind('fileuploaddone', function (e, data) {
        var form_data = new FormData();
        var server = "<?= $server ?>";
        var client_name = "<?= $client_name ?>";
        var file_data = data['files'][0];
        form_data.append("client_id", client_name);
        form_data.append("image", file_data);
        $.ajax({
            url:"<?= URL ?>/server/get_token/",
            type:"GET",
            success: function (token, textStatus, jqXHR) {
                var token = token.trim();
                $.ajax({
                    url:"http://<?= $server ?>login/client_logout",
                    crossDomain:true,
                    type:"GET"
                });
                try{
                    $.ajax({
                        url:"http://" + server + "image/upload?token=" + token,
                        type:"POST",
                        data: form_data,
                        contentType:false,
                        cache:false,
                        processData:false,
                        crossDomain:true,
                        success: function (data) {
                            data.indexOf("login");
                            if(data.indexOf("login") > -1) {
                                swal("ERROR!","Cannot upload photo to server","error");
                            } else {
                                if(data.trim() != ""){
                                    if($(".document_photo_url").text() == ""){
                                        $(".document_photo_url").text(data.trim());
                                        $(".document_photo_url").removeClass( "document_photo_url" );
                                    }
                                    $.gritter.add({title:"Success",text:"Image Uploaded",sticky:false,image:"public/images/upload-image.png",
                                        before_open:function(){ if($(".gritter-item-wrapper").length===5){return false} }
                                    });
                                } else {
                                    $.gritter.add({title:"Error",text:"Cannot Upload Image",sticky:false,image:"public/images/error_message_icon.png",
                                        before_open:function(){ if($(".gritter-item-wrapper").length===5){return false} }
                                    });
                                }
                            }
                        },
                        error: function (e, textStatus, errorThrown) {
                            swal("ERROR!","error upload: " + errorThrown, "error");
                        }
                    });
                }catch(e){
                    alert(e);
                }
            }
        });
    });

    $('#form').fileupload(
        'option',
        'redirect',
        window.location.href.replace(/\/[^\/]/,'/cors/result.html?%s')
    );
});

function submitData() {
    get_data_from_table();

    if(save_method == 'add') {
        url = "File/ajax_add_file";
        close = $('.modal').modal('hide');
        notif = $.gritter.add({title:"Success",text:"File add to system",sticky:false,image:"public/images/add_page.png",
                    before_open:function(){ if($(".gritter-item-wrapper").length===3){return false} }
                });
    } 
    else if (save_method == 'edit') {
        url = "File/ajax_update_file";
        close = $('.modal').modal('hide');
        notif = $.gritter.add({title:"Success",text:"File Updated",sticky:false,image:"public/images/accept_page.png",
                    before_open:function(){ if($(".gritter-item-wrapper").length===3){return false} }
                });
    } 
    else {
        url = "File/ajax_delete_file";
        close = $('.modal').modal('hide');
        notif = $.gritter.add({title:"Deleted",text:"File Deleted",sticky:false,image:"public/images/delete_page.png",
                    before_open:function(){ if($(".gritter-item-wrapper").length===3){return false } }
                });
    }
    
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: $("#form").serialize(),
        success: function(data) {
            if(data.status) {
                close;
                notif;
            }
        }
    });
    clearModals();
    reload_table();
}
</script>
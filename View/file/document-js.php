<script type="text/javascript">

$(document).ready(function() {
    $( "#btnSearch" ).click ( function (e) {
       var search = $('#search').val();
       $.post(
            "File/view_result",
            {search: search},
            function(res){
            $("#result").html(res);
        });
    });

    $('#search').keypress( function (e) {
        if (e.which == 13) {
            $( "#btnSearch" ).click();
        }
    }); 

    $('#search').keyup(function(e){
        if(e.keyCode == 27) {
            $(this).val('');
        }
    });
            
 //   $('#search').on( 'keyup click', function () {
 //       $('#files').DataTable().search(
 //           $('#search').val()
 //       ).draw();
  //  });
});

function showModals( id ) {
    // Show data to edit or delete
    if( id ) {
        save_method = 'edit';
        $.ajax({
            type: "POST",
            url: "File/ajax_get_data/" + id,
            dataType: 'JSON',
            success: function(res){
                setModalData( res );
            }
        });
    }
    // add data
    else {
        save_method = 'add';
        $.ajax({
            type: "GET",
            url: "File/get_reserve_id",
            dataType: 'JSON',
            data: {id:id},
            success: function(res){
                setMOdalDataAdd( res );
            }
        });
    }
}

function showModalView( id ) {
    $('.modal-title').empty();
    $('#image_thumb').empty();
    $('[name="description"]').empty();
    $('[name="tags"]').empty();
    $('[name="create"]').empty();
    $('[name="create-by"]').empty();
    $('[name="memo"]').empty();
    $('[name="update-date"]').empty();
    $('[name="update-by"]').empty();
    $("#modal_view").modal("show");
/*    $.ajax({
        type: "POST",
        url: "File/ajax_get_data/" + id,
        dataType: 'json',
        data: {id:id},
        success: function(res){
            setMOdalDataView( res );
        }
    });
*/  
    $.post(
        "File/ajax_view_file/" + id,
        {},
        function(res){
            $("#modal_view_body").html(res);
        }
    );


}

function setMOdalDataAdd( data ) {
    //get & initialize sugestion data tag from database (typeahead.js include Bloodhound) 
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
            name: 'tags_name',
            displayKey:'name',
            valueKey: 'name',
            source: tags.ttAdapter()
        }
    });
    $('[name="id"]').val(data.id); //show id from to form input name="id"
    $("#modal_files").modal("show");
    $(".modal-title").html("Add New Document");
}

function setModalData( data ) {
    //get & initialize sugestion data tag from database (typeahead.js include Bloodhound) 
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
            name: 'tags_name',
            displayKey:'name',
            valueKey: 'name',
            source: tags.ttAdapter()
        }
    });
    elt.tagsinput("add",data.tag);
   
    $('[name="id"]').val(data.id); //show id from to form input name="id"
    $('[name="file_name"]').val(data.file_name); //show file name to form input name="file_name"
    $('[name="description"]').val(data.description); //show description value to input name="description"
    $('[name="memo"]').val(data.memo); //set memo data value to input name="memo" from database
    for(i = 0; i<data.images.length; i++){
        $('#image_list').append(
            '<tr class="template-download">'+
                '<td><span class="preview">'+
                    '<a href="<?= URL ?>public/file/files/'+ data.images[i].img_name + '" title="' + data.images[i].img_name + '" download="' + data.images[i].img_name + '" data-gallery>'+
                        '<img src="<?= URL ?>public/file/files/thumbnail/' + data.images[i].img_name + '" width=""/>'+
                '</a></span></td>'+
                '<td name="img_name" id="img_name">'+
                    '<p class="name">'+
                        '<a href="<?= URL ?>public/file/files/'+ data.images[i].img_name + '" title="' + data.images[i].img_name + '" download="' + data.images[i].img_name + '" data-gallery>'+ data.images[i].img_name +'</a>' + 
                    '</p></td>'+
                '<td>'+ data.images[i].img_size + '</td>'+
                '<td>'+ '<button href="javascript:void(0)" class="btn btn-danger delete" onclick="deleteImage('+"'"+data.images[i].img_name +"'"+')">'+
                    '<i class="glyphicon glyphicon-trash"></i><span> Delete</span></button' + '</td>'+
            '<tr>'
        );
    } 
    $('#modal_files').modal('show'); // show bootstrap modal
    $('.modal-title').text('Edit File'+' '+'('+data.file_name+')'); // Set title to Bootstrap modal title
}

//Submit data for add/edit/delete Data 
function submitData() {
    get_data_from_table();

    if(save_method == 'add') {
        url = "File/ajax_add_file";
        close = $('#modal_files').modal('hide');
        notif = $.gritter.add({title:"Success",text:"File add to system",sticky:false,image:"public/images/add_page.png",
                    before_open:function(){ if($(".gritter-item-wrapper").length===3){return false} }
                });
    } 
    else if (save_method == 'edit') {
        url = "File/ajax_update_file";
        close = $('#modal_files').modal('hide');
        notif = $.gritter.add({title:"Success",text:"File Updated",sticky:false,image:"public/images/accept_page.png",
                    before_open:function(){ if($(".gritter-item-wrapper").length===3){return false} }
                });
    } 
    else {
        url = "File/ajax_delete_file";
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

//Delete Data
function deleteData( id ) {
    save_method = 'delete';
    clearModals();
    $.ajax({
        type: "POST",
        url: "File/ajax_get_data",
        dataType: 'json',
        data: {id:id},
        success: function(data){
            $('.modal-title').text('Delete File'+' '+'('+data.file_name+')');
            $('[name="id"]').val(data.id); //show id from to form input name="id"
            $("#modal_delete").modal("show"); 
        }
    });
}

function deleteImage( name ) {
    if(confirm('Are you sure delete this image?')){
        $.ajax({
            type: "POST",
            url: "File/delete_image?name=" + name,
            dataType: 'json',
        });
    }
}

function get_data_from_table() {
    var TableData;
    TableData = $.toJSON(storeTblValues());
                
    $.ajax({
        type: "POST",
        url: "File/ajax_update_file",
        data: "pTableData=" + TableData,
    });
}

function storeTblValues() {
    var TableData = new Array();
    var id = document.getElementById("id").value;
    
    $('#image_table tr.data').each(function(row, tr){
        TableData[row]={
            "img_id" : $(tr).find('td:eq(0)').text(),
            "img_name" : $(tr).find('td:eq(1)').text(),
            "img_size" : $(tr).find('td:eq(2)').text(),
            "files_id" : id,
        }
    }); 
    //TableData.shift();
    return TableData;
}

function reload_table() {
    files.ajax.reload(null,false); //reload datatable ajax 
}

function clearModals() {
    $('#form')[0].reset(); // reset form on modals
    //$('#form').validator('destroy');
    $('#tags').tagsinput("removeAll"); // reset tag field
    $('table tbody.files').empty(); //reset field upload image
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#description').empty(); //clear description textarea input
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

</script>
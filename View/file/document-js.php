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

    $('#modal_document').on('shown.bs.modal', function (e) {
        $("#file_name").focus();
    });

    $('#modal_document').on('hidden.bs.modal', function (e) {
        e.preventDefault();

    });

    var modalIsOpen = false ;
    var listener = new window.keypress.Listener();
    
    $('.modal').on('shown.bs.modal', function(e) {
        modalIsOpen = true;
        listener.unregister_combo("enter");
        listener.unregister_combo("esc");
        listener.simple_combo("insert", function() {
            $("#btnSave").click();
        });
        listener.simple_combo("ctrl =", function() {
            $("#fileInput").click();
        });
        listener.simple_combo("esc", function() {
            $("#cancel").click();
        });
    });
    
    $('.modal').on('hidden.bs.modal', function(e) { 
        modalIsOpen = false; 
        listener.unregister_combo("insert");
        listener.unregister_combo("ctrl =");
        listener.unregister_combo("esc");
        listener.simple_combo("enter", function() {
            $("#btnSearch").click();
        });
        listener.simple_combo("ctrl i", function() {
            if (modalIsOpen) return;
            showModalFile();
        });
        listener.simple_combo("esc", function() {
            $('#search').val('');
        });
        clearModals();
    });

    $(window).keypress(function (e) {
        if (modalIsOpen) return;
        if($(!'#search').focus()) {
            $('#search').focus();
        }
    });

    listener.simple_combo("ctrl i", function() {
        showModalFile();
    });

    listener.simple_combo("esc", function() {
        $('#search').val('');
    });

    listener.simple_combo("enter", function() {
        $("#btnSearch").click();
    });

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

    $('#search').typeahead(null, {
        name: 'tags_name',
        displayKey:'name',
        valueKey: 'name',
        source: tags.ttAdapter()
    });
});

function showModalFile( id ) {
    
    if( id ) {
        save_method = 'edit';
        $.post(
            "File/ajax_get_data_file/" + id,
            {},
            function(res){
                $(".modal_file_body").html(res);
                $(".modal-title").html("Edit Document");
                $("#file_name").focus();
                $("#deleteAlert").hide();
            }
        );
        $("#modal_document").modal("show");
    }
    
    else {
        save_method = 'add';
        $.post(
            "File/reserve_id/",
            {},
            function(res){
                $(".modal_file_body").html(res);
                $(".modal-title").html("Add New Document");
                $("#file_name").focus();
                $("#deleteAlert").hide();
            }
        );
        $("#modal_document").modal("show");
    }
}

function showModalFileToDelete(id) {
    save_method = 'delete';
    $.post(
        "File/ajax_get_data_file/" + id,
        {},
        function (res){
            $(".modal_file_body").html(res);
            $(".modal-title").html("Delete Document");
            $("#btnSave").text('Delete');
            $('#form :input').prop('readonly', true);
            $('#addImageGroup').hide();
            $('#tagsgroup').hide();
            $('#image_table').hide();
        }
    );
    $("#modal_document").modal("show");
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
    $.post(
        "File/ajax_view_file/" + id,
        {},
        function(res){
            $(".modal_view_body").html(res);
        }
    );
    $("#modal_view").modal("show");
}

//Delete Data
function deleteData( id ) {
    save_method = 'delete';
    clearModals();
    $.ajax({
        type : "POST",
        url : "File/ajax_get_data",
        dataType : 'json',
        data : {id:id},
        success: function(data){
            $('.modal-title').text('Delete File'+' '+'('+data.file_name+')');
            $('[name="id"]').val(data.id); //show id from to form input name="id"
            $("#modal_delete").modal("show");
        }
    });
}

function reload_table() {
    files.ajax.reload(null,false); //reload datatable ajax 
}

function clearModals() {
    $('#form')[0].reset(); // reset form on modals
    $('#blueimp-gallery').removeAttr("data-filter");
    $('#file_name').val('');
    $('#memo').val('');
    $(".modal-title").empty('');
    //$('#form').validator('destroy');
    $('#tags').tagsinput("removeAll"); // reset tag field
    $('table tbody.files').empty(); //reset field upload image
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#description').empty(); //clear description textarea input
}
</script>
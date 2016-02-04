<?php
?>

<div id="page-title" class="page-title-blue">
    <h1>Client</h1>
</div>

<div id="page-container">          
</div>

<?php 
if($_SESSION['status'] != ""){
    //show_alert($_SESSION['status']);
}
?>

<script>    
    load_client(false,"");
    
    $(document).on("click",".button",function(){        
        if($(this).data("action") == "delete"){
            var hash_id = $(this).data("id");
            
            if(confirm("Delete this client ? ") == true){
                //window.location.href = "<?= URL ?>client/delete_client/" + hash_id;
                $.ajax({
                    type:"GET",
                    url:"<?= URL ?>client/delete_client/" + hash_id,
                    success: function (data, textStatus, jqXHR) {
                        load_client(true, "Success",1);                        
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        load_client(true, "Error",0);                        
                    }
                });
                
            }else{
                return false;
            }
        }
    });            
    
    function save_client(client_name, client_password){
        
        try{
            $.ajax({
                type:"POST",
                url:"<?= URL ?>client/save_client",
                data:{
                    "client_name":client_name,
                    "client_password":client_password
                },
                success: function (data, textStatus, jqXHR) {
                    load_client(true, "Success",1);                                        
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    load_client(true, "Error",0);                    
                }
            });            
        }catch(e){
            alert(e);
        }
    }
    
    function load_client(show,alertMessage, successState){
        try{                        
            $.ajax({
                url : "<?= URL ?>client/ajax_client_list",
                type: "GET",
                success: function (data) {                    
                    $("#page-container").html(data);   
                    show == true ? showAlert(alertMessage,successState) : "";
                }
            });
        }catch (err){
            alert(err);
        }
    }
    
    function showAlert(alertMessage, successState){
        successState == 1 ? $(".alert").addClass("alert-success") : $(".alert").addClass("alert-error");
        $(".alert").show();
        $(".alert").text(alertMessage);
        $(".alert").fadeOut(3000);
    }
</script>
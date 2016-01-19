
function ajax_link(target_address, target_window){
    $.ajax({
        url: target_address,
        success: function( data ) {
            $( "#" + target_window ).html( data );
        },
        error: function (error) {
            console.log(error);
        }
    });        
}

<a href="javascript:void(0)" id="test" onclick="get_url('scasscsd')">
	test
</a>

<script>
	function get_url(name) {
		$.ajax({
    		type: 'post',
		    url: 'get_preview_img/' + name,
		    dataType: 'json',
		    success: function(res) {
		    	console.log(res);
		    	swal("Success", res, "success");
		    }
		});
	}
</script>
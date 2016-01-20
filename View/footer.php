</div>
<!-- end #content -->
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	
	<!--[if lt IE 9]>
		<script src="<?php echo URL; ?>public/crossbrowserjs/html5shiv.js"></script>
		<script src="<?php echo URL; ?>public/crossbrowserjs/respond.min.js"></script>
		<script src="<?php echo URL; ?>public/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="<?php echo URL; ?>public/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo URL; ?>public/plugins/jquery-cookie/jquery.cookie.js"></script>
    <script src="<?php echo URL; ?>public/js/moment.js"></script>
    <script src="<?php echo URL; ?>public/js/jquery.json.js"></script>
    <script src="<?php echo URL; ?>public/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
    <script src="<?php echo URL; ?>public/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
    <script src="<?php echo URL; ?>public/plugins/bootstrap-validator/dist/js/bootstrapValidator.js"></script>
    <script src="<?php echo URL; ?>public/plugins/bootstrap-validator/validator.js"></script>
    <script src="<?php echo URL; ?>public/plugins/bootstrap-add-clear/bootstrap-add-clear.js"></script>
	<!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE JS JQUERY FILE UPLOAD ================== -->
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/vendor/tmpl.min.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/vendor/load-image.min.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/jquery.fileupload-process.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/jquery.fileupload-image.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/jquery.fileupload-audio.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/jquery.fileupload-video.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/jquery.fileupload-validate.js"></script>
    <script src="<?php echo URL; ?>public/plugins/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
    <script src="<?php echo URL; ?>public/js/test.js"></script>
    <!--[if (gte IE 8)&(lt IE 10)]>
        <script src="assets/plugins/jquery-file-upload/js/cors/jquery.xdr-transport.js"></script>
    <![endif]-->
    <!-- ================== END PAGE JS JQUERY FILE UPLOAD ================== -->

	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="<?php echo URL; ?>public/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="<?php echo URL; ?>public/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','<?php echo URL; ?>public/js/analytics.js','ga');
        ga('create', 'UA-53034621-1', 'auto');
        ga('send', 'pageview');
    </script>
</body>
</html>
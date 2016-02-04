

$(function () {
    'use strict';

    $('#form').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'File/upload_file',
        dataType: 'json',
        disableImageResize:/Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
        autoUpload: false,
        singleFileUploads: true,
        imageOrientation : true,
        add: function(e, data) {
                var uploadErrors = [];
                var acceptFileTypes = /^image\/(gif|jpe?g|png)$|^application\/(pdf|msword)$|^text\/plain$/i;
                if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                    uploadErrors.push('Not an accepted file type');
                }
                if(data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 5000000) {
                    uploadErrors.push('Filesize is too big');
                }
                if(uploadErrors.length > 0) {
                    alert(uploadErrors.join("\n"));
                } else {
                    data.submit();
                }
        },

        //done: function(data) {
            //hide completed upload element in queue
          //  $(data.context);
            //here isoutput of uploaded objects
          //  console.log(data);
        //},
        
    });

    // Enable iframe cross-domain access via redirect option:
    $('#form').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );
        // Load existing files:
        $('#form').addClass('fileupload-processing');
       

});
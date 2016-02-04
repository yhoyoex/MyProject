<hr><br><br>
<div class="table-responsive">
    <table id="files" class="table table-bordered table-condensed table-striped" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th width="20%">File Name</th>
                <th width="20%">Description</th>
                <th width="20%">File Tag</th>
                <th>Date Create</th>
                <th width="15%"> Action </th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script type="text/javascript">
var files;
search = $('#search').val();
search = search.replace(/\s{2,}/g, ' ');
search = search.replace(/[^A-Z0-9\s]+/i, '');
$(document).ready(function() {
    files = $('#files').DataTable({
        "language": {
            "zeroRecords": "Document not found...",
            "loadingRecords": "Loading...",
            "processing":     "Processing...",
        },
        "pagingType": "full_numbers",
        "dom": '<"top">rt<"bottom"ip><"clear">',
        "sorting":[[3, "desc"]],
        "responsive": true,
        "lengthMenu": [ [15, 25, 50, -1], [15, 25, 50, "All"] ],
        "ajax": {
            "url": "File/view_content_result/" + search,
            "dataSrc": "files",
            "deferRender": true,
         },
        "columns": [
            { "data" : 'file_name' },
            { "data" : 'description' },
            { "data" : 'tag',},
            { "data" : 'create_date'},
            { "data" : 'action',"orderable" : false}
        ]
    });
/*

    $('#files').delegate('tbody > tr > td', 'click', function () {
        showModalView(id)
    });

   $('#files tfoot th').each(function (){
        //disable  search in footer colum action
        if( $(this).text() != "" ){
            //var title = $('#files thead th').eq( $(this).index() ).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" class="form-control" />' );
        }
    });

    // search in footer
    files.columns().every( function (){
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            that
            .search( this.value )
            .draw();
        });
    } );
*/
});

</script>
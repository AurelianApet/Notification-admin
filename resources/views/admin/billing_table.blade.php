@extends('admin.layouts.left')
@section('content')
<div class="right_col" role="main">
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div class="x_title">
            <h2>Billing Table</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
            <table id="group" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Emby Balance</th>
                        <th>Media Expired</th>
                        <th>Media Status</th>
                        <th>Output Balance</th> 
                        <th>Output Expired</th>
                        <th>Output Status</th>
                        <th>Output Name</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    {!!$billing_table_html!!}
                </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
</div>
<link href="assets/js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="assets/js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="assets/css/switchery/switchery.min.css" />
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="assets/js/datatables/dataTables.bootstrap.js"></script>
<script src="assets/js/datatables/dataTables.buttons.min.js"></script>
<script src="assets/js/datatables/buttons.bootstrap.min.js"></script>
<script src="assets/js/datatables/jszip.min.js"></script>
<script src="assets/js/datatables/pdfmake.min.js"></script>
<script src="assets/js/datatables/vfs_fonts.js"></script>
<script src="assets/js/datatables/buttons.html5.min.js"></script>
<script src="assets/js/datatables/buttons.print.min.js"></script>
<script src="assets/js/datatables/dataTables.fixedHeader.min.js"></script>
<script src="assets/js/datatables/dataTables.keyTable.min.js"></script>
<script src="assets/js/datatables/dataTables.responsive.min.js"></script>
<script src="assets/js/datatables/responsive.bootstrap.min.js"></script>
<script src="assets/js/datatables/dataTables.scroller.min.js"></script>
<script src="assets/js/switchery/switchery.min.js"></script>
<script>
    $(function(){
        $("#group").dataTable();
        if ($(".js-switch")[0]) {
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, {
            color: '#26B99A'
        },$(html).data());
    });
}
    })
</script>
@endsection

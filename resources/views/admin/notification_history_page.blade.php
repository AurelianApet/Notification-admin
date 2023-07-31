@extends('admin.layouts.left')
@section('content')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Notification History</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="notification_history" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>PreView</th>
                                <th>Date</th>
                                <th>Group</th>
                                <th>Recieved</th>
                            </tr>
                        </thead>
                        <tbody>
                            {!!$notification_list_html!!}
                        </tbody>
                    </table>
                    <a href="/notification_manage" class="btn btn-success btn-lg">Send Notification</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="previewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Preview</h2>
        </div>
        <div class="modal-body">
            <div class="">
                <div class="x_panel">
                    <div class="x_content">
                        <iframe id="notificationContent" style="width:100%;min-height: 500px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/js/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/datepicker/daterangepicker.js"></script>
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
<script>
    //add modal element
    var previewModal = document.getElementById("previewModal");
    $(document).ready(function(){
        $('#notification_history').dataTable();
        //refreshUserTable();
    });
    $('.close').on('click',function(){
        previewModal.style.display = "none";
    });
    $('.preview').on('click',function(){
        id = this.parentElement.parentElement.children[0].textContent;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/get_notification_content',
            type:'POST',
            data: {_token: CSRF_TOKEN,id: id},
            dataType: 'JSON',
            success: function(data){
                previewModal.style.display = "block";
                $('#notificationContent').contents().find("body").html("");
                document.getElementById('notificationContent').contentWindow.document.write(data.content);
            },
            fail: function(data){

            }
        });
    });
</script>
<style>
    .dot {
      height: 25px;
      width: 25px;
      background-color: #ccc;
      border-radius: 50%;
      display: inline-block;
    }
    </style>
@endsection
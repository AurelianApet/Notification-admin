@extends('admin.layouts.left')
@section('content')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Group Page</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="groupTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Privilege</th>
                                <th>Count of Users</th>
                                <th>Edit</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            {!!$group_list_html!!}
                        </tbody>
                    </table>
                    <button type="button" id="btnAddGroup" class="btn btn-success btn-lg">Add Group</button>
                    <button type="button" id="btnImportGroup" class="btn btn-success btn-lg">Import Group</button>
                </div>
            </div>
        </div>
    </div>
</div>
@if(session()->has('message'))
<div id="notificationModal" class="modal" style="display:block">
    <div style="width: 50%;text-align: center;position: absolute;left: 25%;">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Notification</h2>
        </div>
        <div class="modal-body" style="background: white;">
            <h3 id="notificationTxt">{{session()->get('message')}}</h3>
        </div>
    </div>
</div>
@endif
<div id="groupAddModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Add Group</h2>
        </div>
        <div class="modal-body">
            <div class="">
                <div class="x_panel">
                    <div class="x_content">
                        <form class="add-form form-horizontal form-label-left" method="POST" action="/add_group">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">Group Name</label>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <input type="text" id="groupName" name="groupName" class="form-control" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">Privilege</label>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <select name="privilege" class="form-control" id="privilege">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ln_solid"></div>
        
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button class="btn btn-primary cancelGroupBtn">Cancel</button>
                                    <button id="addGroupBtn" type="submit" class="btn btn-success">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="groupEditModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Edit Group</h2>
        </div>
        <div class="modal-body">
            <div class="">
                <div class="x_panel">
                    <div class="x_content">
                        <form class="add-form form-horizontal form-label-left" method="POST" action="/edit_group">
                            {{ csrf_field() }}
                            <input type="hidden" id="editGroupId" name="groupId" class="form-control" required="required">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">Group Name</label>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <input type="text" id="editGroupName" name="groupName" class="form-control" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">Privilege</label>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <select name="privilege" class="form-control" id="editPrivilege">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ln_solid"></div>
        
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button class="btn btn-primary cancelGroupBtn">Cancel</button>
                                    <button id="editGroupBtn" type="submit" class="btn btn-success">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="importGroupModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Import Group</h2>
        </div>
        <div class="modal-body">
            <div class="">
                <div class="x_panel">
                    <div class="x_content">
                        <form class="add-form form-horizontal form-label-left" method="POST" action="/group_import" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="file" class="form-control-file" name="fileToUpload" id="exampleInputFile" aria-describedby="fileHelp" required="required">
                                <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                            </div>
                            <button class="btn btn-primary cancelGroupBtn">Cancel</button>
                            <button type="submit" class="btn btn-primary">Import Group</button>
                        </form>
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
    var groupAddModal = document.getElementById('groupAddModal');
    var groupEditModal = document.getElementById('groupEditModal');
    var notificationModal = document.getElementById('notificationModal');
    var importGroupModal = document.getElementById('importGroupModal');

    $(document).ready(function(){
        $('#groupTable').dataTable();
        //refreshUserTable();
    });
    function refreshUserTable()
    {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/get_group_list/',
            type: 'POST',
            data: {_token: CSRF_TOKEN},
            dataType: 'JSON',
            success: function(data){
                console.log(data);
                $('#groupTable').dataTable();
            }
        });
    }
    $('#btnAddGroup').on('click',function(){
        groupAddModal.style.display = "block";
    });
    $('.close').on('click',function(){
        groupAddModal.style.display = "none";
        groupEditModal.style.display = "none";
        importGroupModal.style.display = "none";
        notificationModal.style.display = "none";
    });
    $('.cancelGroupBtn').on('click',function(){
        groupAddModal.style.display = "none";
        groupEditModal.style.display = "none";
        importGroupModal.style.display = "none";
    });
    $('.editBtn').on('click',function(){
        groupEditModal.style.display = "block";
        id = this.parentElement.parentElement.children[0].textContent;
        groupName = this.parentElement.parentElement.children[1].textContent;
        privilege = this.parentElement.parentElement.children[2].textContent;
        $('#editGroupName').val(groupName);
        $('#editGroupId').val(id);
        $('#editPrivilege').val(privilege);

    });
    $('#btnImportGroup').on('click',function(){
        importGroupModal.style.display = "block";
    });
</script>
@endsection
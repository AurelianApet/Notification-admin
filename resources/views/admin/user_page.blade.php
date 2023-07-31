@extends('admin.layouts.left')
@section('content')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>User Page</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="groupTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Password</th>
                                <th>Group Name</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Allowed</th>
                                <th>Edit</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            {!!$user_list_html!!}
                        </tbody>
                    </table>
                    <button type="button" id="btnAddUser" class="btn btn-success btn-lg">Add User</button>
                    <button type="button" id="btnImportUser" class="btn btn-success btn-lg">Import User</button>
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
<div id="userAddModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Add User</h2>
        </div>
        <div class="modal-body">
            <div class="">
                <div class="x_panel">
                    <div class="x_content">
                        <form class="add-form form-horizontal form-label-left" method="POST" action="/add_user">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">User Name</label>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <input type="text" id="userName" name="name" class="form-control" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3">Password</label>
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                        <input type="text" id="userPassword" name="pwd" class="form-control" required="required">
                                    </div>
                                </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">Group List</label>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <select name="groupId" class="form-control" id="groupId" required="required">
                                        {!!$group_list_html!!}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3">Login Allow</label>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <input type="checkbox" class="js-switch" name="isAllow" checked/>
                                </div>
                            </div>
                            <div class="form-group"  style="text-align:  center;">
                                <button type="button" id="add_field_add" class="btn btn-round btn-info">+</button>
                            </div>
                            <div id="menu-group-add">
                            </div>
                            <div class="ln_solid"></div>
        
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button class="btn btn-primary cancelUserBtn" type="button">Cancel</button>
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

<div id="userEditModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Edit Group</h2>
        </div>
        <div class="modal-body">
            <div class="">
                <div class="x_panel">
                    <div class="x_content">
                        <form class="add-form form-horizontal form-label-left" method="POST" action="/edit_user">
                            {{ csrf_field() }}
                            <div class="form-group-list">
                                <input type="hidden" id="editUserId" name="userId" class="form-control" required="required">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3">User Name</label>
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                        <input type="text" id="editUserName" name="name" class="form-control" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Password</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="text" id="editUserPwd" name="pwd" class="form-control" required="required">
                                        </div>
                                    </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3">Group List</label>
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                        <select name="groupId" class="form-control" id="editGroupId" required="required">
                                            {!!$group_list_html!!}
                                        </select>
                                    </div>
                                </div>
    
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3">Login Allow</label>
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                        <input type="checkbox" class="js-switch" name="isAllow" checked/>
                                    </div>
                                </div>
                                <div class="form-group"  style="text-align:  center;">
                                    <button type="button" id="add_field_edit" class="btn btn-round btn-info">+</button>
                                </div>
                                <div id="menu-group-edit">
                                </div>
                            </div>
                            <div class="ln_solid"></div>
        
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button class="btn btn-primary cancelUserBtn" type="button">Cancel</button>
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

<div id="importUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Import User</h2>
        </div>
        <div class="modal-body">
            <div class="">
                <div class="x_panel">
                    <div class="x_content">
                        <form class="add-form form-horizontal form-label-left" method="POST" action="/user_import" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="file" class="form-control-file" name="fileToUpload" id="exampleInputFile" aria-describedby="fileHelp" required="required">
                                <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                            </div>
                            <button class="btn btn-primary cancelGroupBtn">Cancel</button>
                            <button type="submit" class="btn btn-primary">Import User</button>
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
<script src="assets/js/switchery/switchery.min.js"></script>
<link rel="stylesheet" href="assets/css/switchery/switchery.min.css" />
<script>
    //add modal element
    var userAddModal = document.getElementById('userAddModal');
    var userEditModal = document.getElementById('userEditModal');
    var notificationModal = document.getElementById('notificationModal');
    var importUserModal = document.getElementById('importUserModal');

    $(document).ready(function(){
        $('#groupTable').dataTable();
        if ($(".js-switch")[0]) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html, {
                    color: '#26B99A'
                },$(html).data());
            });
        }
        $('#add_field_add').on('click',function(){
            $('#menu-group-add').append("<div class='menu'><div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='first-name'>Menu Name<span class='required'>*</span></label><div class='col-md-6 col-sm-6 col-xs-12'><input type='text' id='group_name' name='menu_name[]' required='required' class='form-control col-md-7 col-xs-12' data-parsley-id='8566'><ul class='parsley-errors-list' id='parsley-id-8566'></ul></div></div>"+
            "<div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='first-name'>Link<span class='required'>*</span></label><div class='col-md-6 col-sm-6 col-xs-12'><input type='url' placeholder='https://example.com' id='group_name' name='menu_link[]' required='required' class='form-control col-md-7 col-xs-12' data-parsley-id='8566'><ul class='parsley-errors-list' id='parsley-id-8566'></ul></div><button type='button' class='remove_field_edit btn btn-round btn-info'>-</button></div></div>");
            $('.remove_field_edit').on('click',function(){
                $(this).parent().parent().remove();
            });
         });
         $('#add_field_edit').on('click',function(){
            $('#menu-group-edit').append("<div class='menu'><div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='first-name'>Menu Name<span class='required'>*</span></label><div class='col-md-6 col-sm-6 col-xs-12'><input type='text' id='group_name' name='menu_name[]' required='required' class='form-control col-md-7 col-xs-12' data-parsley-id='8566'><ul class='parsley-errors-list' id='parsley-id-8566'></ul></div></div>"+
            "<div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='first-name'>Link<span class='required'>*</span></label><div class='col-md-6 col-sm-6 col-xs-12'><input type='url' placeholder='https://example.com' id='group_name' name='menu_link[]' required='required' class='form-control col-md-7 col-xs-12' data-parsley-id='8566'><ul class='parsley-errors-list' id='parsley-id-8566'></ul></div><button type='button' class='remove_field_edit btn btn-round btn-info'>-</button></div></div>");
            $('.remove_field_edit').on('click',function(){
                $(this).parent().parent().remove();
            });
         });
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
    $('#btnAddUser').on('click',function(){
        userAddModal.style.display = "block";
        $('#menu-group-add').html("");
    });
    $('.close').on('click',function(){
        userAddModal.style.display = "none";
        userEditModal.style.display = "none";
        notificationModal.style.display = "none";
    });
    $('.cancelUserBtn').on('click',function(){
        userAddModal.style.display = "none";
        userEditModal.style.display = "none";
    });
    $('.editBtn').on('click',function(){
        userEditModal.style.display = "block";
        id = this.parentElement.parentElement.children[0].textContent;
        userName = this.parentElement.parentElement.children[1].textContent;
        userPwd = this.parentElement.parentElement.children[2].textContent;
        userGroupId = $(this.parentElement.parentElement.children[3]).attr('data');
        $('#editUserId').val(id);
        $('#editUserName').val(userName);
        $('#editUserPwd').val(userPwd);
        $('#editGroupId').val(userGroupId);
        $('#menu-group-edit').html("");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/get_menu_list',
            type: 'POST',
            data: {_token: CSRF_TOKEN,user_id:id},
            dataType: 'JSON',
            success: function(data){
                var menuLst = data.menu;
                for(var i = 0;i < menuLst.length;i++)
                {
                    var menu = menuLst[i];
                    $('#menu-group-edit').append("<div class='menu'><div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='first-name'>Menu Name<span class='required'>*</span></label><div class='col-md-6 col-sm-6 col-xs-12'><input type='text' id='group_name' name='menu_name[]'  required='required' value='" + menu.menu_text +"' class='form-control col-md-7 col-xs-12' data-parsley-id='8566'><ul class='parsley-errors-list' id='parsley-id-8566'></ul></div></div>"+
                    "<div class='form-group'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='first-name'>Link<span class='required'>*</span></label><div class='col-md-6 col-sm-6 col-xs-12'><input type='url' placeholder='https://example.com' id='group_name' name='menu_link[]' required='required' value='" + menu.url + "' class='form-control col-md-7 col-xs-12' data-parsley-id='8566'><ul class='parsley-errors-list' id='parsley-id-8566'></ul></div><button type='button' class='remove_field_edit btn btn-round btn-info'>-</button></div></div>");
                    $('.remove_field_edit').on('click',function(){
                        $(this).parent().parent().remove();
                    });
                }
            }
        });
    });
    $('#btnImportUser').on('click',function(){
        importUserModal.style.display = "block";
    });
</script>
@endsection
@extends('admin.layouts.left')
@include('UEditor::head');
@section('content')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Notification Management</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Title <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="title" name="title" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="container" name="content"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3">Group List</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select name="groupId" class="form-control" id="groupId">
                                    {!!$group_list_html!!}
                                </select>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
    
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="sentNotificationBtn" type="button" class="btn btn-success">Send</button>
                            </div>
                        </div>
                    </form>      
                </div>
            </div>
        </div>
    </div>
</div>
<div id="notificationModal" class="modal" style="display:none;z-index:999;">
    <div style="width: 50%;text-align: center;position: absolute;left: 25%;">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Notification</h2>
        </div>
        <div class="modal-body" style="background: white;">
            <h3 id="notificationTxt"></h3>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/js/moment/moment.min.js"></script>
<script id="container" name="content" type="text/plain">
</script>
<script type="text/javascript">
    var notificationBlock = document.getElementById('notificationModal');
    var ue = UE.getEditor('container');
        ue.ready(function() {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.    
    });
    $('.close').on('click',function(){
        notificationBlock.style.display = "none";
    });
    $('#sentNotificationBtn').on('click',function(){
        var nofificationContent = ue.getContent();
        var groupId = $('#groupId').val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var title = $('#title').val();
        $.ajax({
            url:'/send_notification',
            type:'POST',
            data: {_token:CSRF_TOKEN,notification: nofificationContent,groupId:groupId,title:title},
            dataType:'JSON',
            success: function(data)
            {
                ue.setContent('');
                if(data.result == "success")
                {
                    $('#notificationTxt').html('A notification is sent.');
                    notificationBlock.style.display = 'block';
                }
                else
                {
                    $('#notificationTxt').html('To send a notification is failed');
                    notificationBlock.style.display = 'block';
                }
            },
            fail: function(data)
            {
                ue.setContent('');
                $('#notificationTxt').html('To send a notification is failed');
                notificationBlock.style.display = 'block';
            }
        });
    });
</script>
@endsection
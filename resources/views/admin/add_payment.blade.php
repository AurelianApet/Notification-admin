@extends('admin.layouts.left')
@section('content')
<div class="right_col" role="main">
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div class="x_title">
            <h2>Add your payment</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
            <form id="ad_payment" action="/add_payment" method="POST" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">User <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="select2_multiple form-control" name = "user_id" required="required" multiple="multiple">
                        @foreach($user_list as $user)  
                            <option value={{$user[0]}}>{{$user[1]}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="media_balance">Emby Service Balance($) <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="number" id="media_balance" name="media_balance"  data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Emby Service Expired Date <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <fieldset>
                            <div class="control-group">
                            <div class="controls">
                                <div class="xdisplay_inputx form-group has-feedback">
                                    <input type="text" name="media_expired" class="form-control col-md-7 col-xs-12 has-feedback-left active " id="single_cal1" placeholder="Date" aria-describedby="inputSuccess2Status">
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                            </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="output_balance">Media Attached File <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="file" class="form-control-file" name="media_attache_file" aria-describedby="fileHelp" >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="output_balance">Output Service Balance($) <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="output_balance" name="output_balance" data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Output Service Expired Date <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <fieldset>
                            <div class="control-group">
                            <div class="controls">
                                <div class="xdisplay_inputx form-group has-feedback">
                                    <input type="text" name="output_expired" class="form-control col-md-7 col-xs-12 has-feedback-left active " id="single_cal2" placeholder="Date" aria-describedby="inputSuccess2Status">
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                            </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="output_balance">Output Attached File <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="file" class="form-control-file" name="output_attache_file" aria-describedby="fileHelp" >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="output_balance">User Name of output <span class="required">*</span>
                    </label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_output_name" name="output_name" aria-describedby="fileHelp" >
                            <input type="hidden" id="txt_output_name_" name="output_name_" aria-describedby="fileHelp" >
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <a class="btn btn-success" id="btn_check_output_name">Check name of output service</a>
                        </div>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success" id="btn_add_payment" style="display:none;">Add Payment</button>
                    </div>
                </div>

            </form>
        </div>
        </div>
    </div>
    </div>
</div>
<script type="text/javascript" src="assets/js/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/datepicker/daterangepicker.js"></script>
<script>
    $(document).ready(function(){
        $(".select2_multiple").select2({
            maximumSelectionLength: 1,
            placeholder: "Please choose one person",
            allowClear: true
        });
        $("#btn_check_output_name").on('click',function(data){
            var check_name = $('#txt_output_name').val();
            if(check_name == null || check_name == "")
            {
                alert("Please input name of output service.");
                return;
            }                
            $.ajax({
                url:"check_output_name",
                type:"post",
                data:{name:check_name,'_token':$('meta[name=csrf-token]').attr('content')},
                success: function(data)
                {
                    if(data.exist > 0 )
                    {
                        $("#btn_add_payment").show();
                        $("#txt_output_name").prop("disabled",true);
                        $("#txt_output_name_").val($("#txt_output_name").val());
                    }else
                    {
                        alert("Please input correct name of output service.");
                    }
                }
                
            });
        });
        try
        {
            $('#single_cal1').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_1",
                minYear: 2019,
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        }catch(err)
        {
        }

        try
        {
            $('#single_cal2').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_1",
                minYear: 2019,
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        }catch(err)
        {
        }
        });
</script>
@endsection

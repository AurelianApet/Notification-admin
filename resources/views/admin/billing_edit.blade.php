@extends('admin.layouts.left')
@section('content')
<div class="right_col" role="main">
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div class="x_title">
            <h2>Edit Bill Informatijon</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
            <form id="ad_payment" action="/user/bill/edit/{!!$user_id!!}" method="POST" data-parsley-validate class="form-horizontal form-label-left">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">User <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="{!!$user_name!!}" name="user_name" disabled="disabled" required="required" data-validate-length-range="8,20" value="{!!$user_name!!}" class="form-control col-md-7 col-xs-12">
                            <input type="hidden" name="user_id"  value="{!!$user_id!!}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="media_balance">Emby Service Balance($) <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="number" id="media_balance" name="media_balance" required="required" data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12" value="{!!$media_balance!!}">
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
                                    <input type="text" name="media_expired" class="form-control col-md-7 col-xs-12 has-feedback-left active " id="single_cal1" required="required" value="{!!$media_expired!!}" placeholder="Date" aria-describedby="inputSuccess2Status">
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                            </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Emby Service Activate <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="switch"><input type="checkbox" name="media_service_activate" class="js-switch" {!!$media_service_activate!!}/> 
                        <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="output_balance">Output Service Balance($) <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="number" id="output_balance" name="output_balance" required="required" data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12" value="{!!$output_balance!!}">
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
                                    <input type="text" name="output_expired" class="form-control col-md-7 col-xs-12 has-feedback-left active " id="single_cal2" required="required" value="{!!$output_expired!!}" placeholder="Date" aria-describedby="inputSuccess2Status">
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                    <span id="inputSuccess2Status" class="sr-only">(success)</span>
                                </div>
                            </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Output Service Activate <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <label class="switch"><input type="checkbox" name="output_service_activate" class="js-switch" {!!$output_service_activate!!}/>  <span class="slider round"></span> </label>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Edit</button>
                    </div>
                </div>

            </form>
        </div>
        </div>
    </div>
    </div>
</div>
<style>
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }
    
    .switch input { 
      opacity: 0;
      width: 0;
      height: 0;
    }
    
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }
    
    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }
    
    input:checked + .slider {
      background-color: #26B99A;
    }
    
    input:focus + .slider {
      box-shadow: 0 0 1px #26B99A;
    }
    
    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }
    
    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }
    
    .slider.round:before {
      border-radius: 50%;
    }
    </style>
    
<script type="text/javascript" src="assets/js/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/datepicker/daterangepicker.js"></script>
<script src="assets/js/switchery/switchery.min.js"></script>
<link rel="stylesheet" href="assets/css/switchery/switchery.min.css" />
<script>
    $(document).ready(function(){
        $(".select2_multiple").select2({
            maximumSelectionLength: 2,
            placeholder: "With Max Selection limit 2",
            allowClear: true
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
        // $(function(){
        // if ($(".js-switch")[0]) {
        //     var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        //     elems.forEach(function (html) {
        //         var switchery = new Switchery(html, {
        //             color: '#26B99A'
        //         });
        //     });
        //     }
        // })
</script>
@endsection

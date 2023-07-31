@extends('admin.layouts.left')
@section('content')
<div class="right_col" role="main">
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div id="myModal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="img01">
            <div id="caption"></div>
        </div>
        <div class="x_title">
            <h2>Billing Filter</h2>
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
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Start Date <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <fieldset>
                          <div class="control-group">
                          <div class="controls">
                              <div class="xdisplay_inputx form-group has-feedback">
                                  <input type="text" name="start_date" class="form-control col-md-7 col-xs-12 has-feedback-left active " id="start_date" placeholder="Date" aria-describedby="inputSuccess2Status">
                                  <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                  <span id="inputSuccess2Status" class="sr-only">(success)</span>
                              </div>
                          </div>
                          </div>
                      </fieldset>
                  </div>
              </div>
              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">End Date <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <fieldset>
                          <div class="control-group">
                          <div class="controls">
                              <div class="xdisplay_inputx form-group has-feedback">
                                  <input type="text" name="end_date" class="form-control col-md-7 col-xs-12 has-feedback-left active " id="end_date" placeholder="Date" aria-describedby="inputSuccess2Status">
                                  <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                  <span id="inputSuccess2Status" class="sr-only">(success)</span>
                              </div>
                          </div>
                          </div>
                      </fieldset>
                  </div>
              </div>
              <div class="form-group">
                <div class="col-md-3 col-sm-3 col-xs-12">
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="checkbox" class="flat" name="chk_emby" id="chk_emby" checked="checked"> Emby Service
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-3 col-sm-3 col-xs-12">
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="checkbox" class="flat" name="chk_output" id="chk_output" checked="checked"> Output Service
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                      <a id="btn-filter" class="btn btn-success">Filter</a>
                  </div>
              </div>
          </form>
          <form  class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Emby Value <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="total_media" name="total_media" disabled="disabled" required="required" data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Output Value <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" id="total_output" name="total_output" disabled="disabled" required="required" data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>
            </div>
          </form>
          <table id="group" class="table table-striped table-bordered dt-responsive nowrap dataTable" cellspacing="0" width="100%">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>User Name</th>
                      <th>Description</th>
                      <th>Media Attached File</th>
                      <th>Output Attached File</th>
                      <th>Create Date</th>
                  </tr>
              </thead>
              <tbody id="search_content">
                  
              </tbody>
          </table>
          <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-3">
            <a id="btn_export" class="btn btn-success">Export To Excel File</a>
          </div>
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
<link href="assets/css/icheck/flat/green.css" rel="stylesheet">
<script type="text/javascript" src="assets/js/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/datepicker/daterangepicker.js"></script>
<link href="assets/css/select/select2.min.css" rel="stylesheet">
<script src="assets/js/select/select2.full.js"></script>
<script src="assets/js/icheck/icheck.min.js"></script>
<script>

function prepCSVRow(arr, columnCount, initial) {
  var row = ''; // this will hold data
  var delimeter = ','; // data slice separator, in excel it's `;`, in usual CSv it's `,`
  var newLine = '\r\n'; // newline separator for CSV row

  /*
   * Convert [1,2,3,4] into [[1,2], [3,4]] while count is 2
   * @param _arr {Array} - the actual array to split
   * @param _count {Number} - the amount to split
   * return {Array} - splitted array
   */
  function splitArray(_arr, _count) {
    var splitted = [];
    var result = [];
    _arr.forEach(function(item, idx) {
      if ((idx + 1) % _count === 0) {
        splitted.push(item);
        result.push(splitted);
        splitted = [];
      } else {
        splitted.push(item);
      }
    });
    return result;
  }
  var plainArr = splitArray(arr, columnCount);
  // don't know how to explain this
  // you just have to like follow the code
  // and you understand, it's pretty simple
  // it converts `['a', 'b', 'c']` to `a,b,c` string
  plainArr.forEach(function(arrItem) {
    arrItem.forEach(function(item, idx) {
      row += item + ((idx + 1) === arrItem.length ? '' : delimeter);
    });
    row += newLine;
  });
  return initial + row;
}

  $(document).ready(function(){
      $("#group").dataTable();
      $(".select2_multiple").select2({
        maximumSelectionLength: 1,
        placeholder: "Please choose one person",
        allowClear: true
      });

      $("#btn_export").click(function()
      {
        var titles = [];
        var data = [];

        /*
        * Get the table headers, this will be CSV headers
        * The count of headers will be CSV string separator
        */
        $('.dataTable th').each(function() {
          titles.push($(this).text());
        });

        /*
        * Get the actual data, this will contain all the data, in 1 array
        */
        $('.dataTable td').each(function() {
          data.push($(this).text());
        });
        
        /*
        * Convert our data to CSV string
        */
        var CSVString = prepCSVRow(titles, titles.length, '');
        CSVString = prepCSVRow(data, titles.length, CSVString);
        CSVString = CSVString + "Total,,," + $("#total_media").val() + "," + $("#total_output").val();

        /*
        * Make CSV downloadable
        */
        var downloadLink = document.createElement("a");
        var blob = new Blob(["\ufeff", CSVString]);
        var url = URL.createObjectURL(blob);
        downloadLink.href = url;
        downloadLink.download = "result.csv";

        /*
        * Actually download CSV
        */
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
      });

      if ($("input.flat")[0]) {
          $(document).ready(function () {
              $('input.flat').iCheck({
                  checkboxClass: 'icheckbox_flat-green',
                  radioClass: 'iradio_flat-green'
              });
          });
      }
      $("#btn_export").hide();
      $("#btn-filter").click(function()
      {
        var user_id = $(".select2_multiple").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var chk_emby = $("#chk_emby").prop("checked");
        var chk_output = $("#chk_output").prop("checked");
        // if(user_id == null || start_date ==  null || end_date == null)
        // {
        //   alert("Please input all data.");
        //   return;
        // }
        $.post("search",{'_token' : $('meta[name=csrf-token]').attr('content'),user_id: user_id,start_date: start_date,end_date:end_date,chk_emby:chk_emby,chk_output:chk_output},function(data){
        $search_content = $("#search_content").html(data.search_result);
        $("#total_media").val(0);
        $("#total_output").val(0);
        $("#btn_export").hide();
        if(chk_emby)
        {
          $("#total_media").val(data.total_media);
          if(data.total_media != 0)
            $("#btn_export").show();
        }
        if(chk_output)
        {
          $("#total_output").val(data.total_output);
          if(data.total_output != 0)
            $("#btn_export").show();
        }
        var modal = document.getElementById("myModal");
        
        var img = document.getElementById("myImg");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        
        $(".myImg").click(function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;  
        });
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() { 
          modal.style.display = "none";
        }
        });

      });

      try
      {
          $('#start_date').daterangepicker({
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
          $('#end_date').daterangepicker({
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
<style>
body {font-family: Arial, Helvetica, sans-serif;}

#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>
@endsection

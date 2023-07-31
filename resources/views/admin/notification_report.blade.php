@extends('admin.layouts.left')
@section('content')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Send and Acknowledge Report</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="reportTable" class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            {!!$report_list_html!!}
                        </tbody>
                    </table>
                    <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-3">
                        <form class="add-form form-horizontal form-label-left" method="POST" action="/clear_log" enctype="multipart/form-data" style="display: inline-table;">
                            {{ csrf_field() }}
                            <button type="submit" id="btnClearBtn" class="btn btn-success">Clear Report</button>
                        </form>
                        <a id="btn_export" class="btn btn-success">Export To Excel File</a>
                    </div>
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
    //export csv file for report
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
//
    var notificationModal = document.getElementById('notificationModal');
    $(document).ready(function(){
        $('#reportTable').dataTable();
        $('.close').on('click',function(){
            notificationModal.style.display = "none";
            });
            $("#btn_export").on('click',function()
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
                /*
                * Make CSV downloadable
                */
                var downloadLink = document.createElement("a");
                var blob = new Blob(["\ufeff", CSVString]);
                var url = URL.createObjectURL(blob);
                downloadLink.href = url;
                downloadLink.download = "send_acknowledge.csv";

                /*
                * Actually download CSV
                */
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            });
    });
</script>
@endsection
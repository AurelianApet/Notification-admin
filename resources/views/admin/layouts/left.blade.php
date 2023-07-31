<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <base href="http://127.0.0.1:8000">
  <title>Notification Admin</title>

  <!-- Bootstrap core CSS -->

  <link href="assets/css/bootstrap.min.css" rel="stylesheet">

  <link href="assets/fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="assets/css/custom.css" rel="stylesheet">
  <script src="assets/js/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="assets/js/custom.js"></script>
  
  <link href="assets/css/icheck/flat/green.css" rel="stylesheet" />
  <link href="assets/css/floatexamples.css" rel="stylesheet" type="text/css" />
  <link href="assets/css/select/select2.min.css" rel="stylesheet">

  <script src="assets/js/nprogress.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/select/select2.full.js"></script>

  <link href="css/custom.css" rel="stylesheet">

  <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>


<body class="nav-md">

  <div class="container body">


    <div class="main_container">

      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title col" style="border: 0;height: fit-content;">
            <a href="/" class="site_title" style="height: fit-content;">
              <div class="col">
                <div class="row-sm">
                    <div class="profile_img col-md-12 col-sm-12" style="height: 70px"></div>
                </div>
                <div class="row-sm" style="text-align:  center;">
                    <h3>Welcome Admin</h3>
                </div>
              </div>
            </a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile" style="padding:0px;margin-bottom: 50px">
            <div class="profile_pic">
              
            </div>
            <div class="profile_info" style="padding-top:0px">
              
            </div>
          </div>
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
              <ul class="nav side-menu">
                <li><a href="/user_manage"><i class="fa fa-home"></i>User Management</span></a>
                </li>
                <li><a href="/group_manage"><i class="fa fa-object-group"></i>Group Management</span></a>
                </li>
                <li><a href="/news_manage"><i class="fa fa-newspaper-o"></i>Create News</span></a>
                </li>
                <li><a href="/notification_manage"><i class="fa fa-qq"></i>Create New Notification</span></a>
                </li>
                <li><a href="/notification_history"><i class="fa fa-history"></i>Notification History</span></a>
                </li>
                <li><a href="/news_history"><i class="fa fa-history"></i>News History</span></a>
                </li>
                <li><a><i class="fa fa-paypal"></i>Billing System<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none">
                    <li><a href="/add_payment">Add Payment</a>
                    </li>
                    <li><a href="/billing_table">Billing Table</a>
                    </li>
                    <li><a href="/billing_record_table">History</a>
                    </li>
                    <li><a href="/billing_record_filter">Billing Table Filter</a>
                    </li>
                  </ul>
                </li>
                <li><a><i class="fa fa-file"></i>Reports<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display:none">
                    <li><a href="/online_report">Online Users</a>
                    </li>
                    <li><a href="/offline_report">Offline Users</a>
                    </li>
                    <li><a href="/notification_report">Notification Reports</a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>

          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          <div class="sidebar-footer hidden-small" style="display: none;">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
              <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
              <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
          </div>
        </div>
      </div>
      <div class="top_nav">

        <div class="nav_menu">
          <nav class="" role="navigation">
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="" id="logout">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                  <li><a href="/logout"><i class="fa fa-sign-out pull-right"></i> LOG OUT</a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      @yield('content')
    </div>
    <script>
      $(document).ready(function(){
        $("#logout").click(function(){
          if($("#logout").attr("class") == "open")
            $("#logout").removeClass("open");
          else
            $("#logout").addClass("open");
        });
      });
  </script>
  </body>
</html>
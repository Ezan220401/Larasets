<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        label {
          border-bottom: 0.5px solid black;
          width: 80%;
        }
        #sign {
          border-bottom: 0.5px solid black;
          width: 100%;
        }
        .approval-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 10px;
            text-align: center;
            padding-top: 20px;
        }
        .approval-item {
            padding: 20px;
            text-align: center;
            font-size: 15pt;
            margin: 0;
        }
        .container {
            width: 100%;
            min-height: 297mm; /* Tinggi A4 */
            padding-bottom: 0;
            margin: 0;
            border: none;
            box-shadow: none;
            page-break-after: always;
        }
          tr th:nth-child(1),
          tr td:nth-child(1),
          tr th:nth-child(3),
          tr td:nth-child(3) {
              display: block;
          }
        @media (max-width: 800px) {
          .approval-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
          }
          #detskop_div{
            display: none;
          }
            tr th:nth-child(3),
            tr td:nth-child(3) {
                display: none;
            }
          #mobile_span{
            display:none;
          }
        }
        @media (max-width: 450px){
          .approval-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            grid-template-rows: repeat(1, 1fr);
          }
          #detskop_div{
            display: none;
          }
          tr th:nth-child(2),
          tr td:nth-child(2),
          tr th:nth-child(3),
          tr td:nth-child(3) {
              display: none;
          }
          #mobile_span{
              display:block;
          }
        }
          @media print {
            header {
              width: 95%;
              font-size: 20pt;
            }
            p {
                font-size: 15pt;
            }
            #button, #note {
                display: none;
            }
            main {
                width: 95%;
                min-height: 297mm; /* Tinggi A4 */
                margin: 0;
                border: none;
                box-shadow: none;
                font-size: 14pt;
            }
            #detskop_div{
              display: block;
            }
            .approval-grid {
              display: grid;
              grid-template-columns: repeat(3, 1fr);
              grid-template-rows: repeat(3, 1fr);
            }
            .approval_item {
                padding: 20px;
                text-align: center;
                font-size: 15pt;
                margin: 0;
                border: none;
            }
            main {
                font-size: 15pt;
            }
            #approval {
                margin-top: 100px;
            }
            tr th:nth-child(1),
            tr td:nth-child(1),
            tr th:nth-child(3),
            tr td:nth-child(3) {
                display: block;
            }
            
        }
    </style>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('AdminLteTemplate/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('AdminLteTemplate/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('AdminLteTemplate/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('AdminLteTemplate/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLteTemplate/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('AdminLteTemplate/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('AdminLteTemplate/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('AdminLteTemplate/plugins/summernote/summernote-bs4.min.css') }}">
  
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap-toggle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <!-- Sweat Alert -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


      @include('layouts.navbar')

      @include('layouts.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper p-3">
        <!-- Content Header (Page header) -->
        <div class="content-header card col-md-11 mx-auto border-secondary p-4">
          <h1 class="m-0">@yield('title')<button id="export-pdf" style="width:100px" class="btn btn-danger float-right">Unduh PDF <i class="fa-solid fa-download"></i></button></h1> 
        </div>
        
        <div class="container card col-md-11 mx-auto p-4">
          <p id="note" class="text-warning bg-secondary p-2 rounded text-center">Harap print atau unduh dokumen sebelum memakai</p>
          @yield('content')
        </div>
      </div>
      <!-- /.content-wrapper -->
      
      <!-- @include('layouts.footer') -->

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset('AdminLteTemplate/plugins/jquery/jquery.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('AdminLteTemplate/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('AdminLteTemplate/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('AdminLteTemplate/plugins/chart.js/Chart.min.js') }}"></script>
  <!-- Sparkline -->
  <script src="{{ asset('AdminLteTemplate/plugins/sparklines/sparkline.js') }}"></script>
  <!-- JQVMap -->
  <script src="{{ asset('AdminLteTemplate/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('AdminLteTemplate/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ asset('AdminLteTemplate/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="{{ asset('AdminLteTemplate/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('AdminLteTemplate/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('AdminLteTemplate/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <!-- Summernote -->
  <script src="{{ asset('AdminLteTemplate/plugins/summernote/summernote-bs4.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('AdminLteTemplate/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- AdminLteTemplate App -->
  <script src="{{ asset('AdminLteTemplate/dist/js/adminlte.min.js') }}"></script>
  <!-- AdminLteTemplate dashboard demo (This is only for demo purposes) -->
  <script src="{{ asset('AdminLteTemplate/dist/js/pages/dashboard.js') }}"></script>
  <!-- Sweet Alert --> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      document.getElementById('export-pdf').addEventListener('click', function() {
        window.print();
      });
    </script>
  </body>
</html>
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ATK</title>
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('templates/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
  <link href="{{ asset('plugins/DataTables/datatables.min.css') }}" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('plugins/iziModal/css/iziModal.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.5.6/lib/themes/classic.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.5.6/lib/themes/classic.date.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.5.6/lib/themes/classic.time.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/adminlte/dist/css/AdminLTE.min.css') }}">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('templates/adminlte/dist/css/skins/_all-skins.min.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
.html5buttons {
  float: right;
  margin-left: 5px;
}
</style>
@yield('custom-css')

<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <a href="{{ asset('templates/adminlte/index2.html') }}" class="logo">
        <span class="logo-mini"><b>A</b>TK</span>
        <span class="logo-lg"><b>ATK</b></span>
      </a>
      <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('templates/adminlte/dist/img/avatar5.png') }}" class="user-image" alt="User Image">
                <span class="hidden-xs">{{  Auth::user()->name  }}</span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header">
                  <img src="{{ asset('templates/adminlte/dist/img/avatar5.png') }}" class="img-circle" alt="User Image">
                  <p>{{  Auth::user()->name  }}
                    <p>
                      <small>{{   Auth::user()->subbidang['nama'] ?? '-' }}</small>
                    </p>
                  </li>

                  <li class="user-footer">
                    {{-- <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div> --}}
                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </li>
              </ul>
            </li>

          </ul>
        </div>
      </nav>
    </header>
    <aside class="main-sidebar">
      <section class="sidebar">
        <div class="user-panel">
          <div class="pull-left image">
            <img src="{{ asset('templates/adminlte/dist/img/avatar5.png') }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{  ucwords(Auth::user()->name)  }}
            </p>
            <a href="#"><i class="fa fa-circle text-success"></i> 
              {{  ucwords(implode(Auth::user()->getRoleNames()->toArray()))  }}
            </a>
          </div>
        </div>
        @include('layouts.sidebar_backend')
      </section>
    </aside>
    <div class="content-wrapper">
      <section class="content-header">
        @yield('content-header')
      </section>
      <section class="content">
        @yield('content')
      </section>
    </div>
    <footer class="main-footer">{{-- 
      <div class="pull-right hidden-xs">
        <b>Version</b> 2.4.0
      </div>
      <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
      reserved.
    --}}</footer>
    <script src="{{ asset('templates/adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('templates/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('templates/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
    <script type="text/javascript" src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>



    <script src="{{ asset('templates/adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('plugins/axios/dist/axios.min.js') }}"></script>
    <script src="{{ asset('plugins/iziModal/js/iziModal.min.js') }}"></script>

    <script src="{{ asset('templates/adminlte/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('plugins/pickadate.js-3.5.6/lib/picker.js') }}"></script>
    <script src="{{ asset('plugins/pickadate.js-3.5.6/lib/picker.date.js') }}"></script>
    <script src="{{ asset('plugins/pickadate.js-3.5.6/lib/picker.time.js') }}"></script>
    <script src="{{ asset('plugins/pickadate.js-3.5.6/lib/legacy.js') }}"></script>
    <script src="{{ asset('templates/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/convert_rupiah.js') }}"></script>


    @yield('custom-js')
  </body>
  </html>

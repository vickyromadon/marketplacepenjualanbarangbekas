<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="viewport" content="initial-scale=1.0">
  
  <title>Admin Marketplace Limbah</title>
  
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  @yield('css')
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="{{ route('admin.home') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>MPL</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Marketplace</b> Limbah</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{ asset('images/avatar_admin.png') }}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{ asset('images/avatar_admin.png') }}" class="img-circle" alt="User Image">

                <p>
                  {{ Auth::user()->name }} - Super Admin
                  <small>Admin since {{ date_format(Auth::user()->created_at, 'M. Y') }}</small>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('admin.profile.index') }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Sign out
                  </a>

                  <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('images/avatar_admin.png') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU NAVIGATION</li>

        {{-- Dashboard --}}
        <li class="{{(Request::segment(2) == 'admin' || Request::segment(2) == '') ? "active" : ""}}">
          <a href="{{ route('admin.home') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        
        {{-- Category --}}
        <li class="{{(Request::segment(2) == 'category') ? "active" : ""}}">
          <a href="{{ route('admin.category.index') }}">
            <i class="fa fa-th-list"></i> <span>Category</span>
          </a>
        </li>

        {{-- Setting --}}
        <li class="{{(Request::segment(2) == 'setting') ? "active" : ""}}">
          <a href="{{ route('admin.setting.index') }}">
            <i class="fa fa-gears"></i> <span>Pengaturan Harga</span>
          </a>
        </li>

        {{-- Pengelola Barang Bekas --}}
        <li class="treeview {{(Request::segment(2) == 'buy') ? "active" : ""}}">
          <a href="#">
            <i class="fa fa-cubes"></i>
            <span>Pengelolaan Barang Bekas</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::segment(2) == 'buy') ? "active" : ""}}">
              <a href="{{ route('admin.buy.index') }}"><i class="fa fa-circle-o"></i>Pembelian Barang Bekas</a>
            </li>
          </ul>
        </li>
        
        {{-- Bank --}}
        <li class="{{(Request::segment(2) == 'bank') ? "active" : ""}}">
          <a href="{{ route('admin.bank.index') }}">
            <i class="fa fa-bank"></i> <span>Bank</span>
          </a>
        </li>

        {{-- Produk --}}
        <li class="{{(Request::segment(2) == 'product') ? "active" : ""}}">
          <a href="{{ route('admin.product.index') }}">
            <i class="fa fa-cubes"></i> <span>Pengelolaan Produk</span>
          </a>
        </li>
        
        {{-- Pengelola Pengguna --}}
        <li class="treeview {{(Request::segment(2) == 'member') || (Request::segment(2) == 'owner') ? "active" : ""}}">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Pengelolaan Pengguna</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::segment(2) == 'member') ? "active" : ""}}">
              <a href="{{ route('admin.member.index') }}"><i class="fa fa-circle-o"></i>Member</a>
            </li>
            <li class="{{(Request::segment(2) == 'owner') ? "active" : ""}}">
              <a href="{{ route('admin.owner.index') }}"><i class="fa fa-circle-o"></i>Owner</a>
            </li>
          </ul>
        </li>
        
        {{-- Pengelola Penjualan --}}
        <li class="treeview {{(Request::segment(2) == 'transaction_sell') || (Request::segment(2) == 'refund_sell') ? "active" : ""}}">
          <a href="#">
            <i class="fa fa-cube"></i>
            <span>Pengelolaan Penjualan</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::segment(2) == 'transaction_sell') ? "active" : ""}}">
              <a href="{{ route('admin.transaction_sell.index') }}"><i class="fa fa-circle-o"></i>Transaksi</a>
            </li>
            <li class="{{(Request::segment(2) == 'refund_sell') ? "active" : ""}}">
              <a href="{{ route('admin.refund_sell.index') }}"><i class="fa fa-circle-o"></i>Pengembalian Dana</a>
            </li>
          </ul>
        </li>

        {{-- Pengelola Permintaan --}}
        <li class="treeview {{(Request::segment(2) == 'transaction_request') || (Request::segment(2) == 'refund_request') ? "active" : ""}}">
          <a href="#">
            <i class="fa fa-cube"></i>
            <span>Pengelolaan Permintaan</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::segment(2) == 'transaction_request') ? "active" : ""}}">
              <a href="{{ route('admin.transaction_request.index') }}"><i class="fa fa-circle-o"></i>Transaksi</a>
            </li>
            <li class="{{(Request::segment(2) == 'refund_request') ? "active" : ""}}">
              <a href="{{ route('admin.refund_request.index') }}"><i class="fa fa-circle-o"></i>Pengembalian Dana</a>
            </li>
          </ul>
        </li>

        {{-- Artikel --}}
        <li class="{{(Request::segment(2) == 'article') ? "active" : ""}}">
          <a href="{{ route('admin.article.index') }}">
            <i class="fa fa-th-list"></i> <span>Tambah Artikel</span>
          </a>
        </li>

        {{-- Pengelola Pelelangan --}}
        {{-- <li class="treeview {{(Request::segment(2) == 'transaction_auction') || (Request::segment(2) == 'refund_auction') ? "active" : ""}}">
          <a href="#">
            <i class="fa fa-cube"></i>
            <span>Pengelolaan Pelelangan</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::segment(2) == 'transaction_auction') ? "active" : ""}}">
              <a href="{{ route('admin.transaction_auction.index') }}"><i class="fa fa-circle-o"></i>Transaksi</a>
            </li>
            <li class="{{(Request::segment(2) == 'refund_auction') ? "active" : ""}}">
              <a href="{{ route('admin.refund_auction.index') }}"><i class="fa fa-circle-o"></i>Pengembalian Dana</a>
            </li>
          </ul>
        </li> --}}
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      @yield('header')
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2016 <a href="#">Company</a>.</strong> All rights reserved.
  </footer>
  <div class="control-sidebar-bg"></div>
</div>
  <script src="{{ asset('/js/jquery.min.js') }}"></script>
  <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('/js/jquery.slimscroll.min.js') }}"></script>
  <script src="{{ asset('/js/fastclick.js') }}"></script>
  <script src="{{ asset('/js/icheck.min.js') }}"></script>
  <script src="{{ asset('/js/adminlte.min.js') }}"></script>
  <script src="{{ asset('/js/jquery.toast.min.js') }}"></script>
  <script src="{{ asset('/js/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('/js/dataTables.bootstrap.js') }}"></script>
  <script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('/js/moment.js') }}"></script>
  <script src="{{ asset('/js/app.js') }}"></script>
  @yield('js')
</body>
</html>
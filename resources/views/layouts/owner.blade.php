<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="viewport" content="initial-scale=1.0">
  
  <title>Owner Marketplace Limbah</title>
  
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  @yield('css')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="{{ route('owner.home') }}" class="logo">
      <span class="logo-mini"><b>MPL</b></span>
      <span class="logo-lg"><b>Marketplace</b> Limbah</span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ Auth::user()->file != null ? asset('storage/'. Auth::user()->file->path) : asset('images/avatar_owner.png') }}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="{{ Auth::user()->file != null ? asset('storage/'. Auth::user()->file->path) : asset('images/avatar_owner.png') }}" class="img-circle" alt="User Image">

                <p>
                  {{ Auth::user()->name }} - Owner
                  <small>Owner since {{ date_format(Auth::user()->created_at, 'M. Y') }}</small>
                </p>
              </li>

              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('owner.profile.index') }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('owner.logout') }}" class="btn btn-default btn-flat"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Sign out
                  </a>

                  <form id="logout-form" action="{{ route('owner.logout') }}" method="POST" style="display: none;">
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

  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ Auth::user()->file != null ? asset('storage/'. Auth::user()->file->path) : asset('images/avatar_owner.png') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU NAVIGATION</li>
        
        {{-- Dashboard --}}
        <li class="{{(Request::segment(2) == 'owner' || Request::segment(2) == '') ? "active" : ""}}">
          <a href="{{ route('owner.home') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        {{-- Product --}}
        <li class="{{(Request::segment(2) == 'product') ? "active" : ""}}">
          <a href="{{ route('owner.product.index') }}">
            <i class="fa fa-cubes"></i> <span>Product</span>
          </a>
        </li>

        {{-- Bank --}}
        <li class="{{(Request::segment(2) == 'bank') ? "active" : ""}}">
          <a href="{{ route('owner.bank.index') }}">
            <i class="fa fa-bank"></i> <span>Bank</span>
          </a>
        </li>

        {{-- Forum --}}
        <li class="{{(Request::segment(2) == 'forum') ? "active" : ""}}">
          <a href="{{ route('owner.forum.index') }}">
            <i class="fa fa-rss"></i> <span>Forum Pengrajin</span>
          </a>
        </li>

        {{-- Pengiriman --}}
        <li class="{{(Request::segment(2) == 'delivery') ? "active" : ""}}">
          <a href="{{ route('owner.delivery.index') }}">
            <i class="fa fa-truck"></i> <span>Pengiriman</span>
          </a>
        </li>

        {{-- Permintaan --}}
        <li class="treeview {{(Request::segment(2) == 'request') || (Request::segment(2) == 'on_progress') ? "active" : ""}}">
          <a href="#">
            <i class="fa fa-archive"></i>
            <span>Pengelolaan Permintaan</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class="{{(Request::segment(2) == 'request') ? "active" : ""}}">
              <a href="{{ route('owner.request.index') }}"><i class="fa fa-circle-o"></i>Daftar Permintaan</a>
            </li>
            <li class="{{(Request::segment(2) == 'on_progress') ? "active" : ""}}">
              <a href="{{ route('owner.request.on_progress') }}"><i class="fa fa-circle-o"></i>Sedang Proses</a>
            </li>
          </ul>
        </li>

        {{-- Pengembalian Dana --}}
        <li class="{{(Request::segment(2) == 'refund') ? "active" : ""}}">
          <a href="{{ route('owner.refund.index') }}">
            <i class="fa fa-money"></i> <span>Pengembalian Dana</span>
          </a>
        </li>

        {{-- Pengelolaan Poin --}}
        <li class="{{(Request::segment(2) == 'use_poin') ? "active" : ""}}">
          <a href="{{ route('owner.use_poin.index') }}">
            <i class="fa fa-dot-circle-o"></i> <span>Pengelolaan Poin</span>
          </a>
        </li>

      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      @yield('header')
    </section>

    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        @yield('content')

    </section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      Anything you want
    </div>
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
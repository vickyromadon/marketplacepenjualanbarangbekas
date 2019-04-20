<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="viewport" content="initial-scale=1.0">

  <title>Marketplace Limbah</title>
  
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{ mix('/css/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
  {{-- google font --}}
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  @yield('css')
</head>
<body class="hold-transition skin-red layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="/" class="navbar-brand"><b>Martketplace</b> Limbah</a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="{{(Request::segment(1) == '/' || Request::segment(1) == '') ? "active" : ""}}">
              <a href="/">
                <i class="fa fa-home"></i>
                Home
              </a>
            </li>
            {{-- <li>
              <a href="#">
                <i class="fa fa-hourglass-2"></i>
                Lelang
              </a>
            </li> --}}
            @if( Auth::user() )
              <li class="{{(Request::segment(1) == 'buy') ? "active" : ""}}">
                <a href="{{ route('member.buy.index') }}">
                  <i class="fa fa-cubes"></i>
                  Jual Barang Bekas
                </a>
              </li>
              <li class="{{(Request::segment(1) == 'article') ? "active" : ""}}">
                <a href="{{ route('member.article.index') }}">
                  <i class="fa fa-file"></i>
                  Artikel
                </a>
              </li>
            @endif
            <li class="dropdown {{(Request::segment(2) == 'category') ? "active" : ""}}">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list"></i> Kategori <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                @foreach(\App\Models\Category::all() as $category)
                  <li><a href="{{ route('member.product.index', ['name' => $category->name]) }}">{{ $category->name }}</a></li>
                @endforeach
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Pencarian">
            </div>
          </form>
        </div>
        
        @if( !Auth::user() )
            <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
              <ul class="nav navbar-nav">
                <li><a href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Masuk</a></li>
              </ul>
            </div>
        @else
            <div class="navbar-custom-menu">
              <ul class="nav navbar-nav">
                @if(Auth::user())
                  <li class="dropdown messages-menu {{(Request::segment(1) == 'cart') ? "active" : ""}}">
                    <a href="{{ route('member.cart.index') }}">
                      <i class="fa fa-cart-plus"></i>
                      <span class="label label-warning">{{ \App\Models\Cart::countCart(Auth::user()->id) }}</span>
                    </a>
                  </li>
                @endif

                <li class="dropdown user user-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ Auth::user()->file != null ? asset('storage/'. Auth::user()->file->path) : asset('images/avatar_member.png') }}" class="user-image" alt="User Image">
                    <span class="hidden-xs">{{ Auth::user()->name }}</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="user-header">
                      <img src="{{ Auth::user()->file != null ? asset('storage/'. Auth::user()->file->path) : asset('images/avatar_member.png') }}" class="img-circle" alt="User Image">

                      <p>
                        {{ Auth::user()->name }} - Customer
                         <small>Customer since {{ date_format(Auth::user()->created_at, 'M. Y') }}</small>
                      </p>
                    </li>

                    @if(Auth::user())
                    <li class="user-body">
                      <div class="row">
                        <div class="col-xs-4 text-center">
                          <a href="{{ route('member.transaction.index') }}">Transaksi</a>
                        </div>
                        <div class="col-xs-4 text-center">
                          <a href="{{ route('member.request.index') }}">Permintaan</a>
                        </div>
                        {{-- <div class="col-xs-4 text-center">
                          <a href="#">Lelang</a>
                        </div> --}}
                        <div class="col-xs-4 text-center">
                          <a href="{{ route('member.buy.history') }}">Riwayat Jual</a>
                        </div>
                      </div>
                    </li>
                    @endif

                    <li class="user-footer">
                      <div class="pull-left">
                        <a href="{{ route('member.profile.index') }}" class="btn btn-default btn-flat">Profile</a>
                      </div>
                      <div class="pull-right">
                        <a href="{{ route('logout') }}" class="btn btn-default btn-flat"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign out
                          </a>

                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                          </form>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        @endif

    </nav>
  </header>
  <div class="content-wrapper">
    <div class="container">
      <section class="content-header">
        @yield('header')
      </section>

      <section class="content">
        @yield('content')
      </section>
      
    </div>
    
  </div>
  
  <footer class="main-footer">
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Version</b> 2.4.0
      </div>
      <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
      reserved.
    </div>
    
  </footer>
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

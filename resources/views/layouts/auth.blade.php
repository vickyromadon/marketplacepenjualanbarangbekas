<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  @yield('title')
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{ mix('/css/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ mix('/css/auth.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            @yield('header')
        </div>
        <!-- /.login-logo -->

        <div class="login-box-body">
            @yield('content')
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

<!-- JS -->
<script src="{{ mix('/js/jquery.min.js') }}"></script>
<script src="{{ mix('/js/bootstrap.min.js') }}"></script>
<script src="{{ mix('/js/icheck.min.js') }}"></script>
<script src="{{ mix('/js/auth.js') }}"></script>

</body>
</html>

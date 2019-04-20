@extends('layouts.app')

@section('header')
	<h1>
	Article
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li >Article</li>        
	</ol>
@endsection

@section('content')
	<div class="row">
        <div class="box box-danger ">
            <div class="box-header with-border">
                <h3 class="box-title">
                    {{ $data->title }}
                </h3>
            </div>
            <div class="box-body">
                <p>
                    {!! $data->description !!}                    
                </p>                
            </div>
            <div class="box text-center" style="padding: 10px;">                
                <iframe width="560" height="315" src="http://www.youtube.com/embed/{{ $data->link }}" frameborder="0" allowfullscreen></iframe> 
                <p style="font-weight: bold">Berikut Video Tutorial dipersembahkan oleh RecycleDev</p>           
            </div>
        </div>
	</div>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
	
    <script type="text/javascript">
        jQuery(document).ready(function($){
            @if( session('success') )
                heading: 'Success',
                text : "{{ session('success') }}",
                position : 'top-right',
                allowToastClose : true,
                showHideTransition : 'fade',
                icon : 'success',
                loader : false
            @endif

            @if ( session('error') )
                $.toast({
                    heading: 'Error',
                    text : "{{ session('error') }}",
                    position : 'top-right',
                    allowToastClose : true,
                    showHideTransition : 'fade',
                    icon : 'error',
                    loader : false,
                    hideAfter: 5000
                });
            @endif

            
    </script>
@endsection
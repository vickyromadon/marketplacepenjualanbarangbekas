@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/owl.theme.default.min.css') }}">

    <style type="text/css">
        .item > img{
            width:100%;
            height: 400px !important;
        }

        .carousel-caption{
            background: rgba(0, 0, 0, 0.5); 
            width: 100%; 
            left: 0;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="{{ asset('images/slider/slider_1.jpg') }}" alt="Slider 1">
                        <div class="carousel-caption">
                            <h3>Slider 1</h3>
                            <p>Ini adalah contoh Slider 1</p>
                        </div>
                    </div>
                    <div class="item">
                        <img src="{{ asset('images/slider/slider_2.jpg') }}" alt="Slider 2">
                        <div class="carousel-caption">
                            <h3>Slider 2</h3>
                            <p>Ini adalah contoh Slider 2</p>
                        </div>
                    </div>
                    <div class="item">
                        <img src="{{ asset('images/slider/slider_3.jpg') }}" alt="Slider 3">
                        <div class="carousel-caption">
                            <h3>Slider 3</h3>
                            <p>Ini adalah contoh Slider 3</p>
                        </div>
                    </div>
                </div>
                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    
    <br>

    <!-- Produk Jual -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Kreasi Teratas</h1>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <div class="owl-carousel owl-theme">
                    <?php
                        $countProduct = count($newProduct) < 5 ? count($newProduct) : 5;
                    ?>
                    @for($i=0; $i<$countProduct; $i++)
                    <div class="col-md-12"> 
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4><i class="fa fa-fw fa-cubes"></i> {{ $newProduct[$i]->product->name }}</h4>
                            </div>
                            <div class="panel-body">
                                <img src="{{ asset('storage/'.$newProduct[$i]->product->file->path) }}" class="img-thumbnail img-responsive" style="width: 100%; height: 200px;">
                                <p>
                                    {!! strlen($newProduct[$i]->product->description) > 200 ? substr($newProduct[$i]->product->description, 0, 200) . '...' : $newProduct[$i]->product->description !!}
                                </p>
                                
                                <hr>

                                <h3 class="pull-left" style="margin: 0px;">
                                    <i class="fa fa-money"> Rp. {{ number_format($newProduct[$i]->product->price) }}</i>
                                </h3>
                                <a href="{{ route('member.product.show', ['id' => $newProduct[$i]->product->id]) }}" class="btn btn-info pull-right">
                                    <i class="fa fa-eye"></i> Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Berbagai macam kreasi lainnya</h1>
        </div>
        <div class="col-md-12">
            <div class="row">
                @foreach( $products as $product )
                    <div class="col-md-4 img-portfolio">
                        <a href="portfolio-item.html">
                            <img src="{{ asset('storage/'.$product->file->path) }}" class="img-thumbnail img-responsive" style="width: 100%; height: 200px;">
                        </a>
                        <h3>
                            <a href="{{ route('member.product.show', ['id' => $product->id]) }}">{{ $product->name }}</a>
                        </h3>
                        <p>
                            {!! strlen($product->description) > 200 ? substr($product->description, 0, 200) . '...' : $product->description !!}
                        </p>
                    </div>
                @endforeach
            </div>
            <div class="row">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    <!-- /.row -->

    <!-- Produk Lelang -->
    {{-- <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Produk Yang di Lelang</h1>
        </div>
        <div class="col-md-6">
            <p>The Modern Business template by Start Bootstrap includes:</p>
            <ul>
                <li><strong>Bootstrap v3.2.0</strong>
                </li>
                <li>jQuery v1.11.0</li>
                <li>Font Awesome v4.1.0</li>
                <li>Working PHP contact form with validation</li>
                <li>Unstyled page elements for easy customization</li>
                <li>17 HTML pages</li>
            </ul>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, omnis doloremque non cum id reprehenderit, quisquam totam aspernatur tempora minima unde aliquid ea culpa sunt. Reiciendis quia dolorum ducimus unde.</p>
        </div>
        <div class="col-md-6">
            <img class="img-responsive" src="http://placehold.it/700x450">
        </div>
    </div>
    
    <br>
    
    <div class="well">
        <div class="row">
            <div class="col-md-8">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias, expedita, saepe, vero rerum deleniti beatae veniam harum neque nemo praesentium cum alias asperiores commodi.</p>
            </div>
            <div class="col-md-4">
                <a class="btn btn-lg btn-default btn-block" href="#">Call to Action</a>
            </div>
        </div>
    </div> --}}
@endsection

@section('js')
    <script src="{{ asset('/js/owl.carousel.min.js') }}"></script>

    <script>
        jQuery(document).ready(function($){
            $(document).ready(function(){
                $('.owl-carousel').owlCarousel({
                    item:3,
                    loop:true,
                    // responsiveClass:true,
                    autoplay:true,
                    autoplayTimeout:5000,
                    autoplayHoverPause:false,
                    // responsive:{
                    //     0:{
                    //         items:1,
                    //         nav:true
                    //     },
                    //     600:{
                    //         items:2,
                    //         nav:false
                    //     },
                    //     1000:{
                    //         items:3,
                    //         nav:true,
                    //         loop:false
                    //     }
                    // }
                });
            });
        });
    </script>
@endsection
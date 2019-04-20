@extends('layouts.app')

@section('header')
	<h1>
	Riwayat Transaksi
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Riwayat Transaksi</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
        	<div class="box box-danger">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="transaction_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Total Harga</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Jumlah Kreasi</th>
                                    <th>Status</th>
                                    <th>Tipe</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
	<script>
		jQuery(document).ready(function($){
			// Checking Session
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

	        // function format currency
        	function number_format (number, decimals, dec_point, thousands_sep) {
			    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
			    var n = !isFinite(+number) ? 0 : +number,
			        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			        s = '',
			        toFixedFix = function (n, prec) {
			            var k = Math.pow(10, prec);
			            return '' + Math.round(n * k) / k;
			        };

			    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
			    if (s[0].length > 3) {
			        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
			    }
			    if ((s[1] || '').length < prec) {
			        s[1] = s[1] || '';
			        s[1] += new Array(prec - s[1].length + 1).join('0');
			    }
			    return s.join(dec);
			}

	        var table = $('#transaction_table').DataTable({
	            "bFilter": false,
	            "processing": true,
	            "serverSide": true,
	            "lengthChange": true,
	            "ajax": {
	                "url": "{{ route('member.transaction.index') }}",
	                "type": "POST",
	                "data" : {}
	            },
	            "language": {
	                "emptyTable": "Tidak Ada Data Tersedia",
	            },
	            "columns": [
	                {
	                   data: null,
	                   render: function (data, type, row, meta) {
	                       return meta.row + meta.settings._iDisplayStart + 1;
	                   },
	                   "width": "20px",
	                   "orderable": false,
	                },
                    {
                        "data": "total_price",
                        render: function (data, type, row){
                            return 'Rp. ' + number_format(data, 0, ',', ',');
                        },
                        "orderable": true,
                    },
	                {
	                    "data": "created_at",
                        render: function (data, type, row){
                            return moment(data, "YYYY-MM-DD").format("dddd, DD MMM YYYY");
                        },
                        "orderable": true,
	                },
                    {
                        "data": "arr_product",
                        render: function (data, type, row){
                            return data.length + ' Paket';
                        },
                        "orderable": true,
                    },
	                {
	                    "data": "status",
	                    render: function(data, type, row){
	                    	if(data == '{{\App\Models\Transaction::STATUS_PENDING}}'){
	                    		return '<span class="label bg-gray">Menunggu persetujuan</span>';
	                    	}
	                    	else if(data == '{{\App\Models\Transaction::STATUS_CANCEL}}'){
	                    		return '<span class="label bg-aqua">Dibatalkan</span>';
	                    	}
	                    	else if(data == '{{\App\Models\Transaction::STATUS_NOT_PAYMENT}}'){
	                    		return '<span class="label bg-orange">Belum membayar</span>';
	                    	}
	                    	else if(data == '{{\App\Models\Transaction::STATUS_APPROVE}}'){
	                    		return '<span class="label bg-blue">Barang persiapan dikirim</span>';
	                    	}
	                    	else if(data == '{{\App\Models\Transaction::STATUS_REJECT}}'){
	                    		return '<span class="label bg-red">Ditolak</span>';
	                    	}
	                    	else if(data == '{{\App\Models\Transaction::STATUS_FINISH}}'){
	                    		return '<span class="label bg-green">Selesai</span>';
	                    	}
	                    },
	                    "orderable": true,
	                },
	                {
                        "data": "type",
                        render: function (data, type, row){
                            if(data == '{{\App\Models\Transaction::TYPE_SELL}}'){
	                    		return '<span class="label bg-red">Penjualan</span>';
	                    	}
	                    	else if(data == '{{\App\Models\Transaction::TYPE_REQUEST}}'){
	                    		return '<span class="label bg-aqua">Permintaan</span>';
	                    	}
	                    	else{
	                    		return '<span class="label bg-orange">Pelelangan</span>';
	                    	}
                        },
                        "orderable": true,
                    },
	                {
	                    render : function(data, type, row){
	                        return	'<a href="{{ route('member.transaction.index') }}/'+ row.id +'" class="view-btn btn btn-xs btn-primary"><i class="fa fa-eye"> Lihat</i></a>';
	                    },
	                    "width": "10%",
	                    "orderable": false,
	                }
	            ],
	            "order": [ 2, 'desc' ],
	            "fnCreatedRow" : function(nRow, aData, iDataIndex) {
	                $(nRow).attr('data', JSON.stringify(aData));
	            }
	        });
        });
    </script>
@endsection
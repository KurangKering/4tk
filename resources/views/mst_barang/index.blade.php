@extends('layouts.backend')

@section('custom-css')
<style type="text/css">
th, td {
	white-space: nowrap;
}
</style>
@endsection

@section('content-header')
<h1>
	Master Barang
	{{-- <small>advanced tables</small> --}}
</h1>
{{-- <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">Tables</a></li>
	<li class="active">Data tables</li>
</ol> --}}

@endsection

@section('content')
<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				@role('humas')
				<h3 class="box-title"></h3>
				
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-primary" id="btn-tambah">Tambah Barang</button>
				</div>
				@endrole
			</div>
			<div class="box-body">
				<table id="table-barang" class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Barang ID</th>
							<th>Nama Barang</th>
							<th>Satuan</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@include('mst_barang/modal_barang')
@endsection

@section('custom-js')

<script type="text/javascript">

	var table_barang = $('#table-barang').DataTable({ 
		"bAutoWidth": false ,
		"processing": true, 
		"serverSide": true, 
		"order": [], 

		"ajax": {
			"url": '{{ url('datatables/mst_barang') }}',
			"type": "GET",

		},
		"columns": [
		{"data": "id", "orderable" : false},
		{"data": "nama"},
		{"data": "satuan"},
		{"data": "action"},
		],
		'columnDefs': [
		{
			"targets": 0,
			"className": "",
			"width" : "1%",
		},
		{
			"targets": 1,
			"width" : "35%"
		},
		{
			"targets": 2,
			"className": "text-center",
			"width" : "15%"
		},
		{
			"targets": 3,
			"width" : "1%"
		},
		

		],	
	});
	$('#btn-tambah').click(function(e) {
		show_modal();
	});



	

	
</script>
@endsection

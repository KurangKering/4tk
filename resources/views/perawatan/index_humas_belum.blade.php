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
	Daftar Pengajuan Perawatan
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
	<div class="col-lg-12">
		<div class="box">
			<div class="box-header"></div>
			<div class="box-body">
				<table class="table table-striped" id="table-daftar">
					<thead>
						<tr>
							<th>Perawatan ID</th>
							<th>Sub Bagian</th>
							<th>Tanggal Pengajuan</th>
							<th>Status</th>
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
@include('perawatan.modal_perawatan_detail')

@endsection

@section('custom-js')
<script type="text/javascript">
	var table_daftar = $('#table-daftar').DataTable({ 
		"bAutoWidth": false ,
		"processing": true, 
		"serverSide": true, 
		"order": [], 

		"ajax": {
			"url": '{{ url('datatables/daftar_perawatan_belum') }}',
			"type": "GET",

		},
		"columns": [
		{"data": "id"},
		{"data": "subbidang.nama"},
		{"data": "tanggal_pengajuan"},
		{"data": "status"},
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
			"width" : "1%"
		},
		{
			"targets": 3,
			"width" : "20%"
		},
		{
			"targets": 4,
			"width" : "1%"
		},

		],	
	});




</script>
@endsection

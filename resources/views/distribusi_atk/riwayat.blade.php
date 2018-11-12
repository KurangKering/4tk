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
	Daftar Riwayat Distribusi ATK
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
				<table class="table table-striped" id="table-riwayat">
					<thead>
						<tr>
							<th>Distribusi ID</th>
							<th>Sub Bagian</th>
							<th>Tanggal Distribusi</th>
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
@include('distribusi_atk.modal_riwayat_distribusi')

@endsection

@section('custom-js')
<script type="text/javascript">


	var table_riwayat = $('#table-riwayat').DataTable({ 
		"bAutoWidth": false ,
		"processing": true, 
		"serverSide": true, 
		"order": [2], 

		"ajax": {
			"url": '{{ url('datatables/daftar_tahap_distribusi') }}',
			"type": "GET",

		},
		"columns": [
		{"data": "distribusi_atk_id"},
		{"data": "distribusi_atk.permintaan_atk.subbidang.nama"},
		{"data": "tanggal_distribusi"},
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
			"width" : "1%"
		},

		],	
	});

	

	var cetak = function(id)
	{
		window.open("{{ url('distribusi_atk/riwayat') }}"+"/"+id+"/"+"cetak");	
	}

</script>
@endsection

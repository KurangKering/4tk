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
	Daftar Perawatan Selesai 
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
							<th>Tanggal Perawatan</th>
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
			"url": '{{ url('datatables/daftar_perawatan_complete') }}',
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


	var delete_perawatan = function(id) {
		swal({
			icon : 'warning',
			title : 'Hapus Perawatan',
			text : 'Yakin Ingin Menghapus Data ini ?',
			buttons : true
		})
		.then(clicked => {
			if (clicked) 
			{
				axios.post('{{ url("perawatan/delete_perawatan") }}', {
					_token : "{{ csrf_token() }}",
					id : id
				})
				.then(response => {
					console.log(response.data);
					res = response.data;
					if (res.success)
					{
						swal({
							icon : 'success',
							title : "Sukses",
							text : "Berhasil Menghapus data",
							timer : 1500,
							buttons : false,
							closeOnClickOutside : false
						})
						.then(t => {
							table_daftar.ajax.reload();
						})
					} else
					{
						swal({
							icon : 'warning',
							title : "Gagal",
							text : "Gagal Menghapus data",
							timer : 1500,
							buttons : false,
							closeOnClickOutside : false
						})
					}
				})
				.catch(err => {

				})
			}
		})
	}
	var cetak = function(id)
	{	
		window.open("{{ url('perawatan') }}"+"/"+id+"/"+"cetak_perawatan");	
		
	}

</script>
@endsection

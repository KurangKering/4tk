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
	Daftar Pengajuan Perawatan Fasilitas
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
				<h3 class="box-title"></h3>
				<div class="box-tools pull-right">
					<a href="{{ url('perawatan/pengajuan') }}" class="btn btn-primary">Pengajuan Baru</a>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-striped" id="table-pengajuan">
					<thead>
						<tr>
							<th>Pengajuan ID</th>
							<th>Tanggal Pengajuan</th>
							<th>Status Pengajuan</th>
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
@include('perawatan.modal_pengajuan_detail')
@endsection

@section('custom-js')
<script type="text/javascript">
	var table_pengajuan = $('#table-pengajuan').DataTable({ 
		"bAutoWidth": false ,
		"processing": true, 
		"serverSide": true, 
		"order": [], 

		"ajax": {
			"url": '{{ url('datatables/pengajuan_anggota') }}',
			"type": "GET",

		},
		"columns": [
		{"data": "id"},
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
		},
		
		{
			"targets": 3,
			"className": "text-center",
			"width" : "1%"
		},
		],
		
	});

	var show_delete = function(id) 
	{
		swal({
			icon : 'warning',
			title : 'Hapus Pengajuan',
			text : 'Apakah Anda Yakin Ingin Menghapus Pengajuan ?',
			buttons : true,
		})
		.then(clicked => {
			if (clicked) 
			{
				axios.post('{{ url("perawatan/delete_pengajuan") }}' , {
					_token : "{{ csrf_token() }}",
					id : id
				})
				.then(res => {
					if (res.data.success)
					{
						swal({
							icon : 'success',
							title : 'Sukses',
							text : 'Berhasil Menghapus Pengajuan',
							buttons : false,
							closeOnClickOutside : false,
							timer : 1500
						})
						.then(showed => {
							table_pengajuan.ajax.reload();
						});
					} else
					{
						swal({
							icon : 'warning',
							title : "Gagal",
							text : "Tidak Dapat Menghapus Pengajuan",
							buttons : false,
							closeOnClickOutside : false,
							timer : 1500
						})
					}
				})
			}
		})
	}
</script>
@endsection

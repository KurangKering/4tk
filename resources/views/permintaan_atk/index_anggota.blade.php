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
	Daftar Permintaan ATK
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
					<a href="{{ route('permintaan_atk.create') }}" class="btn btn-primary">Permintaan ATK Baru</a>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-striped" id="table-permintaan">
					<thead>
						<tr>
							<th>Permintaan ID</th>
							<th>Tanggal Permintaan</th>
							<th>Status Permintaan</th>
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
@include('permintaan_atk.modal_permintaan_detail')
@endsection

@section('custom-js')
<script type="text/javascript">
	var table_permintaan = $('#table-permintaan').DataTable({ 
		"bAutoWidth": false ,
		"processing": true, 
		"serverSide": true, 
		"order": [], 

		"ajax": {
			"url": '{{ url('datatables/permintaan_atk_anggota') }}',
			"type": "GET",

		},
		"columns": [
		{"data": "id"},
		{"data": "tanggal_permintaan"},
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
			title : 'Hapus Permintaan',
			text : 'Apakah Anda Yakin Ingin Menghapus Permintaan ?',
			closeOnClickOutside : false,
			buttons : {
				n : {
					text : 'Batal',
					className : 'btn btn-default',
				},
				y : {
					text : 'Hapus',
					className : 'btn btn-danger'
				} 
			},
		})
		.then(clicked => {
			if (clicked == 'y') 
			{
				axios.post("{{ route('permintaan_atk.index') }}" + "/" + id, {
					_method : "DELETE",
					_token : "{{ csrf_token() }}",
					id : id
				})
				.then(res => {
					if (res.data.success)
					{
						swal({
							icon : 'success',
							title : 'Sukses',
							text : 'Berhasil Menghapus Permintaan',
							buttons : false,
							closeOnClickOutside : false,
							timer : 1500
						})
						.then(showed => {
							table_permintaan.ajax.reload();
						});
					} else
					{
						swal({
							icon : 'warning',
							title : "Gagal",
							text : "Tidak Dapat Menghapus Permintaan",
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

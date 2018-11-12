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
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				
			</div>
			<div class="box-body">
				<table class="table table-striped" id="table-daftar">
					<thead>
						<tr>
							<th>Pengajuan ID</th>
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
@include('perawatan.modal_pengajuan_detail')

@endsection

@section('custom-js')
<script type="text/javascript">
	var table_daftar = $('#table-daftar').DataTable({ 
		"bAutoWidth": false ,
		"processing": true, 
		"serverSide": true, 
		"order": [], 

		"ajax": {
			"url": '{{ url('datatables/pengajuan_kepala') }}',
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
			"width" : "1%"
		},
		{
			"targets": 3,
			"className": "text-center",
			"width" : "1%"
		},
		],	
	});

	var showDialog = function(id, status)
	{
		var askMesage = '';
		var succMesage = '';
		if (status == '1') 
		{
			askMesage  = 'Yakin ingin Paraf ?';
			succMesage = 'Berhasil Paraf';
		} else if (status == '-1')
		{
			askMesage = 'Yakin ingin Menolak ?';
			succMesage = 'Berhasil Tolak';
			
		} else 
		{
			return;
		}
		swal({
			title: "Yakin!",
			text: askMesage,
			icon: "warning",
			dangerMode: true,
			buttons: true,
			closeOnClickOutside: false,
		}).then((dipencet) => {
			if (dipencet) {
				axios.post('{{ url('perawatan/paraf_pengajuan') }}', {
					perawatan_id : id,
					status : status,
				})
				.then(res => {
					if (res.data.success) 
					{
						swal({
							title: "Sukses!",
							text: succMesage,
							icon: "success",
							button: false,
							closeOnClickOutside: false,
							timer: 2000

						})
						.then((x) => {
							table_daftar.ajax.reload();
						})
					} else 
					{

					}

				})
				.catch( err => {

				});
			}
		});

		
	}
	var showDetailPermintaan = function(id)
	{
		alert(id);
	}
	var clearModal = function()
	{

		
	}
	var setModal = function() 
	{



	}

</script>
@endsection

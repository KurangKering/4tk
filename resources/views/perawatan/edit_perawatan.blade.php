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
	Edit Perawatan
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
			
			<div class="box-body">
				<table class="table">
					<tr>
						<th class="col-lg-2">Tanggal Permintaan</th>
						<td style="width: 1%">:</td>
						<td>{{ indonesian_date($dataPerawatan->tanggal_pengajuan) }}</td>
					</tr>
					<tr>
						<th class="col-lg-2">Sub Bidang</th>
						<td style="width: 1%">:</td>
						<td>{{ $dataPerawatan->subbidang->nama }}</td>
					</tr>
					<tr>
						<th class="col-lg-2">Pemohon</th>
						<td style="width: 1%">:</td>
						<td>{{ $dataPerawatan->user->name }}</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="box">
			<div class="box-body">
				<form id="form-perawatan" method="POST">
					@csrf
					<input type="hidden" name="perawatan_id" value="{{ $dataPerawatan->id }}">
					<table class="table table-bordered table-hover">

						<thead>
							<tr>
								<th>Nama Barang</th>
								<th class="text-center">Jumlah Kerusakan</th>
								<th >Biaya</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($detPerawatan as $k => $v)	
							
							<tr class="">
								<td style="vertical-align: middle" class="">{{ $v->mst_barang->nama }}</td>
								<td style="vertical-align: middle" class="text-center">{{ $v->jumlah }}</td>
								<td style="vertical-align: middle" >
									<input type="hidden" name="det_perawatan_id[]" value="{{ $v->id }}">

									<input type="text"  class="form-control" name="biaya[]" value="{{ (int) $v->biaya }}">
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<div class="text-center">
						<button type="button" class="btn btn-flat" onclick="location.href='{{ url('perawatan/index_humas_selesai') }}'" >Kembali</button>
						<button type="button" class="btn btn-primary" id="btn-submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('custom-js')

<script>
	$('#btn-submit').click(function(e) {
		$(this).attr('disabled', true);
		var formData = $('#form-perawatan').serialize();
		axios.post('{{ url("perawatan/$dataPerawatan->id/update_perawatan") }}',
			formData
			)
		.then(res => {
			console.log(res.data);
			if (!res.data.success) {
				swal({
					icon : 'warning',
					title : 'Gagal',
					text : "Periksa Kembali Data Input",
					buttons : false,
					closeOnClickOutside : false,
					timer : 1500, 
				});
			} else
			{
				swal({
					icon : 'success',
					title : 'Berhasil',
					text : 'Berhasil Merubah Perawatan',
					buttons : false,
					closeOnClickOutside : false,
					timer : 1500, 
				})
				.then(butt => {
					location.href = "{{ url('perawatan/index_humas_selesai') }}";
				})
			}
			$(this).attr('disabled', false);

		})
		.catch(err => {
			$(this).attr('disabled', false);

		});

	});
</script>
@endsection

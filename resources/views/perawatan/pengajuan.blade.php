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
	Pengajuan Perawatan Baru
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
	<div class="col-xs-4">
		<div class="box box-info">
			<div class="box-body">
				<div class="form-group">
					<label for="">Sub Bidang</label>
					<input type="text" class="form-control" readonly="" name="subbidang_id" id="subbidang_id" placeholder="" value="{{ Auth::check() ? Auth::user()->subbidang->nama : '' }}">
				</div>
				<div class="form-group">
					<label for="">Pemohon</label>
					<input type="text" class="form-control" readonly name="pemohon_user_id" id="pemohon_user_id" placeholder="" value="{{ Auth::check() ? Auth::user()->name : '' }}">
				</div>
				<div class="form-group">
					<label for="">Tanggal Permohonan</label>
					<input type="text" class="form-control" readonly="" id="tanggal_permohonan" placeholder="" value="{{ indonesian_date(date('Y-m-d H:i:s')) }}">
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" id="btn-submit" class="btn btn-primary btn-block">Submit</button>
			</div>
		</div>
	</div>
	<div class="col-xs-8">
		<div class="box box-primary">
			
			<div class="box-body">
				<table class="table">
					<thead>
						<tr>
							<th>Nama Barang</th>
							<th>Jumlah</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							
							<td class="col-xs-8">
								<select style="" id="select-barang" class="form-control">
									<option value="">---- Pilih Barang ----</option>
									@foreach ($barangs as $barang)
									<option data-satuan="{{ $barang->satuan }}" value="{{ $barang->id }}">{{ $barang->nama }}</option>
									@endforeach
								</select>
							</td>
							<td class="col-xs-2">
								<input id="jumlah" maxlength="5" type="text" class="form-control">
							</td>
							<td class="col-xs-2">
								<button id="btn-tambah-barang" class="btn btn-block btn-info" type="button">+</button>
							</td>

						</tr>
					</tbody>
				</table>

				
			</div>
		</div>
		<form role="form" method="POST" id="form-pengajuan">
			@csrf

			<div class="box box-default">
				<div class="box-body">
					<table class="table table-bordered" id="table-barang">
						<thead>
							<tr>
								<th width="1%">No</th>
								<th>Nama Barang</th>
								<th width="1%">Satuan</th>
								<th width="15%">Jumlah</th>
								<th width="1%">Action</th>
							</tr>
						</thead>
						<tbody id="content-barang">
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
@section('custom-js')
<script src="{{ asset('js/perawatan.js') }}" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
	const a = generatePerawatan({
		submitData : function()
		{
			a.dom.$btnSubmit.attr('disabled', true);
			var formData = $("#form-pengajuan").serialize();
			axios.post('{{ url('perawatan/store_pengajuan') }}', formData)
			.then(res => {
				if (res.data.success)
				{
					swal({
						icon : 'success',
						title : 'Sukses',
						text : 'Pengajuan Perawatan Berhasil',
						closeOnClickOutside : false,
						buttons : false,
						timer : 1000
					})
					.then(s => {
						location.href="{{ url('perawatan/index_anggota') }}";
					});
				}
				else {
					swal({
						icon : 'warning',
						title : 'Gagal',
						text : 'Periksa Kembali Data Barang',
						closeOnClickOutside : false,
						buttons : false,
						timer : 1000
					})
				}
				a.dom.$btnSubmit.attr('disabled', false);

			})
			.catch(err => {
				a.dom.$btnSubmit.attr('disabled', false);

			})	
		}

	});

</script>
@endsection

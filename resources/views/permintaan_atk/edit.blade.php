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
	Ubah Permintaan ATK
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
					<input type="text" class="form-control" readonly="" name="subbidang_id" id="subbidang_id" placeholder="" value="{{ $permintaan->subbidang->nama }}">
				</div>
				<div class="form-group">
					<label for="">Pemohon</label>
					<input type="text" class="form-control" readonly name="pemohon_user_id" id="pemohon_user_id" placeholder="" value="{{ $permintaan->user->name }}">
				</div>
				<div class="form-group">
					<label for="">Tanggal Permohonan</label>
					<input type="text" class="form-control" readonly="" id="tanggal_permohonan" placeholder="" value="{{ indonesian_date($permintaan->tanggal_permintaan) }}">
				</div>

			</div>
			<!-- /.box-body -->

			<div class="box-footer">
				<button type="button" id="btn-submit" class="btn btn-primary btn-block">Submit</button>

			</div>
		</div>
	</div>
	<div class="col-xs-8">
		<div class="box box-primary">
			<div class="box-body">
				<div class="row">
					<div class="col-xs-8">
						<select style="" id="select-barang" class="form-control">

							<option value="">---- Pilih Barang ----</option>
							@foreach ($barangs as $barang)
							<option data-satuan="{{ $barang->satuan }}" value="{{ $barang->id }}">{{ $barang->nama }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-xs-2">
						<input id="jumlah" maxlength="5" type="number" class="form-control">
					</div>
					<div class="col-xs-2">
						<button id="btn-tambah-barang" class="btn btn-block btn-info" type="button">+</button>
					</div>
				</div>
			</div>
		</div>
		<form role="form" method="POST" id="form-permintaan">
			@csrf
			{{ method_field('PATCH') }}
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
		</div>
	</form>
</div>
</div>
@endsection

@section('custom-js')
<script src="{{ asset('js/permintaan_atk.js') }}" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
	@php 
	$phpArrRequested = $requestedBarang;
	$jsArrRequested = json_encode($phpArrRequested);
	echo "var arr_barang = " . $jsArrRequested . ";\n";
	@endphp
	const a = generateATK({
		data : arr_barang,
		submitData : function()
		{
			a.dom.$btnSubmit.attr('disabled', true);
			var formData = a.dom.$form.serialize();
			axios.post('{{ route('permintaan_atk.update', $permintaan->id) }}', formData)
			.then(res => {
				if (res.data.success)
				{
					swal({
						icon : 'success',
						title : 'Sukses',
						text : 'Berhasil Merubah Permintaan ATK',
						closeOnClickOutside : false,
						buttons : false,
						timer : 1000
					})
					.then(s => {
						location.href="{{ route('permintaan_atk.index_anggota') }}";
					});

				} else
				{
					swal({
						icon : 'warning',
						title : 'Gagal',
						text : 'Periksa Kembali Data ATK',
						closeOnClickOutside : false,
						buttons : false,
						timer : 1000
					})
				}
				a.dom.$btnSubmit.attr('disabled', false);

			})
			.catch(err => {
				a.dom.$btnSubmit.attr('disabled', false);
			});
		}

	});
	
</script>
@endsection

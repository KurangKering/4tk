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
	Laporan Perawatan Fasilitas
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
				<div class="box-title"></div>
				<div class="box-tools pull-right">
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-4 col-lg-offset-4">
						<form action="">
							<div class="form-group">
								<label for="bulan" class="control-label">Bulan</label>
								<select name="bulan" id="bulan" class="form-control">
									@foreach (\Config::get('enums.bulan') as $k => $v)
									@php
									$bulanSebelumnya = Carbon\Carbon::now()->subMonth(1)->format('m');
									$selected = $k == $bulanSebelumnya ? 'selected' : '';
									@endphp
									<option {{ $selected }} value="{{ $k }}">{{ $v }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label for="bulan" class="control-label">Tahun</label>
								<select name="tahun" id="tahun" class="form-control">
									@php
									$tahun = $uniqueYear;
									@endphp
									@foreach ($tahun as $k => $v)
									@php
									$tahunIni = Carbon\Carbon::now()->format('Y');
									$selected = $v == $tahunIni ? 'selected' : '';
									@endphp
									<option {{ $selected }} value="{{ $v }}">{{ $v }}</option>
									@endforeach
								</select>							</div>
								<div class="form-group">
									<button type="button" id="btn-cetak" class="btn btn-info btn-block">Cetak Laporan</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endsection

	@section('custom-js')
	<script>
		$('#btn-cetak').click(function(e) {
			var bulan = $('#bulan').val();
			var tahun = $('#tahun').val();
			window.open("{{ url('laporan/cetak_perawatan') }}"+"?bulan="+bulan+"&tahun="+tahun);
		});
	</script>
	@endsection

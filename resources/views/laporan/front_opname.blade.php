@extends('layouts.backend')

@section('custom-css')
<style type="text/css">
th, td {
	white-space: nowrap;
}
#btn-cetak {
}
</style>
@endsection

@section('content-header')
<h1>
	Laporan Stock Opname
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
				<form action="">
					<div class="row">
						<div class="col-lg-4">
							<label for="" class="control-label">Bulan</label>
							<select name="bulan" id="bulan" class="form-control">
								@foreach (\Config::get('enums.bulan') as $k => $v)
								@php
								$bulanSebelumnya = Carbon\Carbon::now()->subMonth(1)->format('m');
								$inputBulan = request()->get('bulan');
								$bulanTerpilih = $inputBulan ?? $bulanSebelumnya;

								$selected = $k == $bulanTerpilih ? 'selected' : '';
								@endphp
								<option {{ $selected }} value="{{ $k }}">{{ $v }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-4">
							<label for="" class="control-label">Tahun</label>
							<select name="tahun" id="tahun" class="form-control">
								@php
								$tahun = $uniqueYear;
								@endphp
								@foreach ($tahun as $k => $v)
								@php
								$tahunIni = Carbon\Carbon::now()->format('Y');
								$inputTahun = request()->get('tahun');
								$tahunTerpilih = $inputTahun ?? $tahunIni;
								$selected = $v == $tahunTerpilih ? 'selected' : '';
								@endphp
								<option {{ $selected }} value="{{ $v }}">{{ $v }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-4">
							<div style="margin-top: 25px;">
								<button type="button" id="btn-cetak" class="btn btn-info btn-block">Submit</button>
							</div>
						</div>
					</div>	
				</form>
			</div>
		</div>
		<div class="box">
			<div class="box-header">

			</div>
			<div class="box-body">
				<table class="table">
					<thead>

					</thead>
					<tbody>
						<table class="table table-striped table-bordered table-hover" id="table-laporan">
							<thead>
								<tr>
									<th class="text-center">No</th>
									<th class="text-center">Nama Barang</th>
									<th class="text-center">Stock Barang <br> {{ indonesian_date($tanggalStock, 'j F Y') }}</th>
									<th class="text-center">Pembelian <br> {{ (indonesian_date($bulanLaporan, 'F Y')) }} </th>
									<th class="text-center">Distribusi <br> {{ (indonesian_date($bulanLaporan, 'F Y')) }} </th>
									<th class="text-center">Stock  <br> {{ $akhirBulanLaporan > now() ? indonesian_date(now(), 'j F Y') : (indonesian_date($akhirBulanLaporan, 'j F Y')) }} </th>
								</tr>
							</thead>
							<tbody>
								@php $no= 1; @endphp
								@foreach($stockBarangBulanItu as $k => $v)
								@php
								$iDistribusi = array_search($v['id'], array_column($arrDistribusiBarang, 'mst_atk_id'));
								$vDistribusi = $iDistribusi !== FALSE ? $arrDistribusiBarang[$iDistribusi]['jumlah'] : 0;
								$iPembelian = array_search($v['id'], array_column($arrPembelianBulanItu, 'mst_atk_id'));
								$vPembelian = $iPembelian !== FALSE ? $arrPembelianBulanItu[$iPembelian]['jumlah'] : 0;
								$stockSaatini = $v['stock'] - $vDistribusi + $vPembelian;
								@endphp
								<tr>
									<td>{{ $no++ }}</td>
									<td>{{ $v['nama'] }}</td>
									<td class="text-center">{{ $v['stock'] > 0 ? $v['stock'] : 0 }}</td>
									<td class="text-center">{{ $vPembelian }}</td>

									<td class="text-center">{{ $vDistribusi }}</td>
									<td class="text-center">{{ $stockSaatini }}</td>

								</tr>
								@endforeach
							</tbody>
						</table>
					</tbody>
				</table>
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
		location.href = ("{{ url('laporan/front_opname') }}"+"?bulan="+bulan+"&tahun="+tahun);
	});

	let tableLaporan = $("#table-laporan").DataTable({
		dom:  '<"html5buttons"B>lfrtip',
		buttons: [
		{
			text : 'Print',
			action: function() {
				let ques = '{!! request()->getQueryString() !!}';
				let url = '{{ url('laporan/cetak_opname') }}';
				if (ques) {
					url = url + '?' + ques;
				}
				window.open(url);

			}

		}
		]
	});
	</script>
	@endsection

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
	Laporan ATK
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
				<form action="" method="GET">
					
					<div class="form-group">
						<label for="" class="control-label">Bulan</label>
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
						<label for="" class="control-label">Tahun</label>
						<select name="tahun" id="tahun" class="form-control">
							@php
							$tahun = ['2017','2018','2019']
							@endphp
							@foreach ($tahun as $k => $v)
							@php
							$tahunSebelumnya = Carbon\Carbon::now()->subMonth(1)->format('Y');
							$selected = $v == $tahunSebelumnya ? 'selected' : '';
							@endphp
							<option {{ $selected }} value="{{ $v }}">{{ $v }}</option>
							@endforeach
						</select>
					</div>
					<button type="submit" id="btn-laporan">Check</button>
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Nama Barang</th>
							<th>Stock Barang <br> {{ indonesian_date($tanggalStock, 'j F Y') }}</th>
							<th>Pembelian <br> {{ (indonesian_date($bulanLaporan, 'F Y')) }} </th>
							<th>Distribusi <br> {{ (indonesian_date($bulanLaporan, 'F Y')) }} </th>
							<th>Stock  <br> {{ $akhirBulanLaporan > now() ? indonesian_date(now(), 'j F Y') : (indonesian_date($akhirBulanLaporan, 'j F Y')) }} </th>
						</tr>
					</thead>
					<tbody>
						@foreach($stockBarangBulanItu as $k => $v)
						@php
						$iDistribusi = array_search($v['id'], array_column($arrDistribusiBarang, 'mst_atk_id'));
						$vDistribusi = $iDistribusi !== FALSE ? $arrDistribusiBarang[$iDistribusi]['jumlah'] : 0;
						$iPembelian = array_search($v['id'], array_column($arrPembelianBulanItu, 'mst_atk_id'));
						$vPembelian = $iPembelian !== FALSE ? $arrPembelianBulanItu[$iPembelian]['jumlah'] : 0;
						$stockSaatini = $v['stock'] - $vDistribusi + $vPembelian;
						@endphp
						<tr>
							<td>{{ $v['nama'] }}</td>
							<td>{{ $v['stock'] > 0 ? $v['stock'] : 0 }}</td>
							<td>{{ $vPembelian }}</td>

							<td>{{ $vDistribusi }}</td>
							<td>{{ $stockSaatini }}</td>

						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('custom-js')

@endsection

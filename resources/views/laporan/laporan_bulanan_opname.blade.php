@php
$kota = 'Pekanbaru';
$tanggal = indonesian_date(now(), 'j F Y');
@endphp
<html>
<head>
	<head>
		<style type="text/css">
		.text-center {
			text-align: center;
		}

		.title {
			text-align: center;
			font-size: 18px;
			font-weight: bolder;
			text-transform: uppercase;
			letter-spacing: 1px;
		}
		.table {
			border-spacing: 0;
			border-collapse: collapse;
			width: 100%;
		}
		table th, table td {
			white-space: nowrap;
			border: 1px solid;
			padding: 5px;
		}

		#table-footer {
			float: right;
			margin-top: 30px;
		}

		#table-footer tr, #table-footer td {
			border: none;
		}
		

	</style>
</head>
</head>
<body>
	<p class="title">Laporan Stock Opname <br>
		{{ indonesian_date($bulanLaporan, 'F Y') }}
	</p>
	<hr>
	<table class="table">
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
	<table id="table-footer">
		<tr>
			<td>{{ "$kota, $tanggal" }}</td>
		</tr>
		<tr>
			<td style="padding: 25px 0;"></td>
		</tr>
		<tr>
			<td class="text-center">
				{{ strtoupper(Auth::user()->name) }}
			</td>
		</tr>
	</table>
</body>
</html>
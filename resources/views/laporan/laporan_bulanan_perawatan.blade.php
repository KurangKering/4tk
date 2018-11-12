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

	</style>
</head>
</head>
<body>
	<p class="title">Laporan Bulanan Perawatan  <br>
	</p>
	<table class="table">
		<thead>
			<tr>
				<th class="text-center">No</th>
				<th class="text-center">Nama Barang</th>
				<th class="text-center">Jumlah Kerusakan</th>
				<th class="text-center">Total Biaya</th>

			</tr>
		</thead>
		<tbody>
			@php
			$no = 1;
			@endphp
			@foreach ($dataLaporan as $k => $v)
			<tr>
				<td>{{ $no++ }}</td>
				<td>{{ $v['nama_barang']  }}</td>
				<td>{{ $v['jumlah'] . ' '. $v['satuan']}}</td>
				<td>{{ rupiah((int) $v['biaya']) }}</td>
			</tr>
			@endforeach
			<tr>
				<td colspan="3">Total Seluruhnya</td>
				<td>{{ rupiah($total) }}</td>
			
			</tr>
		</tbody>
	</table>
</body>
</html>
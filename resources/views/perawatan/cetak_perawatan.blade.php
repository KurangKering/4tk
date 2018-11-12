<html>
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
<body>
	<p class="title">Daftar Data Perawatan <br> 
	</p>
	<table class="table">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Barang</th>
				<th class="text-center">Total Biaya</th>
			</tr>
		</thead>
		<tbody>
			@php $no = 1; @endphp
			@foreach ($det_perawatan as $k => $det)
			<tr>
				<td style="width: 1%" class="text-center">{{ $no++ }}</td>
				<td>{{ $det->mst_barang->nama }}</td>
				<td class="text-center">{{ $det->biaya_manusia }}</td>
			</tr>
			@endforeach
			
		</tbody>
	</table>
</body>
</html>
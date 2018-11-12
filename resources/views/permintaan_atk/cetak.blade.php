<html>
<head>
	<title></title>

	<style type="text/css">
	#judul {
		font-weight: bold;
		text-align: center;
		font-size: 18px;
		line-height: 150%;
	}
	#table-content {
		width: 100%;
		border-collapse: collapse;
	}
	
	#table-content th {
		padding: 5px;
		text-align: center;
	}
	#table-content td {
		padding: 5px;
	}
	#table-content th, #table-content td {
		border: 1px solid ;
	}
	.w-1 {
		width: 1%;
	}

	.nowrap {
		white-space: nowrap;
	}
	.text-center {
		text-align: center;
	}
</style>
</head>
<body>
	<p id="judul">PERWAKILAN BKKBN PROVINSI RIAU <br>
		FORM PERMINTAAN ATK
	</p>
	<table id="table-header">
		<thead>
			<tr>
				
			</tr>
		</thead>
	</table>
	<table id="table-content">
		<thead>
			<tr>
				<th class="w-1 nowrap text-center">NO</th>
				<th>Nama Barang</th>
				<th class="w-1 nowrap text-center">Jumlah</th>
				<th class="w-1 nowrap text-center">Kode Barang</th>
				<th style="">Keterangan</th>
			</tr>
		</thead>
		<tbody>
			@php $no= 1; @endphp
			@foreach ($permintaan->det_permintaan_atk as $perm)
			<tr>
				<td class="nowrap text-center">{{ $no++ }}</td>
				<td class="nowrap">{{ $perm->mst_atk->nama }}</td>
				<td class="text-center">{{ $perm->jumlah }}</td>
				<td class="text-center">{{ $perm->mst_atk->kode }}</td>
				<td class="nowrap w-1">{{ '' }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
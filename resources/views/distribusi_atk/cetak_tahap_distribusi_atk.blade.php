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
	<p class="title">Daftar Riwayat Distribusi ATK <br> 
		 Tahap ID {{ $tahapDistribusi->id }} <br>
		Sub Bidang {{ $tahapDistribusi->distribusi_atk->permintaan_atk->subbidang->nama }}
	</p>
	<table class="table">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama ATK</th>
				<th class="text-center">Satuan</th>
				<th class="text-center">Jumlah</th>
			</tr>
		</thead>
		<tbody>
			@php $no = 1; @endphp
			@foreach ($tahapDistribusi->det_distribusi_atk as $k => $det)
			<tr>
				<td style="width: 1%" class="text-center">{{ $no++ }}</td>
				<td>{{ $det->mst_atk->nama }}</td>
				<td class="text-center">{{ $det->mst_atk->satuan }}</td>
				<td class="text-center">{{ $det->jumlah }}</td>
			</tr>
			@endforeach
			
		</tbody>
	</table>
</body>
</html>
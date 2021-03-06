@php
$kota = 'Pekanbaru';
$tanggal = indonesian_date(now(), 'j F Y');
@endphp
<html>
<head>
	<style type="text/css">
	.text-center {
		text-align: center;
	}

	.title {
		text-align: center;
		font-size: 18px;
		font-weight: bolder;
		letter-spacing: 1px;
		text-transform: uppercase;
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
	.breakNow { page-break-inside:avoid; }


</style>
</head>
<body>
	<p class="title">Daftar Pembelian ATK <br> 
		{{ indonesian_date($pembelian->tanggal_pembelian) }}
	</p>
	<table class="table">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Barang</th>
				<th class="text-center">Satuan</th>
				<th class="text-center">Jumlah</th>
				<th class="text-center">Harga</th>
			</tr>
		</thead>
		<tbody>
			@php $no = 1; @endphp
			@foreach ($pembelian->det_pembelian_atk as $k => $det)
			<tr>
				<td style="width: 1%" class="text-center">{{ $no++ }}</td>
				<td>{{ $det->mst_atk->nama }}</td>
				<td class="text-center">{{ $det->mst_atk->satuan }}</td>
				<td class="text-center">{{ $det->jumlah }}</td>
				<td class="text-center">{{ rupiah($det->harga) }}</td>
			</tr>
			@endforeach
			<tr>
				<td colspan="4" style="font-weight: bold;text-align: right">Total</td>
				<td class="text-center" style="font-weight: bolder;">{{ rupiah($pembelian->det_pembelian_atk->sum('harga')) }}</td>
			</tr>
		</tbody>
	</table>
	<table id="table-footer" class="breakNow">
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
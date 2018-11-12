<table>
	<thead>
		<tr>
			<th>Barang ID</th>
			<th>Nama</th>
			<th>Satuan</th>
			<th>Kebutuhan</th>
			<th>Jumlah Beli</th>
			<th>Harga</th>
		</tr>
	</thead>

	<tbody>
		@foreach ($data['daftarBarangBeli'] as $k => $v)
		@php
		$barang = $data['stockBarang']->where('id', $v['mst_atk_id'])->first();
		$kebutuhan = $v['pembelian'];
		@endphp
		<tr>
			<td>{{ ucwords(strtolower($barang->id)) }}</td>
			<td>{{ ucwords(strtolower($barang->nama)) }}</td>
			<td>{{ ucwords(strtolower($barang->satuan)) }}</td>
			<td>{{ (($kebutuhan)) }}</td>
			<td></td>
			<td></td>
		</tr>
		@endforeach
	</tbody>
</table>
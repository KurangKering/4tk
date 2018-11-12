@extends('layouts.backend')

@section('custom-css')
@parent
<style type="text/css">
th, td {
	white-space: nowrap;
}
</style>
@endsection

@section('content-header')
<h1>
	Daftar Pembelian ATK
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
		{{-- <div class="box">
			<div class="box-header">
				<div class="box-title"></div>
				<div class="box-tools pull-right">
					
				</div>
			</div>
			<div class="box-body">
				<table class="table">
					<thead>
						<tr>
							<th>Nama Barang</th>
							<th>Kebutuhan</th>
						</tr>
					</thead>
					
					<tbody>
						@foreach ($daftarBarangBeli as $k => $v)
						@php
						$barang = $stockBarang->where('id', $v['mst_atk_id'])->first();
						$kebutuhan = $v['pembelian'];
						@endphp
						@if ($kebutuhan < 0)
						<tr>
							<td>{{ ucwords(strtolower($barang->nama)) }}</td>
							<td>{{ (abs($kebutuhan)  . ' ' .   ucwords(strtolower($barang->satuan))) }}</td>
						</tr>
						@endif
						@endforeach
					</tbody>
					
				</table>
			</div>
			
		</div> --}}
		<div class="box">
			<div class="box-header">
				<a href="{{ route('pembelian_atk.create') }}" class="btn btn-primary" >Input Pembelian Baru</a>
				<div class="box-tools pull-right">
					<a  target="_blank" class="btn btn-info" href="{{ url('pembelian_atk/cetak_laporan_kebutuhan') }}">Cetak Laporan Kebutuhan</a>
				</div>
			</div>
			<div class="box-body">
				<table class="table" id="table-pembelian" nowrap>
					<thead>
						<tr>
							<th>Pembelian ID</th>
							<th>Tanggal Pembelian</th>
							<th>Total Harga</th>
							<th style="width: 1%">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($daftarPembelian as $k => $v)
						<tr>
							<td>{{ $v->id }}</td>
							<td>{{ indonesian_date($v->tanggal_pembelian) }}</td>
							<td>{{ rupiah($v->det_pembelian_atk->sum('harga')) }}</td>
							<td>
								<button type="button" onclick="show_modal('{{ $v->id }}')" class="btn btn-info">Detail</button>
								<button type="button" onclick="location.href = '{{ route('pembelian_atk.edit', $v->id) }}'" class="btn btn-info">Edit</button>
								<button type="button" onclick="cetak('{{ $v->id }}')"  class="btn btn-warning">Cetak</button>
								<button type="button" onclick="hapus('{{ $v->id }}')" class="btn btn-danger">Hapus</button>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@include('pembelian_atk.modal_pembelian_detail')
@endsection

@section('custom-js')
<script type="text/javascript">
	$('#table-pembelian').dataTable();
	
	var cetak = function(id)
	{
		window.open("{{ url('pembelian_atk') }}"+"/"+id+"/"+"cetak");	
	}
	var hapus = function(id)
	{
		swal({
			icon : 'warning',
			title : "Yakin ?",
			text : "Apakah Yakin ingin Menghapus Pembelian ? ",
			buttons: true,
		})
		.then(clicked => {
			if (clicked) {
				axios.post("{{ route('pembelian_atk.index') }}"+"/"+id, {
					_method : "DELETE",
					_token : "{{ csrf_token() }}",
					id : id,
				})
				.then(res => {
					if (res.data.success) {

						location.href = "{{ route('pembelian_atk.index') }}";
					}
					else {
						swal({
							icon : 'warning',
							title : 'Gagal',
							text : 'Tidak Dapat Menghapus Data',
							closeOnClickOutside : false,
							buttons : false,
							timer : 1500
						})
					}
				})
				.catch(err => {

				});
			}
		})
	}
</script>
@endsection

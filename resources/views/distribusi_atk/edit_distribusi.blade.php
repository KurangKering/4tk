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
	Edit Distribusi ATK
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
				<table class="table">
					<tr>
						<th class="col-lg-2">Tanggal Permintaan</th>
						<td style="width: 1%">:</td>
						<td>{{ indonesian_date($dataDistribusi->permintaan_atk->tanggal_permintaan) }}</td>
					</tr>
					<tr>
						<th class="col-lg-2">Sub Bidang</th>
						<td style="width: 1%">:</td>
						<td>{{ $dataDistribusi->permintaan_atk->subbidang->nama }}</td>
					</tr>
					<tr>
						<th class="col-lg-2">Pemohon</th>
						<td style="width: 1%">:</td>
						<td>{{ $dataDistribusi->permintaan_atk->user->name }}</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="box">
			<div class="box-body">
				<form id="form-distribusi" method="POST">
					@csrf
					<input type="hidden" name="distribusi_atk_id" value="{{ $dataDistribusi->id }}">
					<table class="table table-bordered table-hover">

						<thead>
							<tr>
								<th>Nama Barang</th>
								<th class="text-center">Stock</th>
								<th class="text-center">Butuh</th>
								<th class="text-center">Terdistribusi</th>
								<th class="text-center">Max Input</th>
								<th >Input</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($stockBarang as $k => $v)	
							@php 
							$indexD = array_search($v->id, array_column($telahDistribusi, 'mst_atk_id'));
							$butuh = $detPermintaanAtk
							->where('mst_atk_id', $v->id)
							->first()
							->jumlah ;
							$terdistribusi = $indexD !== FALSE ? $telahDistribusi[$indexD]['jumlah'] : '0';
							$selisih = $detPermintaanAtk
							->where('mst_atk_id', $v->id)
							->first()
							->jumlah - $terdistribusi;

							$maxInput = $v->stock <  $selisih ? $v->stock : $selisih ;
							$rowColor = '';
							if ($butuh == $terdistribusi) {
								$rowColor = 'bg-info';
							}
							@endphp
							<tr class="">
								<td class="{{ $rowColor }}">{{ $v->nama }}</td>
								<td class="text-center">{{ $v->stock }}</td>
								<td class="text-center">
									{{ $butuh }}
								</td>
								<td class="text-center">
									{{ $terdistribusi }}
								</td>
								<td class="text-center">
									{{ $maxInput }}
								</td>
								<td >
									<input type="hidden" name="mst_atk_id[]" value="{{ $v->id }}">
									<input type="hidden" name="max_input[]" value="{{ $maxInput }}">
									<input type="text" {{ $maxInput == 0 ? 'readonly' : '' }} class="form-control" name="jumlah[]" value="0">
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<div class="text-center">
						<button type="button" class="btn btn-flat" onclick="location.href='{{ url('distribusi_atk') }}'" >Kembali</button>
						<button type="button" class="btn btn-primary" id="btn-submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('custom-js')

<script>
	$('#btn-submit').click(function(e) {
		$(this).attr('disabled', true);
		var formData = $('#form-distribusi').serialize();
		axios.post('{{ url('distribusi_atk/post/distribusi') }}',
			formData
			)
		.then(res => {
			console.log(res.data);
			if (!res.data.success) {
				swal({
					icon : 'warning',
					title : 'Gagal',
					text : "Periksa Kembali Data Input",
					buttons : false,
					closeOnClickOutside : false,
					timer : 1500, 
				});
			} else
			{
				swal({
					icon : 'success',
					title : 'Berhasil',
					text : 'Berhasil Menambah Distribusi ATK',
					buttons : false,
					closeOnClickOutside : false,
					timer : 1500, 
				})
				.then(butt => {
					location.href = "{{ url('distribusi_atk') }}";
				})
			}
			$(this).attr('disabled', false);

		})
		.catch(err => {
			$(this).attr('disabled', false);

		});

	});
</script>
@endsection

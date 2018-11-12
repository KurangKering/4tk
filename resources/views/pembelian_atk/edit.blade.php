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
	Edit Pembelian ATK
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
	<form role="form" method="POST" id="form-pembelian">
		@csrf
		{{ method_field("PATCH") }}
		<div class="col-xs-4">
			<div class="box box-info">
				<div class="box-header">


				</div>

				<div class="box-body">
					
					<div class="form-group">
						<label for="">Pembelian ID</label>
						<input type="text" name="id" value="{{ $pembelian_atk->id }}" required class="form-control pickadate" id="id" readonly="">
					</div>
					<div class="form-group">
						<label for="">Tanggal Pembelian</label>
						<input type="text" name="tanggal_pembelian" required class="form-control pickadate" id="tanggal_pembelian">
					</div>
					
					<div class="form-group">
						<label for="">Import Dari Excel </label>
						<input type="file" class="form-control" name="import" id="import" >
					</div>
					<div class="form-group">
						<button type="button" id="btn-import" class="btn btn-warning btn-block">Import</button>
						
					</div>

				</div>
				<!-- /.box-body -->

				<div class="box-footer">
					<button type="submit" id="btn-submit" class="btn btn-primary btn-block">Submit</button>

				</div>
			</div>
		</div>
		<div class="col-xs-8">
			<div class="box box-primary">
				<div class="box-body">
					<table class="table">
						<thead>
							<tr>
								<th>Nama ATK</th>
								<th>Jumlah</th>
								<th>Harga</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-xs-6">
									<select style="" id="select-barang" class="form-control">

										<option value="">---- Pilih ATK ----</option>
										@foreach ($barangs as $barang)
										<option data-satuan="{{ $barang->satuan }}" value="{{ $barang->id }}">{{ $barang->nama }}</option>
										@endforeach
									</select>
								</td>
								<td class="col-xs-2">
									<input id="jumlah"  type="text" class="form-control">
								</td>
								<td class="col-xs-2">
									<input id="harga"  type="text" class="form-control">
								</td>
								<td class="col-xs-2">
									<button id="btn-tambah-barang" class="btn btn-block btn-info" type="button">+</button>
								</td>

							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="box box-default">
				<div class="box-body">

					<table class="table table-bordered" id="table-barang">
						<thead>
							<tr>
								<th width="1%">No</th>
								<th>Nama Barang</th>
								<th width="1%">Satuan</th>
								<th width="15%">Jumlah</th>
								<th width="15%">Harga</th>
								<th width="1%">Action</th>
							</tr>

						</thead>
						<tbody id="content-barang">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</form>

</div>

@endsection

@section('custom-js')
<script type="text/javascript">
	var date_pembelian = new Date(Date.parse("{{ date('D M d Y H:i:s O', strtotime($pembelian_atk->tanggal_pembelian)) }}"));

	$('input[name="tanggal_pembelian"]').pickadate({
		formatSubmit: 'yyyy-mm-dd',
		clear : false,
		onStart : function() {
			var date = new Date();
			this.set('select', date_pembelian);
		}
	});

	

	$(function() {
		
		$('#btn-import').click(function(e) {

			var file_data = $('#import').prop("files")[0];
			var form_data = new FormData();
			form_data.append("file", file_data);
			form_data.append("_token", "{{ csrf_token() }}");
			$.ajax({
				url: "{{ url('pembelian_atk/import_laporan_kebutuhan') }}",
				dataType: 'script',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                      
				type: 'post'
			})
			.done(function(res) {
				$("#import").val("");
				response = JSON.parse(res);
				$.each(response, function(index, val) {
					if (val.jumlah <= 0 || val.id == '' || val.harga <= 0) return;

					if (checkBarang(arr_barang, val.id) === false && val.id != null && val.jumlah != '')
					{
						arr_barang.push({
							'id' : val.id,
							'nama' : val.nama,
							'jumlah' : val.jumlah,
							'harga' : val.harga,
							'satuan' : val.satuan
						});

						populate_table(arr_barang, content_barang);
					}
				});



			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		})
	});
	
	$('#form-pembelian').submit(function(e) {
		e.preventDefault();
		$("#btn-submit").attr('disabled', true);

		var formData = $(this).serialize();
		axios.post('{{ route('pembelian_atk.update', $pembelian_atk->id) }}', formData)
		.then(res => {	
			
			if (!res.data.success) 
			{
				swal({
					icon : 'warning',
					title : 'Gagal',
					text : 'Terdapat error',
					buttons : false,
					timer : 1500,
				})
			} else
			{
				window.location.href = "{{ route('pembelian_atk.index') 	}}";
			}
			$("#btn-submit").attr('disabled', false);

		})
		.catch(err => {
			$("#btn-submit").attr('disabled', false);

		});
	})

	
	@php 
	$phpArrRequested = $requested;
	$jsArrRequested = json_encode($phpArrRequested);
	echo "var arr_barang = " . $jsArrRequested . ";\n";
	@endphp
	var content_barang = $('#content-barang');

	$(function() {
		populate_table(arr_barang, content_barang);

	});
	$('#btn-tambah-barang').click(function() {
		var id = $('#select-barang').val();
		var nama = $('#select-barang :selected').text();
		var jumlah = $('#jumlah').val();
		var harga = $('#harga').val();
		var satuan = $("#select-barang :selected").data('satuan');
		if (jumlah <= 0 || id == '') return;

		if (checkBarang(arr_barang, id) === false && id != null && jumlah != '')
		{
			arr_barang.push({
				'id' : id,
				'nama' : nama,
				'jumlah' : jumlah,
				'harga' : harga,
				'satuan' : satuan
			});
			
			populate_table(arr_barang, content_barang);
		}

		$('#select-barang').focus();
	});

	function checkBarang(data, id)
	{
		var index = false;
		for (var i = 0; i < data.length; i++) {
			if (data[i].id == id) 
			{
				index  = i;
				break;
			}
		}
		return index;
	}

	var populate_table = function (data, content_barang)
	{
		empty_table(content_barang);
		$.each(data.reverse(), function(index, val) {
			val.det_pembelian_id = val.det_pembelian_id || "undefined";

			var noPage = data.length - index;
			var tr = $("<tr/>");
			tr.append($("<td/>", {
				text : noPage,
				class : 'text-center',
				style : "vertical-align:middle;"
			}))
			.append($("<td/>", {
				text : val.nama,
				style : "vertical-align:middle;"

			}))
			.append($("<td/>", {
				text : val.satuan,
				style : "vertical-align:middle;"

			}))
			.append($("<td/>", {
				html : "<input type=\"text\" name=\"val_jumlah[]\" value=\""+val.jumlah+"\" class=\"form-control\">",
				class : 'text-center'
			}))
			.append($("<td/>", {
				html : "<input type=\"text\" name=\"val_harga[]\" value=\""+val.harga+"\" class=\"form-control\">",
				class : 'text-center'
			}))
			.append($("<td/>", {
				html : "<button data-id_jabatan=\""+val.id+"\" type=\"button\" class=\"btn btn-danger btn-hapus-jabatans\">Hapus</button>",
				class : 'text-center'
			}).click(function(event) {
				arr_barang.splice(index, 1);	
				populate_table(arr_barang, content_barang);			
			}))
			.append($("<input>", {
				type : "hidden",
				name : "val_id[]",
				value : val.id
			}))
			.append($("<input>", {
				type : "hidden",
				name : "val_det_id[]",
				value : val.det_pembelian_id
			}));

			content_barang.append(tr);
		});

	}

	function empty_table(content_barang)
	{
		content_barang.empty();
	}
</script>
@endsection

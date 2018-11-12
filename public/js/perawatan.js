let generatePerawatan = (function(settings) {
		//options
		var options = $.extend({
			data : [],
			dom : {
				$btnSubmit   : $("#btn-submit"),
				$btnTambah   : $('#btn-tambah-barang'),
				$form        : $("#form-permintaan"),
				$select      : $('#select-barang'),
				$inputJumlah : $('#jumlah'),
				$content     : $('#content-barang'),
			},
			submitData : function(){},
		}, settings);
		//bind events
		options.dom.$btnTambah.on('click', addFromClick);
		options.dom.$btnSubmit.on('click', options.submitData);
		//methods
		function addFromClick()
		{
			options.dom.$select.focus();
			let id = options.dom.$select.val();
			let nama = options.dom.$select.find(':selected').text();
			let jumlah = options.dom.$inputJumlah.val();
			let satuan = options.dom.$select.find(':selected').data('satuan');
			let obj = {
				id : id,
				nama : nama,
				jumlah : jumlah,
				satuan : satuan
			};
			if (validasiBarang(obj)) {
				options.data.push(obj);
				populateTable();
			}
		}
		function validasiBarang(obj)
		{
			if (obj.jumlah <= 0 || obj.id == '') return false;
			if (checkBarang(options.data, obj.id) === false && obj.id != null && obj.jumlah != '')
				return true;	
		}
		function populateTable()
		{
			var data = options.data;
			var el = options.dom.$content;
			emptyTable(el);
			$.each(data.reverse(), function(index, val) {
				val.det_perawatan_id = val.det_perawatan_id || "undefined";
				
				var noPage = data.length - index;
				var tr = $("<tr/>");
				tr.append($("<td/>", {
					text 	: noPage,
					class 	: 'text-center',
					style 	: "vertical-align:middle;"
				}))
				.append($("<td/>", {
					text 	: val.nama,
					style 	: "vertical-align:middle;"
				}))
				.append($("<td/>", {
					text 	: val.satuan,
					style 	: "vertical-align:middle;"
				}))
				.append($("<td/>", {
					class : 'text-center'
				})
				.append($("<input/>", {
					type 	: 'text',
					name 	: 'val_jumlah[]',
					value 	: val.jumlah,
					class 	: 'form-control', 	
				})))
				.append($("<td/>", {
					class 	: 'text-center'
				})
				.append($("<button/>", {
					'data-id_jabatan' : val.id,
					type 	: 'button',
					class 	: 'btn btn-danger btn-hapus-jabatans',
					text 	: 'Hapus'
				})
				.click(function(event) {
					options.data.splice(index, 1);	
					options.data.reverse();
					populateTable();			
				})))
				.append($("<input>", {
					type : "hidden",
					name : "val_id[]",
					value : val.id
				}))
				.append($("<input>", {
					type : "hidden",
					name : "val_det_id[]",
					value : val.det_perawatan_id
				}));
				el.append(tr);
			});
		}
		function checkBarang(data, id) {
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
		function emptyTable(el) {
			el.empty();
		}
		(function(){
			populateTable();
		})();
		return {
			dom : options.dom,
		}
	});
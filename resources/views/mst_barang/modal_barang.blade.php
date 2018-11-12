   <div id="modal-custom">
    <div class="box">
      <div id="error-message">

      </div>

      <form  id="frm-barang" class="form-horizontal" method="POST">
        <input type="hidden" name="id" value="" id="id">
        @csrf
        <input type="hidden" name="type" value="" id="type">
        <div class="box-body">
          <div class="form-group">
            <label for="" class="control-label col-lg-3">Nama Barang</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" name="nama" id="nama">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="control-label col-lg-3">Satuan</label>
            <div class="col-lg-9">
              <input type="text" class="form-control" name="satuan" id="satuan">
            </div>
          </div>
          <div class="box-footer">
            <div class="text-center">
             {{--  <button data-iziModal-close data-iziModal-transitionOut="bounceOutDown" class="btn bg-olive">Cancel</button> --}}
             <button type="submit" id="btn-submit" class="btn btn-primary">Simpan</button>
           </div>
         </div>
       </div>
     </form>
   </div>
 </div>
 @section('custom-js')
 @parent
 <script>
  $(function() {
    $('#frm-barang').submit(function(e) {
      e.preventDefault();
      submit_atk();
    });
  });
  $("#modal-custom").iziModal({
    subtitle: '',
    headerColor: '#88A0B9',

    onOpening: function(modal){
      modal.startLoading();
    },
    onOpened: function(modal){
      modal.stopLoading();
    },
  //   onClosing: function(){},
  //   onClosed: function(){},
  //   afterRender: function(){}
});
  var show_modal = function(id)
  {
    if( id )
    {
      axios.get('{{ url('mst_barang') . '/' }}'+id, {
      })
      .then(res => {
        response = res.data;
        clear_modal();
        $("#btn-submit").text("Ubah");

        set_modal_data(response);
      })
      .catch();
    }
    else
    {
      $("#type").val("new"); 
      clear_modal();
      $("#btn-submit").text("Simpan");

      $("#modal-custom").iziModal('setTitle', 'Form Input Barang Baru');
      $("#modal-custom").iziModal("open");
    }
  }
  var set_modal_data = function(data)
  {
    $("#error-message").html("");

    $("#modal-custom").iziModal('setTitle', 'Form Ubah Data Barang');
    $('#id').val(data.id);
    $('#nama').val(data.nama);
    $('#satuan').val(data.satuan);
    $("#type").val("edit");
    $("#modal-custom").iziModal('open');
  }
  var submit_atk = function()
  {
    var form_data = $("#frm-barang").serialize();
    axios.post('{{ url('mst_barang/submit_barang') }}', 
      form_data
      )
    .then(res => {
      response = res.data;
      console.log("success");
      $('#modal-custom').iziModal("close");
      table_barang.ajax.reload();
    })
    .catch(err => {
     var errors = err.response.data.errors;
     var list = '';
     $.each(errors, function(index, val) {
      $.each(val, function(index2, val2) {
       list += "<li>" + val2 + "</li>";
     });
    });
     $('#error-message').html("");
     $("#error-message").html(
       "<div class=\"alert alert-danger\">\
       <strong>Ooops!</strong> Terdapat Error.<br><br>\
       <ul>\
       "+list+"\
       </ul>\
       </div>\
       ");
     $("#modal-custom .iziModal-wrap").scrollTop(0); 
   });
  }
  var delete_barang = function(id)
  {
    axios.get('{{ url('mst_barang') . '/' }}' + id, {
    })
    .then(res =>  {
      response = res.data;
      $('#modal-custom').iziModal('setTitle', 'Hapus Data Barang');
      $("#error-message").html("");
      
      $("#id").val(response.id);
      $("#btn-submit").text("Delete");
      $("#type").val("delete");
      $("#nama").val(response.nama).attr("disabled","true");
      $("#satuan").val(response.satuan).attr("disabled","true");
      $('#modal-custom').iziModal('open');
    })
    .catch(err => {
    });
  }
  var clear_modal = function()
  {
    $("#id").val("");
    $("#nama").val("").removeAttr( "disabled" );
    $("#satuan").val("").removeAttr( "disabled" );
  }
</script>
@endsection
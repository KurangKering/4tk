   <div id="modal-custom" style="display: none;">
    <div class="box">
      <form  id="frm-atk" class="form-horizontal" method="POST">
        <input type="hidden" name="id" value="" id="id">
        @csrf
        <input type="hidden" name="type" value="" id="type">
        <div class="box-body">
          <div class="form-group">
            <label for="" class="control-label col-lg-3">Nama ATK</label>
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
              <button data-iziModal-close data-iziModal-transitionOut="bounceOutDown" class="btn bg-olive">Cancel</button>
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
      $('#frm-atk').submit(function(e) {
        e.preventDefault();
        submit_atk();
      });
    });
    $("#modal-custom").iziModal({
      subtitle: '',
      headerColor: '#88A0B9',
    // background: null,
    // theme: '',  
    // icon: null,
    // iconText: null,
    // iconColor: '',
    // rtl: false,
    // width: 600,
    // top: null,
    // bottom: null,
    // borderBottom: true,
    // padding: 0,
    // radius: 3,
    // zindex: 999,
    // iframe: false,
    // iframeHeight: 400,
    // iframeURL: null,
    // focusInput: true,
    // group: '',
    // loop: false,
    // arrowKeys: true,
    // navigateCaption: true,
  //   navigateArrows: true, // Boolean, 'closeToModal', 'closeScreenEdge'
  //   history: false,
  //   restoreDefaultContent: false,
  //   autoOpen: 0, // Boolean, Number
  //   bodyOverflow: false,
  //   fullscreen: false,
  //   openFullscreen: false,
  //   closeOnEscape: true,
  //   closeButton: true,
  //   appendTo: 'body', // or false
  //   appendToOverlay: 'body', // or false
  //   overlay: true,
  //   overlayClose: true,
  //   overlayColor: 'rgba(0, 0, 0, 0.4)',
  //   timeout: false,
  //   timeoutProgressbar: false,
  //   pauseOnHover: false,
  //   timeoutProgressbarColor: 'rgba(255,255,255,0.5)',
  //   transitionIn: 'comingIn',
  //   transitionOut: 'comingOut',
  //   transitionInOverlay: 'fadeIn',
  //   transitionOutOverlay: 'fadeOut',
  //   onFullscreen: function(){},
  //   onResize: function(){},
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
        axios.get('{{ url('mst_atk') . '/' }}'+id, {
        })
        .then(res => {
          response = res.data;
          clear_modal();
          set_modal_data(response);
        })
        .catch();
      }
      else
      {
        $("#type").val("new"); 
        clear_modal();
        $("#modal-custom").iziModal('setTitle', 'Form Input ATK Baru');
        $("#modal-custom").iziModal("open");
      }
    }
    var set_modal_data = function(data)
    {
      $("#modal-custom").iziModal('setTitle', 'Form Ubah Data ATK');
      $('#id').val(data.id);
      $('#nama').val(data.nama);
      $('#satuan').val(data.satuan);
      $("#type").val("edit");
      $("#modal-custom").iziModal('open');
    }
    var submit_atk = function()
    {
      var form_data = $("#frm-atk").serialize();
      axios.post('{{ url('mst_atk/submit_atk') }}', 
        form_data
        )
      .then(res => {
        response = res.data;
        console.log("success");
        $('#modal-custom').iziModal("close");
        table_barang.ajax.reload();
      })
      .catch(err => {
      });
    }
    var delete_barang = function(id)
    {
      axios.get('{{ url('mst_atk') . '/' }}' + id, {
      })
      .then(res =>  {
        response = res.data;
        $('#modal-custom').iziModal('setTitle', 'Hapus Data ATK');
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
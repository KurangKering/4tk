  <div id="modal-detail" style="display: none">
    <div class="box">

      <div class="box-body">
        <table class="table" nowrap>
          <tr>
            <th style="width: 1%">Pemohon</th>
            <td style="width: 1%">:</td>
            <td><span id="permintaan-user"></span></td>
          </tr>
          <tr>
            <th style="width: 1%">Tanggal Permintaan</th>
            <td style="width: 1%">:</td>
            <td><span id="tanggal-permintaan"></span></td>
          </tr>
          <tr>
            <th style="width: 1%">Sub Bidang</th>
            <td style="width: 1%">:</td>
            <td><span id="sub-bidang"></span></td>
          </tr>
          <tr>
            <th style="width: 1%">Status</th>
            <td style="width: 1%">:</td>
            <td><span id="status"></span></td>
          </tr>
        </table>
        <div class="box-body">
          <table class="table table-bordered">
           <thead>
             <tr>
              <th>No</th>
              <th>Nama ATK</th>
              <th>Kebutuhan</th>
              <th>Telah di Distribusi</th>
            </tr>
          </thead>
          <tbody id="content-atk">

          </tbody>
        </table>
      </div>
      <div class="box-footer">
        <div class="text-center">
          <button data-iziModal-close data-iziModal-transitionOut="bounceOutDown" class="btn bg-olive">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>

  @section('custom-js')
  @parent
  <script>


    $("#modal-detail").iziModal({
      title: 'Detail Distribusi ATK',
      subtitle: '',
      headerColor: '#88A0B9',
    //   background: null,
    //   theme: '',  
    //   icon: null,
    //   iconText: null,
    //   iconColor: '',
    //   rtl: false,
    width: 700,
    top: 30,
    //   bottom: null,
    //   borderBottom: true,
    padding: 10,
    //   radius: 3,
    zindex: 99999999,
    //   iframe: false,
    //   iframeHeight: 400,
    //   iframeURL: null,
    //   focusInput: true,
    //   group: '',
    //   loop: false,
    //   arrowKeys: true,
    //   navigateCaption: true,
    // navigateArrows: true, // Boolean, 'closeToModal', 'closeScreenEdge'
    // history: false,
    // restoreDefaultContent: false,
    // autoOpen: 0, // Boolean, Number
    bodyOverflow: true,
    // fullscreen: true,
       // openFullscreen: true,
    // closeOnEscape: true,
    // closeButton: true,
    // appendTo: 'body', // or false
    // appendToOverlay: 'body', // or false
    // overlay: true,
    // overlayClose: true,
    // overlayColor: 'rgba(0, 0, 0, 0.4)',
    // timeout: false,
    // timeoutProgressbar: false,
    // pauseOnHover: false,
    // timeoutProgressbarColor: 'rgba(255,255,255,0.5)',
    // transitionIn: 'comingIn',
    // transitionOut: 'comingOut',
    // transitionInOverlay: 'fadeIn',
    // transitionOutOverlay: 'fadeOut',
    // onFullscreen: function(){},
    // onResize: function(){},
    onOpening: function(modal){
      modal.startLoading();
    },
    onOpened: function(modal){
      modal.stopLoading();
    },
    // onClosing: function(){},
    // onClosed: function(){},
    // afterRender: function(){}
  });

    var show_modal = function(id)
    {
     axios.get('{{ url('distribusi_atk/') . '/' }}'+id+"/detail", {
     })
     .then(res => {
      response = res.data;
      var tbodyClass = $('#content-atk');
      set_modal_data(response, tbodyClass);
      $('#modal-detail').iziModal('open');
    })
     .catch();

   }

   var clear_modal = function()
   {
    $('#content-atk').html('');
    $('#permintaan-user').text('');
    $('#tanggal-permintaan').text('');
    $('#sub-bidang').text('');
    $('#status').text('');

  }
  var findOn = function(item, val) {
    return item == val;
  }
  var set_modal_data = function(data, tBody)
  {
    clear_modal();
    var detPermintaan = data.permintaan.det_permintaan_atk;
    var telahDistribusi = data.telah_distribusi;
    var contentTBody = '';
    $.each(detPermintaan, function(index, val) {
      var jumlahDistribusi = 0;
      var warna = '';
      $.each(telahDistribusi, function(index, val2) {
        if (val2.mst_atk_id == val.mst_atk_id) 
        {
          jumlahDistribusi = val2.jumlah;
          return;
        }
      });
      if (val.jumlah == jumlahDistribusi) {
        warna = 'bg-primary';
      }

      var tr = $("<tr>", {
        class : warna,
      });
      tr.append($("<td/>", {
        text : (index + 1),
        class : 'text-center',
      }))
      .append($("<td/>", {
        text : val.mst_atk.nama,
      }))
      .append($("<td/>", {
        text : val.jumlah + " " + val.mst_atk.satuan,
      }))
      .append($("<td/>", {
        text : jumlahDistribusi + " " + val.mst_atk.satuan,
      }));

      tBody.append(tr);
    });

    $('#permintaan-user').text(data.permintaan.user.name);
    $('#tanggal-permintaan').text(data.permintaan.tanggal_manusia);
    $('#sub-bidang').text(data.permintaan.subbidang.nama);
    $('#status').text(data.permintaan.status);

  }


</script>
@endsection

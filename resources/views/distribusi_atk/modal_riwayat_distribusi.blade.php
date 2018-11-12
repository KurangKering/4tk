  <div id="modal-detail">
    <div class="box">

      <div class="box-body">
        <table class="table" nowrap>

          <tr>
            <th style="width: 1%">Tanggal Distribusi</th>
            <td style="width: 1%">:</td>
            <td><span id="tanggal-distribusi"></span></td>
          </tr>
          <tr>
            <th style="width: 1%">Sub Bidang</th>
            <td style="width: 1%">:</td>
            <td><span id="sub-bidang"></span></td>
          </tr>
        </table>
        <div class="box-body">
          <table class="table table-bordered">
           <thead>
             <tr>
              <th>No</th>
              <th>Nama ATK</th>
              <th>Distribusi</th>
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
   axios.get('{{ url('distribusi_atk/detail_riwayat') . '/' }}'+id, {
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
  $('#tanggal-distribusi').text('');
  $('#sub-bidang').text('');

}
var findOn = function(item, val) {
  return item == val;
}
var set_modal_data = function(data, tBody)
{
  clear_modal();
  var detDistribusi = data.det_distribusi_atk;
  var contentTBody = '';
  $.each(detDistribusi, function(index, val) {

    var tr = $("<tr>", {
      class : '',
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
    }));

    tBody.append(tr);
  });

  $('#tanggal-distribusi').text(data.tanggal_manusia);
  $('#sub-bidang').text(data.distribusi_atk.permintaan_atk.subbidang.nama);

}
var delete_distribusi = function(id)
{
  swal({
    icon : 'warning',
    title : 'Hapus Distribusi',
    text : 'Yakin ingin menghapus riwayat distribusi?',
    buttons : true,
    closeOnClickOutside: false
  })
  .then(ok => {
    if (ok) {
      axios.post('{{ url('distribusi_atk/delete_tahap') }}', {
        id : id
      })
      .then(response => {
        res = response.data;
        if (res.success) 
        {
          swal({
            icon : 'success',
            title : 'Berhasil',
            text : 'Berhasil Menghapus Data',
            timer : 1500,
            buttons : false,
            closeOnClickOutside : false
          })
          .then(ok2 => {
            location.reload();
          })
        } else
        {
         swal({
          icon : 'warning',
          title : 'Gagal',
          text : 'Gagal Menghapus Data',
          timer : 1500,
          buttons : false,
          closeOnClickOutside : false
        })
       }
     })
      .catch(res => {

      })
    }

  })
  
}

</script>
@endsection

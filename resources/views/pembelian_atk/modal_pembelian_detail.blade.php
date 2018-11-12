 @section('custom-css')
 @parent
 <style type="text/css">
 td.total-title {
  text-align: right;
  font-weight: bold;
}
</style>
@endsection

<div id="modal-detail">
  <div class="box">

    <div class="box-body">
      <table class="table" nowrap>
        <tr>
          <th style="width: 1%">Pembelian ID</th>
          <td style="width: 1%">:</td>
          <td><span id="id"></span></td>
        </tr>
        <tr>
          <th style="width: 1%">Tanggal Pembelian</th>
          <td style="width: 1%">:</td>
          <td><span id="tanggal-permintaan"></span></td>
        </tr>


      </table>
      <table class="table table-bordered table-striped">
       <thead>
         <tr>
          <th>No</th>
          <th>Nama ATK</th>
          <th>Jumlah</th>
          <th>Harga</th>
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

@section('custom-js')
@parent
<script>


  $("#modal-detail").iziModal({
    title: 'Detail Pembelian ATK',
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
   axios.get('{{ url('pembelian_atk/') . '/' }}'+id, {
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
  $('#id').text('');
  $('#permintaan-user').text('');
  $('#tanggal-permintaan').text('');
  $('#sub-bidang').text('');
  $('#status').text('');

}
var set_modal_data = function(data, tBody)
{

  clear_modal();
  var detPermintaan = data.det_pembelian_atk;
  var contentTBody = '';
  $.each(detPermintaan, function(index, val) {
    var trElement = $("<tr>");
    var tdNo = trElement.append("<td>"+(index+1)+"</td>");
    var tdNamaBarang = trElement.append("<td>"+val.mst_atk.nama+"</td>");
    var tdJumlahBarang = trElement.append("<td>"+val.jumlah+" "+ val.mst_atk.satuan+"</td>");
    var tdHargaBarang = trElement.append("<td>"+val.harga_rupiah+"</td>");
    tBody.append(trElement);
    if (detPermintaan.length == (index + 1) ) {
      var lasTrElement = $("<tr>");
      lasTrElement.append("<td colspan=\"3\" class=\"total-title\">Total</td>");
      lasTrElement.append("<td>"+data.total_harga+"</td>");
      tBody.append(lasTrElement);
    }
  });

  $('#permintaan-user').text(data.permintaan_user_id);
  $('#id').text(data.id);
  $('#tanggal-permintaan').text(data.tanggal_manusia);
  $('#sub-bidang').text(data.subbidang_id);
  $('#status').text(data.status);
}


</script>
@endsection

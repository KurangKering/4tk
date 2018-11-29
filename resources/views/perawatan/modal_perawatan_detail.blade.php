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
            <th style="width: 1%">Tanggal Perawatan</th>
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
        <table class="table table-bordered table-striped">
         <thead>
           <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Biaya</th>
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
    title: 'Detail Perawatan',
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
    var url = '{{ url("perawatan/") }}'+'/'+id+'/show_perawatan';
    axios.get(url, {
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
  var set_modal_data = function(data, tBody)
  {

    clear_modal();
    var detPengajuan = data.det_perawatan;
    var contentTBody = '';
    $.each(detPengajuan, function(index, val) {
      var tr = $("<tr>");
      tr.append($("<td/>", {
        text : (index + 1),
        class : 'text-center',
      }))
      .append($("<td/>", {
        text : val.mst_barang.nama,
      }))
      .append($("<td/>", {
        text : val.jumlah + " " + val.mst_barang.satuan,
      }))
      .append($("<td/>", {
        text : val.biaya_manusia,
      }));

      tBody.append(tr);
    });

    var trk = $("<tr>");
    trk.append($("<td/>", {
      text : "Total"
    })
    .css({
      'font-weight' : 'bold',
      'text-align' : 'right'
    })
    .attr({
      colspan: '3',
    })

    )
    .append($("<td/>", {
      text : data.total,
    }));

    tBody.append(trk);
    $('#permintaan-user').text(data.user.name);
    $('#tanggal-permintaan').text(data.tanggal_manusia_perawatan);
    $('#sub-bidang').text(data.subbidang.nama);
    $('#status').text(data.status);

  }


</script>
@endsection

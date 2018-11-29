  <div id="modal-user" style="display: none;">
    <div class="box">
      <div class="box-body">
        <div id="error-message">

        </div>
        {!! Form::open(array('route' => 'users.store','method'=>'POST', 'id' => 'frm-user')) !!}
        {!! Form::hidden('id', '') !!}
        {!! Form::hidden('type', 'get') !!}
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Nama:</strong>
              {!! Form::text('name', null, array('placeholder' => '','class' => 'input-data form-control')) !!}
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Username:</strong>
              {!! Form::text('username', null, array('placeholder' => '','class' => 'input-data form-control')) !!}
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Email:</strong>
              {!! Form::text('email', null, array('placeholder' => '','class' => 'input-data form-control')) !!}
            </div>
          </div><div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Sub Bidang:</strong>
              {!! Form::select('subbidang_id',$subbidang, null, array('class' => 'input-data form-control')) !!}
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Password:</strong>
              {!! Form::password('password', array('placeholder' => '','class' => 'input-data form-control')) !!}
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Confirm Password:</strong>
              {!! Form::password('confirm-password', array('placeholder' => '','class' => 'input-data form-control')) !!}
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Role:</strong>

              {!! Form::select('roles[]', $roles,[], array('class' => 'input-data form-control')) !!}
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </form>
  </div>
</div>

<div id="modal-detail" style="display: none;">
  <div class="box">
    <div class="box-header"></div>
    <div class="box-body">
      <form  id="frm-detail" class="form-horizontal">
        <div class="form-group ">
          <label for="" class="control-label col-lg-3">Nama</label>
          <div class="col-lg-9">
            <input type="text" readonly class="form-control" id="det-nama">
          </div>
        </div>
        <div class="form-group ">
          <label for="" class="control-label col-lg-3">Username</label>
          <div class="col-lg-9">
            <input type="text" readonly class="form-control" id="det-username">
          </div>
        </div>
        <div class="form-group ">
          <label for="" class="control-label col-lg-3">Email</label>
          <div class="col-lg-9">
            <input type="text" readonly class="form-control" id="det-email">
          </div>
        </div>
        <div class="form-group ">
          <label for="" class="control-label col-lg-3">Sub Bidang</label>
          <div class="col-lg-9">
            <input type="text" readonly class="form-control" id="det-subbidang">
          </div>
        </div>
        <div class="form-group ">
          <label for="" class="control-label col-lg-3">Hak Akses</label>
          <div class="col-lg-9">
            <input type="text" readonly class="form-control" id="det-hak-akses">
          </div>
        </div>
        <div class="box-footer">
         <div class="text-center">
           <button data-iziModal-close data-iziModal-transitionOut="bounceOutDown" class="btn bg-olive">Tutup</button>
         </div>
       </div>
     </form>
   </div>
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
  $("#modal-user").iziModal({
    subtitle: '',
    zindex: 999999,

    headerColor: '#88A0B9',
    onOpening: function(modal){
      modal.startLoading();
    },
    onOpened: function(modal){
      modal.stopLoading();
    },

  });
  $("#modal-detail").iziModal({
    title: 'Detail Pengguna',
    subtitle: '',
    headerColor: '#88A0B9',
    onOpening: function(modal){
      modal.startLoading();
    },
    onOpened: function(modal){
      modal.stopLoading();
    },

  });
  $("#frm-user").submit(function(e) {
    e.preventDefault();
    
    submit_user();

  })
  var show_detail = function(id)
  {
    axios.get("{{ url('users') . '/' }}"+id)
    .then(response => {
      res = response.data;
      res.subbid = res.subbidang ? res.subbidang.nama : '-';
      $("#det-nama").val(res.name);
      $("#det-username").val(res.username);
      $("#det-email").val(res.email);
      $("#det-subbidang").val(res.subbid);
      $("#det-hak-akses").val(res.hak_akses);
      $("#modal-detail").iziModal("open");  
    })
    .catch(err => {

    })
  }
  var show_modal = function(id)
  {
    if( id )
    {
      axios.get('{{ url('users') . '/' }}'+id, {
      })
      .then(res => {
        response = res.data;
        clear_modal();
        $("input[name='type']").val("edit");
        $("button[type='submit']").text('Simpan');

        $("#modal-user").iziModal('setTitle', 'Form Ubah Data Pengguna');

        set_modal_data(response);
      })
      .catch();
    }
    else
    {
      clear_modal();
      $("input[name='type']").val("new");
      $("button[type='submit']").text('Tambah');

      $("#modal-user").iziModal('setTitle', 'Form Input Pengguna Baru');
      $("#modal-user").iziModal("open");
    }
  }
  var set_modal_data = function(data)
  {

    $("#error-message").html("");
    $("input[name='id']").val(data.id);
    $("input[name='name']").val(data.name);
    $("input[name='username']").val(data.username);
    $("input[name='email']").val(data.email);
    $("select[name='subbidang_id']").val(data.subbidang_id);
    $.each(data.roles, function(index, val) {
     $("select[name='roles[]'] option[value='"+val.name+"']").prop("selected", true);
   });
    $("#modal-user").iziModal('open');
    $("#modal-user .iziModal-wrap").scrollTop(0);            
  }
  var submit_user = function()
  {
    $("button[type='submit']").attr('disabled', true);
    var formData = $('#frm-user').serialize();

    axios.post('{{ url('users/operate') }}', formData)
    .then(response => {
      $("button[type='submit']").attr('disabled', false);
      res = response.data;
      location.reload();
    })
    .catch(err => {
      $("button[type='submit']").attr('disabled', false);

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
      $("#modal-user .iziModal-wrap").scrollTop(0);            

    })

  }
  var delete_user = function(id)
  {
    axios.get('{{ url('users') . '/' }}' + id, {
    })
    .then(res =>  {
      response = res.data;
      $('#modal-user').iziModal('setTitle', 'Hapus Data Pengguna');
      $("input[name='type']").val("delete");
      set_modal_data(response);
      $('.input-data').attr('disabled', true);
      $("button[type='submit']").text('Delete');
      // $("#btn-submit").text("Delete");
      // $("#type").val("delete");
      // $("#nama").val(response.nama).attr("disabled","true");
      // $("#satuan").val(response.satuan).attr("disabled","true");
      // $('#modal-user').iziModal('open');
    })
    .catch(err => {
    });
  }
  var clear_modal = function()
  {
    $("#error-message").html("");
    $("input[name='id']").val("");
    $("input[name='name']").val("");
    $("input[name='username']").val("");
    $("input[name='email']").val("");
    $("input[name='password']").val("");
    $("input[name='confirm_password']").val("");
    $("select[name='subbidang_id']").prop("selectedIndex", 0);
    $("select[name='roles[]']").val("");
    $('.input-data').attr('disabled', false);

  }
</script>
@endsection
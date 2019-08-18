@extends('layouts.inner')
@section('content')
<div class="main_content">
  <div class="desktop_top">
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-4 sidebar">
        <div class="dashboard_menu">
          <ul>
            <li ><a href="{{url('/dashboard')}}">My Review</a></li>
            <li  class="active"><a href="{{url('/manage-profile')}}">Change Password</a></li>
          </ul>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="login_form gray_box">
            <h2>Manage <span style="display:inline-block">Password</span></h2>
              <div id="scroll_error_signup"></div>
              <div id="error_div_signup"></div>
              <form name="" action="{{url('/save-profile')}}" method="post" id="change_password">
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                @if(isset($msg))
                  {{$msg}}
                @endif
                <div class="form-group">
                  <div class="row">
                      <div class="col-sm-6">
                        <label>Password*</label>
                        <input type="password" name="password" value="" class="form-control" id="password" placeholder="Password" required>
                      </div>
                      <div class="col-sm-6">
                        <label>Confirm Password*</label>
                        <input type="password"  name="cpassword" value="" class="form-control" id="confirm_password" placeholder="confirm password" required>
                      </div>
                  </div>
                  <div id="append_password"></div>
                  <a href="#" id="generate_password">Generate Password</a>
                </div>
                <button type="submit" id="submit_signup" class="btn btn-success">Save</button>
              </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
      var options = {
      beforeSubmit: function(){ 
        $('#error_div_signup').hide();
        $("#submit_signup").button('loading');
      },
      data : {'userEmail' : '<?php echo isset($userEmail) ? $userEmail : ''; ?>'},
      success:function(data){
        $("#submit_signup").button('reset');
          if(data.success==1){
          $('#error_div_signup').hide();
          notice('Change password',"Password has Changed successfully.",'success');
        }else{
          //console.log(data.errors.password);
          error_msg = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors.password+'</div>';
          $('#error_div_signup').hide();
          $('#error_div_signup').html(error_msg);
          
          // top position relative to the document
          var pos = $("#scroll_error_signup").offset().top;
  
          // animated top scrolling
          $('body, html').animate({scrollTop: 0});
          $('#error_div_signup').show('slow');
        }
        return false;
      },
      resetForm:false
    }; 
    // pass options to ajaxForm 
    $('#change_password').ajaxForm(options);

    $('#generate_password').on('click',function(){
    var html = '<div class="form-group"><div class="row" style="margin-top:12px;"><div class="col-sm-6"><input type="text" id="suggestPassword" class="form-control"></div><div class="col-sm-3"><input type="button" id="fillNow" class="form-control btn btn-primary" value="Fill Now"></div><div class="col-sm-3"><input type="button" id="cancelPass" class="form-control btn btn-danger" value="Cancel"></div></div></div>';
    var password = randomPassword(10);
    $('#append_password').html('');
    $('#append_password').html(html);
    $('#suggestPassword').val('');
    $('#suggestPassword').val(password);
    //console.log(password);
  });

  $('body').on('click', '#cancelPass', function() {
    $('#password').val('');
    $('#confirm_password').val('');
    $('#suggestPassword').val('');
    $('#append_password').html('');
  });

  $('body').on('click', '#fillNow', function() {
    var pass = $('#suggestPassword').val();
    $('#password').val('');
    $('#confirm_password').val('');
    $('#password').val(pass);
    $('#confirm_password').val(pass);
    $('#suggestPassword').val('');
    $('#append_password').html('');
  });

  $("#password").keypress(function(){

    $('#append_password').html('');
      $('#confirm_password').val('');

  });

    function randomPassword(Mainlength) {
            var passres = '';
            var length = 3;
            charsets = ['0123456789','abcdefghijklmnopqrstuvwxyz','ABCDEFGHIJKLMNOPQRSTUVWXYZ'];

            function gen(length,charsets){
                var pass = "";
                for(var j = 0; j < 3; j++ )
                {
                    //var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
                    var chars = charsets[j];
                    
                    for (var x = 0; x < length; x++) {
                        var i = Math.floor(Math.random() * chars.length);
                        pass += chars.charAt(i);
                    }
                }
                return pass;
            }
            return gen(length,charsets);
        }
</script>

@include('layouts.main.footer_top')
@stop

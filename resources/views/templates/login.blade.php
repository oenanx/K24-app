@extends('templates.header')

<style type="text/css">
#loader {
	display: none;
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	background: rgba(0,0,0,0.75) url({{ asset('assets/images/loading2.gif') }}) no-repeat center center;
	z-index: 10000;
}

.vl {
  border-left: 3px dotted gray;
  height: 68%;
  position: absolute;
  top: 45;
  left: 50%;
  margin-left: -3px;
}
</style>


@section('content')

<!--begin::Login-->
<div class="login login-5 login-signin-on d-flex flex-column flex-column-fluid bg-white" id="kt_login">
<!--<div class="login login-1 login-signin-on d-flex flex-column flex-column-fluid bg-white flex-lg-row" id="kt_login">
    begin::Header-->
    <div class="login-header py-1 flex-column-auto">
        <div class="container d-flex flex-column flex-lg-row align-items-center justify-content-center justify-content-lg-between">
            <!--begin::Logo-->
				<a href="{{ url('/') }}" class="flex-column-auto py-10 py-md-0">
					<img src="{{ asset('assets/images/k24klik_logo2.png') }}" alt="logo" class="h-50px" />
				</a>
            <!--end::Logo-->
        </div>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="login-body d-flex flex-column-fluid align-items-stretch justify-content-center" align="center">
    <!--<div class="login-body d-flex flex-row-fluid justify-content-center position-relative">-->
        <div class="container row">
            <div class="col-lg-6 d-flex align-items-center">
                <!--begin::Signin-->
                <div class="card-body">

					<hr class="style1" />
                    <!--begin::Form-->
					<form id="form1" method="post" action="{{ route('login.authenticate') }}" novalidate="novalidate">
                    @csrf

                    <!--begin::Form group-->
                        <div class="form-group">
                            <div class="input-group input-group-lg input-group-solid">
                                <input type="email" id="username" name="username" required autofocus class="form-control form-control-lg form-control-solid @error('username') is-invalid @enderror font-size-h5 px-2 py-2" value="{{ request()->old('username') }}" placeholder="name@example.com" autocomplete="off" tabindex="1" />
                                <div class="input-group-append">
                                    <span id="mybutton2" class="input-group-text"><i class="fa fa-user fa-fw" aria-hidden="true"></i></span>
                                </div>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <div class="input-group input-group-lg input-group-solid">
                                <input class="form-control form-control-lg form-control-solid font-size-h5 px-2 py-2" type="password" id="password" name="password" required autocomplete="off" placeholder="Password" tabindex="2" />
                                <div class="input-group-append">
                                    <span id="mybutton" class="input-group-text"><i id="pass-status" class="fa fa-eye-slash" aria-hidden="true" onClick="showhide()"></i></span>
                                </div>
                            </div>
                        </div>
                        <!--end::Form group-->		
						
                        <!--begin::Captcha-->
						<div class="form-group row">
							<div class="col-sm-12 col-sm-12 captcha">
								<div class="input-group">
									<input id="captcha" name="captcha" type="text" class="form-control form-control-sm form-control-solid font-size-h5 px-2 py-2" placeholder="Enter Captcha" tabindex="3" />

									<div class="input-group-append">
										<span>{!! captcha_img() !!}</span><button type="button" class="btn btn-sm btn-info" id="reload" name="reload"><i class="flaticon2-refresh-arrow" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>
						</div>
						<!--end::Captcha-->
						
                        <span id="notif"></span>
                        <div class="form-group align-items-center justify-content-between mt-3 mb-0">
                            @if(\Session::has('alert'))
                                <div class="alert alert-danger">
                                    <div>{{Session::get('alert')}}</div>
                                </div>
                            @endif
                        </div>
						<hr class="style1" />
						
						<div class="form-group row">
							<div class="col-lg-6 col-lg-6 align-items-left" align="left">
								<button type="button" id="signup" name="signup" class="btn btn-light-success font-weight-bolder" tabindex="6" style="border-radius: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="flaticon2-edit" aria-hidden="true"></i>&nbsp;&nbsp;Sign Up&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
							</div>
							<div class="col-lg-6 col-lg-6 align-items-right" align="right">
								<button type="submit" id="kt_login_signin_submit" class="btn btn-light-info font-weight-bolder" tabindex="4" style="border-radius: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-sign-in-alt" aria-hidden="true"></i>&nbsp;&nbsp;Log In&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
							</div>
						</div>
						<hr class="style1" />
						<br />
						<div class="col-md-12 col-lg-12">
							<a href="{{ url('forget') }}" tabindex="5"><span>Forgotten password?</span></a>
						</div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Signin-->
            </div>
            <div class="col-lg-6 d-flex align-items-center">
                <div class="card-body">

                    <div class="col-lg-12 bgi-size-contain bgi-no-repeat bgi-position-y-center bgi-position-x-center min-h-300px mt-12 m-md-8" style="background-image: url({{ asset('assets/media/svg/illustrations/process-analyse.svg') }})"></div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Body-->
	<div id="loader"></div>

	<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl">
			<div class="modal-content">
				<div class="card">
					<form enctype="multipart/form-data" id="form2" class="form-horizontal">
					@csrf
						<div class="card-header">
							<h2 class="card-title"><b>New User Sign Up</b></h2>
						</div>
						<div class="card-body">
							<div id="modal-loader" style="display: none; text-align: center;">
								<img src="{{ asset('assets/images/ajax-loader.gif') }}">
							</div>
								<div class="card-body" align="justify">
									<div class="form-group row">			
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>Email User Name&nbsp;</label><label style="color: red;"><b>*</b></label>
												<input type="email" id="emailuser" name="emailuser" class="form-control form-control-sm form-control-solid" required placeholder="Email untuk username" />
											</div>
										</div>
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>Sure Name&nbsp;</label><label style="color: red;"><b>*</b></label>
												<input type="text" id="realname" name="realname" class="form-control form-control-sm form-control-solid" required placeholder="Nama Lengkap" maxlength="100" />
											</div>
										</div>
									</div>
									<div class="form-group row">			
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>Password&nbsp;</label><label style="color: red;"><b>*</b></label>
												<div class="input-group">
													<input type="password" class="form-control form-control-md" name="passwordnew" id="passwordnew" required placeholder="New Password" onclick="validate();" onkeyup="validate();" autocomplete="off" />
													<span class="input-group-append">
														<span type="button" id="mybutton2" class="input-group-text"><i id="pass-status2" class="fa fa-eye-slash" onclick="showhide2()"></i></span>
													</span>
												</div>
												<div id="validation-txt" style="font-size: 10pt; font-family: Calibri; color: red"></div>
											</div>
										</div>
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>Confirm Password&nbsp;</label><label style="color: red;"><b>*</b></label>
												<div class="input-group">
													<input type="password" class="form-control form-control-md" name="passwordconfirm" id="passwordconfirm" required placeholder="Confirm New Password" onclick="validate2();" onkeyup="validate2();" autocomplete="off" />
													<span class="input-group-append">
														<span type="button" id="mybutton3" class="input-group-text"><i id="pass-status3" class="fa fa-eye-slash" onclick="showhide3()"></i></span>
													</span>
												</div>
												<div id="validation-txt2" style="font-size: 10pt; font-family: Calibri; color: red"></div>
											</div>
										</div>
									</div>
									<div class="form-group row">			
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>KTP No.&nbsp;</label><label style="color: red;"><b>*</b></label>
												<input type="text" id="noktp" name="noktp" class="form-control form-control-sm form-control-solid" required placeholder="Nomor KTP" maxlength="16" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
											</div>
										</div>
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>Mobile No.&nbsp;</label><label style="color: red;"><b>*</b></label>
												<input type="text" id="nohp" name="nohp" class="form-control form-control-sm form-control-solid" required placeholder="Nomor HP" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="15" />
											</div>
										</div>
									</div>
									<div class="form-group row">			
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>Gender&nbsp;</label><label style="color: red;"><b>*</b></label>
												<select id="gender" name="gender" class="form-control form-control-sm form-control-solid" required>
													<option value="">Select One...</option>
													<option value="0">Wanita</option>
													<option value="1">Pria</option>
												</select>
											</div>
										</div>
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>Date of Birth.&nbsp;</label><label style="color: red;"><b>*</b></label>
												<div class="input-group">
													<input type="text" id="dob" name="dob" class="form-control form-control-sm form-control-solid" required placeholder="Tanggal Lahir (yyyy-mm-dd)" />
													<div class="input-group-append">
														<span class="input-group-text"><i class="fa fa-calendar"></i></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">			
										<div class="col-md-6 col-lg-6">
											<div class="form-group">
												<label>Profile Picture.&nbsp;</label><label style="color: red;"><b>*</b></label>
												<input type="file" class="form-control  form-control-sm form-control-solid" id="myPict" name="myPict" required placeholder="Upload file Foto max 1MB" accept="image/*" />
											</div>
										</div>
										<div class="col-md-6 col-lg-6">
										</div>
									</div>
								</div>
						</div>
						<div class="card-footer" align="right">
							<button type="button" class="btn btn-primary btn-lg" id="daftar" name="daftar">Sign Up</button>&nbsp;
							<a href="{{ url('/') }}"><button type="button" class="btn btn-danger btn-lg">Close</button></a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


    @include('templates.footer')
</div>
<!--end::Login-->

@endsection

@push('scripts')
<script src="{{ asset('assets/dist/js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/dist/js/jquery.dataTables.js') }}" type="text/javascript" language="javascript"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap-datepicker.js') }}" type="text/javascript" language="javascript"></script>
<script type="text/javascript" class="init">
var spinner = $('#loader');
$(document).ready(function()
{
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
});

$('#reload').click(function () {
	$.ajax({
		type: 'GET',
		url: "{{ route('login.reloadCaptcha') }}",
		success: function (data) {
			$(".captcha span").html(data.captcha);
		}
	});
});

$('body').on('focus',"#dob", function(){
	$(this).datepicker({
		format: 'yyyy-mm-dd', todayHighlight: true, inline: true
	});
});	

function showhide() {
	var passStatus = document.getElementById("pass-status");
	var x = document.getElementById("password");
	if (x.type === "password") {
		x.type = "text";
		passStatus.removeAttribute("class");
		passStatus.setAttribute("class","fa fa-eye");
	} else {
		x.type = "password";
		passStatus.removeAttribute("class");
		passStatus.setAttribute("class","fa fa-eye-slash");
	}
}

function showhide2() {
	var passStatus2 = document.getElementById("pass-status2");
	var x = document.getElementById("passwordnew");
	if (x.type === "password") {
		x.type = "text";
		passStatus2.removeAttribute("class");
		passStatus2.setAttribute("class","far fa-eye");
	} else {
		x.type = "password";
		passStatus2.removeAttribute("class");
		passStatus2.setAttribute("class","far fa-eye-slash");
	}
}

function showhide3() {
	var passStatus3 = document.getElementById("pass-status3");
	var x = document.getElementById("passwordconfirm");
	if (x.type === "password") {
		x.type = "text";
		passStatus3.removeAttribute("class");
		passStatus3.setAttribute("class","far fa-eye");
	} else {
		x.type = "password";
		passStatus3.removeAttribute("class");
		passStatus3.setAttribute("class","far fa-eye-slash");
	}
}

function validate(){
	var validationField = document.getElementById('validation-txt');
	var password = document.getElementById('passwordnew');

	var content = password.value;
	var errors = [];
	//console.log(content);
	if (content.length < 8) {
		errors.push("* Your password must be at least 8 characters.<br />"); 
	}
	if (content.search(/[a-z]/i) < 0) {
		errors.push("* Your password must contain at least one letter.<br />");
	}
	if (content.search(/[A-Z]/) < 0) {
		errors.push("* Your password must contain at least one uppercase letter.<br />");
	}
	if (content.search(/[0-9]/i) < 0) {
		errors.push("* Your password must contain at least one digit.<br />"); 
	}
	if (content.search(/[!@#$%^&.:%|*]/i) < 0) {
		errors.push("* Your password must contain at least one special character.<br />"); 
	}
	if (errors.length > 0) {
		validationField.innerHTML = errors.join('');

		return false;
	}
	validationField.innerHTML = errors.join('');
	return true;
}

function validate2(){
	var validationField2 = document.getElementById('validation-txt2');
	//var password = document.getElementById('passwordnew');
	//var cpassword = document.getElementById('passwordconfirm');

	var errors = [];
	if ($('#passwordconfirm').val() !== $('#passwordnew').val()) {
		errors.push("Your confirmation password doesn't match! "); 
	}

	if (errors.length > 0) {
		validationField2.innerHTML = errors.join('');

		return false;
	}
	validationField2.innerHTML = errors.join('');
	return true;
}

$('#signup').click(function () 
{     
	var id = $(this).data("id");
	$('.help-block').empty(); // clear error string
	$.get("{{ url('Home/new_user') }}", function (data) 
	{
		$('.modal-dialog').css({width:'95%',height:'100%', 'max-height':'100%'});

		$('#view-modal').modal('show');
	});
});

$('#daftar').on("click", function ()
{
	if (document.getElementById("emailuser").value.trim() == "")
	{
		Swal.fire("Warning !", "You must type the email address for login. !!!", "error");
		$('#emailuser').focus();
		return false;
	}
	
	if (document.getElementById("realname").value.trim() == "")
	{
		Swal.fire("Warning !", "You must type your real name. !!!", "error");
		$('#realname').focus();
		return false;
	}
	
	if (document.getElementById("passwordnew").value.trim() == "")
	{
		Swal.fire("Warning !", "You must type the new password. !!!", "error");
		$('#passwordnew').focus();
		return false;
	}
	
	if (document.getElementById("passwordconfirm").value.trim() == "")
	{
		Swal.fire("Warning !", "You must type the confirmation password. !!!", "error");
		$('#passwordconfirm').focus();
		return false;
	}
	
	if (document.getElementById("noktp").value.trim() == "")
	{
		Swal.fire("Warning !", "You must type your KTP number. !!!", "error");
		$('#noktp').focus();
		return false;
	}
	
	if (document.getElementById("nohp").value.trim() == "")
	{
		Swal.fire("Warning !", "You must type the handphone number. !!!", "error");
		$('#nohp').focus();
		return false;
	}
	
	if (document.getElementById("gender").value.trim() == "")
	{
		Swal.fire("Warning !", "You must choose your gender. !!!", "error");
		$('#gender').focus();
		return false;
	}
	
	if (document.getElementById("dob").value.trim() == "")
	{
		Swal.fire("Warning !", "You must choose your date of birth. !!!", "error");
		$('#dob').focus();
		return false;
	}
	
	if (document.getElementById("myPict").value.trim() == "")
	{
		Swal.fire("Warning !", "You must choose your picture file to upload. !!!", "error");
		$('#myPict').focus();
		return false;
	}
	
	var email		= $('#emailuser').val();
	var password	= $('#passwordnew').val();
	var realname	= $('#realname').val();
	var no_hp		= $('#nohp').val();
	var tgl_lahir	= $('#dob').val();
	var sex			= $('#gender').val();
	var no_ktp		= $('#noktp').val();
	var myPict		= $('#myPict')[0].files;
	
	var fd = new FormData();

	fd.append('email', email);
	fd.append('password', password);
	fd.append('realname', realname);
	fd.append('no_hp', no_hp);
	fd.append('tgl_lahir', tgl_lahir);
	fd.append('sex', sex);
	fd.append('no_ktp', no_ktp);
	fd.append('myPict', myPict[0]);

	spinner.show();

	$.ajax({
		url : "{{ url('Home/daftar') }}",
		cache: false,
		contentType: false,
		processData: false,
		method : "POST",
		data : fd,
		dataType : 'json',
		success: function(data)
		{
			spinner.hide();
			if (data.success)
			{
				$('#view-modal').hide();
				$('#form2')[0].reset();
				$("#notif").html('<div class="alert alert-success fade show" style="display: none;" role="alert"><h3><i class="icon-lg flaticon2-check-mark"></i>&nbsp;&nbsp;<b>Done. Data anda sudah terdaftar...!!!</b>&nbsp;&nbsp;<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="window.location.reload();"><i class="ki ki-close"></i></button></h3></div>').show();
				setTimeout(function () { $('#notif').hide(); }, 90000);
				window.location="{{ url('/') }}";
			}
			else if (data.errorfile)
			{
				$('#view-modal').hide();
				$('#form2')[0].reset();
				$("#notif").html('<div class="alert alert-danger fade show" style="display: none;" role="alert"><h3><i class="icon-lg flaticon-danger"></i>&nbsp;&nbsp;<b>Error. File image melebihi 1 MB !!!</b>&nbsp;&nbsp;<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="window.location.reload();"><i class="ki ki-close"></i></button></h3></div>').show();
				setTimeout(function () { $('#notif').hide(); }, 90000);
				window.location="{{ url('/') }}";
			}
			else if (data.erroremail)
			{
				$('#view-modal').hide();
				$('#form2')[0].reset();
				$("#notif").html('<div class="alert alert-danger fade show" style="display: none;" role="alert"><h3><i class="icon-lg flaticon-danger"></i>&nbsp;&nbsp;<b>Error. Email user sudah ada !!!</b>&nbsp;&nbsp;<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="window.location.reload();"><i class="ki ki-close"></i></button></h3></div>').show();
				setTimeout(function () { $('#notif').hide(); }, 90000);
				window.location="{{ url('/') }}";
			}
			else
			{
				$('#view-modal').hide();
				$('#form2')[0].reset();
				//$('#idc').val(data.success);
				$("#notif").html('<div class="alert alert-danger fade show" style="display: none;" role="alert"><h3><i class="icon-lg flaticon-danger"></i>&nbsp;&nbsp;<b>Error. Ada error lain, silahkan hubungi cs !!!</b>&nbsp;&nbsp;<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="window.location.reload();"><i class="ki ki-close"></i></button></h3></div>').show();
				setTimeout(function () { $('#notif').hide(); }, 90000);
				window.location="{{ url('/') }}";
			}
		}
	});
});

var btn = KTUtil.getById("kt_login_signin_submit");
KTUtil.addEvent(btn, "click", function() 
{
	KTUtil.btnWait(btn, "spinner spinner-right spinner-white pr-15", "Please wait");
	setTimeout(function() {
		KTUtil.btnRelease(btn);
	}, 5000);
});

</script>
@endpush
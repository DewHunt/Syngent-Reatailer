@extends('layouts.app')

@section('content')
<style type="text/css">
.commonFontSize {
	font-size: 15px;
}
.beforeLogo {
	margin:0 auto;
}
.beforelogin {
	padding: 0px 20px;
	margin: 0 auto;
	background: #eee;
	border-radius: 15px;
	box-shadow: -10px 10px 10px -10px;
	margin-top:10px
}
.aferFormPT{
	padding-top: 15px;
}
.custom-peer-forgot-text{
	margin-left: 12px;
}
/* 
  ##Device = Desktops
  ##Screen = 1281px to higher resolution desktops
*/
@media only screen and (min-width: 1300px) {
     .login_mobile_margin .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 15%;
    flex: 0 0 15%;
    max-width: 15%;
}
}
@media only screen and (max-width: 3700px) {
   .login_mobile_margin .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
}  
}
@media only screen and (max-width: 3500px) {
   .login_mobile_margin .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 20%;
    flex: 0 0 20%;
    max-width: 20%;
}  
}
@media only screen and (max-width: 2500px) {
    .login_mobile_margin .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
}
}
@media only screen and (max-width: 2100px) {
     .login_mobile_margin .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
}
}
@media only screen and (max-width: 1600px) {
     .login_mobile_margin .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
}
}
@media only screen and (max-width: 1366px) {
    .login_mobile_margin .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 33.33333%;
    flex: 0 0 33.33333%;
    max-width: 33.33333%;
}

}


@media (min-width: 1281px) {
  /*.example {background: blue;}*/
  .commonFontSize {
		font-size: 15px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 0px 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:10px
	}
	.aferFormPT{
		padding-top: 15px;
	}
	.custom-peer-forgot-text{
		margin-left: 12px;
	}
}

/* 
  ##Device = Laptops, Desktops
  ##Screen = B/w 1025px to 1280px
*/

@media (min-width: 1025px) and (max-width: 1280px) {
 /* .example {background: pink;}*/
  .commonFontSize {
		font-size: 15px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 0px 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:10px
	}
	.aferFormPT{
		padding-top: 15px;
	}
	.col-md-4 {
		-webkit-box-flex: 0;
		-ms-flex: 0 0 33.33333%;
		flex: 0 0 33.33333%;
		max-width: 100% !important;
	}
	.custom-peer-greed-text {
		margin-left: 0px;
	}
}

/* 
  ##Device = Tablets, Ipads (portrait)
  ##Screen = B/w 768px to 1024px
*/

@media (min-width: 768px) and (max-width: 1024px) {
  /*.example {background: orange;}*/
  .commonFontSize {
		font-size: 36px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 0px 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:10px
	}
	.aferFormPT{
		padding-top: 15px;
	}
	.col-md-4 {
		-webkit-box-flex: 0;
		-ms-flex: 0 0 33.33333%;
		flex: 0 0 33.33333%;
		max-width: 100% !important;
	}
	.form-control {
		height: 80px;
		font-size: 36px;
	}
	.checkbox label::after, .checkbox label::before {
	    display: inline-block;
	    position: absolute;
	    width: 45px;
	    height: 45px;
	    left: 0;
	    top: 50%;
	    -webkit-transform: translateY(-50%);
	    -ms-transform: translateY(-50%);
	    transform: translateY(-50%);
	    margin-left: -12px;
	}
	.custom-peer-greed-text {
	  margin-left: 30px;
	}
	.login_mobile_margin{
	    margin-top:400px;
	}
}

/* 
  ##Device = Tablets, Ipads (landscape)
  ##Screen = B/w 768px to 1024px
*/

@media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  /*.example {background: yellow;}*/
  .col-md-4 {
		-webkit-box-flex: 0;
		-ms-flex: 0 0 33.33333%;
		flex: 0 0 33.33333%;
		max-width: 100% !important;
	}
	.form-control {
		height: 80px;
		font-size: 36px;
	}
	.checkbox label::after, .checkbox label::before {
	    display: inline-block;
	    position: absolute;
	    width: 25px;
	    height: 25px;
	    left: 0;
	    top: 50%;
	    -webkit-transform: translateY(-50%);
	    -ms-transform: translateY(-50%);
	    transform: translateY(-50%);
	    margin-left: -12px;
	}
	.custom-peer-greed-text {
	  margin-left: 30px;
	}
	.login_mobile_margin{
	    margin-top:400px;
	}
}

/* 
  ##Device = Low Resolution Tablets, Mobiles (Landscape)
  ##Screen = B/w 481px to 767px
*/

@media (min-width: 481px) and (max-width: 767px) {
	/*.example {background: orange;}*/
  .commonFontSize {
		font-size: 15px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 0px 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:10px
	}
	.aferFormPT{
		padding-top: 15px;
	}
	.col-md-4 {
		-webkit-box-flex: 0;
		-ms-flex: 0 0 33.33333%;
		flex: 0 0 33.33333%;
		max-width: 100% !important;
	}
	.form-control {
		height: 80px;
		font-size: 36px;
	}
	.checkbox label::after, .checkbox label::before {
	    display: inline-block;
	    position: absolute;
	    width: 25px;
	    height: 25px;
	    left: 0;
	    top: 50%;
	    -webkit-transform: translateY(-50%);
	    -ms-transform: translateY(-50%);
	    transform: translateY(-50%);
	    margin-left: -12px;
	}
	.login_mobile_margin{
	    margin-top:200px;
	}
}

/* 
  ##Device = Most of the Smartphones Mobiles (Portrait)
  ##Screen = B/w 320px to 479px
*/
@media (min-width: 320px) and (max-width: 480px) {
	/*.example {background: orange;}*/
  .commonFontSize {
		font-size: 15px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding:0px 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:10px
	}
	.aferFormPT{
		padding-top: 15px;
	}
	.col-md-4 {
		-webkit-box-flex: 0;
		-ms-flex: 0 0 33.33333%;
		flex: 0 0 33.33333%;
		max-width: 100% !important;
	}
	.checkbox label::after, .checkbox label::before {
	    display: inline-block;
	    position: absolute;
	    width: 25px;
	    height: 25px;
	    left: 0;
	    top: 50%;
	    -webkit-transform: translateY(-50%);
	    -ms-transform: translateY(-50%);
	    transform: translateY(-50%);
	    margin-left: -12px;
	}
	.login_mobile_margin{
	    margin-top:200px;
	}
}
</style>

@php
$loginUserEmail = $loginUserEmpId = $loginUserPassword = $loginUserRemember= "";
if(isset($_COOKIE["loginUserEmail"]))  { 
    $loginUserEmail =  $_COOKIE["loginUserEmail"]; 
} 
if(isset($_COOKIE["loginUserEmpId"])) { 
    $loginUserEmpId = $_COOKIE["loginUserEmpId"]; 
}
if(isset($_COOKIE["loginUserPassword"])) { 
    $loginUserPassword =  $_COOKIE["loginUserPassword"]; 
}
if(isset($_COOKIE["loginUserRemember"])) { 
    $loginUserRemember =  $_COOKIE["loginUserRemember"]; 
}  
@endphp

<div class="col-12 col-md-12 col-sm-12 col-xs-12 peer pX-40 pY-50 h-100 bgc-white pos-r minMobWidth login_mobile_margin">
		<div class="text-center beforeLogo">
			<div class="bgc-white bdrs-50p pos-r logowh">
				<img class="" src="{{asset('public/admin/images/syngenta_logo.png') }}" alt="Syngenta" height="100">
			</div>
			<h3 style="color:#000000">Retail Management System</h3>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 beforelogin">	
			<form method="POST" action="{{ route('login') }}">
			{{ csrf_field() }}
				<div class="form-group aferFormPT">
				    @if(count( $errors ) > 0)
						@foreach ($errors->all() as $error)
							<span style="color:red"><strong>{{ $error }}</strong></span>
						@endforeach
					@endif
					<label class="text-normal text-dark commonFontSize">E-Mail Address || Employee ID</label>
					<input type="text" class="form-control" name="login" id="login"  placeholder="name@example.com | ex:12345" value="{{ ($loginUserEmpId) ? $loginUserEmpId : $loginUserEmail  }}" required="" autofocus>
					
					@error('login')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
		            										
				</div>
				<div class="form-group">
					<label class="text-normal text-dark commonFontSize">Password</label>
					<input type="password" id="password" name="password" value="{{ ($loginUserPassword) ? $loginUserPassword: "" }}" class="form-control " required="" autocomplete="current-password">
					
					@error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>
				<div class="form-group">
					<div class="peers ai-c jc-sb fxw-nw">
						<div class="peer">
							<div class="checkbox checkbox-circle checkbox-info peers ai-c">
								<input class="peer" type="checkbox" name="remember" id="remember" {{ ($loginUserRemember) == 1 ? 'checked' : '' }}>
								<label class="peers peer-greed js-sb ai-c">
								    <span class="peer peer-greed custom-peer-greed-text commonFontSize">Remember Me</span>
								</label>
							</div>
								<p class="log-forget-text commonFontSize"> <i class="fa fa-key" aria-hidden="true"></i>
								<a href="{{ route('password.request') }}">
									<span class="custom-peer-forgot-text">Forgot password?</span>
								</a>
							</p>
						</div>
						<div class="peer"><button class="btn btn-primary clogbtn commonFontSize">Login</button></div>
					</div>
				</div>
			</form>
		</div>
</div>
@endsection

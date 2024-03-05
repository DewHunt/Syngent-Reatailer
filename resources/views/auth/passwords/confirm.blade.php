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
	padding: 20px;
	margin: 0 auto;
	background: #eee;
	border-radius: 15px;
	box-shadow: -10px 10px 10px -10px;
	margin-top:30px
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

@media (min-width: 1281px) {
  .example {background: blue;}
  .commonFontSize {
		font-size: 15px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:30px
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
 .example {background: pink;}
  .commonFontSize {
		font-size: 15px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:30px
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
  .example {background: orange;}
  .commonFontSize {
		font-size: 36px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:30px
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
	.mobbtn {
		height: 60px;
		font-size: 30px;
	}
}

/* 
  ##Device = Tablets, Ipads (landscape)
  ##Screen = B/w 768px to 1024px
*/

@media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .example {background: yellow;}
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
	.mobbtn {
		height: 60px;
		font-size: 30px;
	}
}

/* 
  ##Device = Low Resolution Tablets, Mobiles (Landscape)
  ##Screen = B/w 481px to 767px
*/

@media (min-width: 481px) and (max-width: 767px) {
	.example {background: orange;}
  .commonFontSize {
		font-size: 15px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:30px
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
	.mobbtn {
		height: 60px;
		font-size: 30px;
	}
}

/* 
  ##Device = Most of the Smartphones Mobiles (Portrait)
  ##Screen = B/w 320px to 479px
*/
@media (min-width: 320px) and (max-width: 480px) {
	.example {background: orange;}
  .commonFontSize {
		font-size: 15px;
	}
	.beforeLogo {
		margin:0 auto;
	}
	.beforelogin {
		padding: 20px;
		margin: 0 auto;
		background: #eee;
		border-radius: 15px;
		box-shadow: -10px 10px 10px -10px;
		margin-top:30px
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
	.mobbtn {
		height: 60px;
		font-size: 30px;
	}
}
</style>
<div class="example"></div>
<div class="col-12 col-md-12 col-sm-12 col-xs-12 peer pX-40 pY-50 h-100 bgc-white pos-r minMobWidth">
	<div class="text-center beforeLogo">
		<div class="bgc-white bdrs-50p pos-r logowh">
			<img class="" src="{{asset('public/admin/images/syngenta_logo.png') }}" alt="Syngenta" height="100">
		</div>
		<h3 style="color:#000000">Syngenta Retail Management System</h3>
	</div>
	<div class="col-md-4 col-sm-12 col-xs-12 beforelogin">
			@if (session('status'))
			<div class="alert alert-success" role="alert">
				{{ session('status') }}
			</div>
			@endif
			<h4 class="fw-300 c-grey-900 mB-40">{{ __('Confirm Password') }}</h4>
			{{ __('Please confirm your password before continuing.') }}
			
			<form method="POST" action="{{ route('password.confirm') }}">
			@csrf
				<div class="form-group aferFormPT">
					<label class="text-normal text-dark commonFontSize">{{ __('Password') }}</label>
					<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
					@error('password')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror									
				</div>
				@if (Route::has('password.request'))
				<p> <i class="fa fa-key" aria-hidden="true"></i>
					<a href="{{ route('password.request') }}">Forgot password?</a>
				</p>
				@endif
				<div class="form-group">
					<div class="peers ai-c jc-sb fxw-nw">
						<div class="peer"><button class="btn btn-primary mobbtn">{{ __('Confirm Password') }}</button></div>
					</div>
				</div>
			</form>
		</div>
</div>
@endsection

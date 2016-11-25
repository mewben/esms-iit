@extends('layouts.default')

@section('content')
<style>
	body {
		/* background-image: url('bgs/{{$bg}}.jpg'); */
		background-size: cover;
		background-repeat: no-repeat;
	}
</style>
<main class="main">
	<section class="login-box">
		<form action="/login" class="login-form" method="post">
			<div class="text-center">
				<img src="/assets/images/bisu-logo-md.png" width="150" alt="BISU LOGO">
			</div>
			@if (Session::has('err'))
			<div class="alert alert-danger">Login Incorrect!</div>
			@endif
			<div class="wrap">
				<input type="text" name="username" placeholder="Username" class="form-control" autofocus="autofocus" required>
			</div>
			<div class="wrap">
				<input type="password" name="password" placeholder="Password" class="form-control" required>
			</div>
			<button type="submit" class="btn btn-warning"><i class="fa fa-automobile fa-fw"></i> Log in</button>
			<div>
				<h2>eSMS ext <em class="text-muted">v0.3.3</em></h2>
				<em class="text-muted">Bohol Island State University - Main Campus</em>
			</div>
		</form>
	</section>
</main>
@stop
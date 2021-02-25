<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PACIFIC MEDIHUB | ADMIN DASHBOARD</title>
  <link rel="icon" type="image/png" href="{{ asset('theme/images/fav.png') }}" />
</head>

<style type="text/css">
	html, body {
  		height: 100%;
  		margin: 0;
	}
	.error-page {
	  background-image: url('theme/images/background_image/error.jpg');
	  height: 100%;
	  background-position: center;
	  background-size: cover;
	  position: relative;
	  color: white;
	  font-family: "Courier New", Courier, monospace;
	  font-size: 25px;
	}
	.middle {
	    width: 340px;
	    position: absolute;
	    top: 420px;
	    left: 470px;
	    padding: 30px 0;
	    cursor: pointer;
	}
</style>

<body>
	<div class="error-page">
		<a class="middle" href="{{route('admin.dashboard')}}"></a>
	</div>
</body>
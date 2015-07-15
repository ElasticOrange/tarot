<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield('title')</title>
		<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="/css/main.css">
	</head>
	<body>
		@include('menu')
        <div class="container">
			<div class="panel panel-default">
				<div class="panel-body main-panel">
		            @yield('content')
				</div>
			</div>
        </div>
	</body>
	@include('javascripts')
</html>

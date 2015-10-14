<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield('title')</title>
		<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="/js/lib/bscombobox/css/bootstrap-combobox.css">
		<link rel="stylesheet" type="text/css" href="/css/main.css">
		<link rel="stylesheet" type="text/css" href="/js/lib/DataTables/DataTables-1.10.9/css/dataTables.bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="/js/lib/DataTables/Responsive-1.0.7/css/responsive.bootstrap.min.css"/>

	</head>
	<body>
		@include('menu')
        <div class="container">
			<div class="panel panel-default">
				<div class="panel-body main-panel">
		            @yield('content')
				</div>
			</div>
			@include('_messageboxes')
        </div>

        <div id="loader" class="overlay" style="display: none">
        	<div class="loader-background"></div>
        	<div class="loader-container">
        		<img src="/images/ajax-loader.gif" width="100px"/>
        	</div>
        </div>

        @yield('boxes')
	</body>

	@include('javascripts')
</html>

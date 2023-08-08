<!DOCTYPE html>
<html>
<head>
	<title>TALMS | @yield('title')</title>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">

<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('plugins/animation/css/animate.min.css') }}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('users.userslayouts.library')
	@include('users.userslayouts.header_public')
	@yield('content')


</body>
</html>


<script src="{{ asset('js/custom.js') }}"></script>


<!--
<script src="{{ asset('js/vendor-all.min.js') }}"></script>
-->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!--
<script src="{{ asset('js/pcoded.min.js') }}"></script>
-->
  

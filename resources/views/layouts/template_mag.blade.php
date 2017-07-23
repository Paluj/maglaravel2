<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<link href="{{ asset('css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('css/ui.fancytree.css') }}" rel="stylesheet">	

	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>

<body>
	
    <nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="">Laravel mag</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				@if (Auth::guest())
					<form class="navbar-form navbar-right" method="POST" action="{{ route('login') }}">
						{{ csrf_field() }}
						Авторизация:
						<input name="email" type="email" class="form-control" placeholder="email" required="required">
						<input name="password" type="password" class="form-control" placeholder="Пароль" required="required">
						<input type="submit" class="form-control" value="Войти">
					</form>
				@else
					{{ Auth::user()->name }}
					<form class="navbar-form navbar-right" method="POST" action="{{ route('logout') }}">
						{{ csrf_field() }}
						<input type="submit" class="form-control" value="Выйти">
					</form>			
				@endif
			</div>
		</div>
    </nav>

    <div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				@yield('content_tree')
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				@yield('goods_place')
			</div>
		</div>
    </div>

	<script src="{{ asset('js/jquery-ui.custom.js') }}"></script>
	<script src="{{ asset('js/jquery.fancytree.js') }}"></script>
	@if (!Auth::guest() && Auth::user()->admin)
		<script src="{{ asset('js/adm_js.js') }}"></script>
		@if (session('status'))
			<script> 
				@if (session('status') == "Error PUT")
					alert("Ошибка добовления!!!");
				@elseif (session('status') == "add_category ok")
					alert("Категория добавлена");
				@elseif (session('status') == "add_goods ok")
					alert("Товар добавлен");
				@endif
			</script>
		@endif
	@else
		<script src="{{ asset('js/js.js') }}"></script>
	@endif	
</body>
</html>

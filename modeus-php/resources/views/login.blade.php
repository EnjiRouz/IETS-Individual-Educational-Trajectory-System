<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="stylesheet" href="{{asset("css/login.css")}}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
</head>

<body>
<div class="login-page">
    <div class="form">
        <form class="login-form" method="post" action="{{ route('admin') }}">
            {{ csrf_field() }}
            <p>Панель администратора</p>

            <input id="username" value="{{old("username")}}" type="text" name="username" placeholder="Имя пользователя"/>
            @error('username')
                <span role="alert">
                    <strong>{{$message}}</strong>
                </span>
            @enderror

            <input id="password" value="{{old("password")}}" type="password" name="password" placeholder="Пароль"/>
            @error('password')
            <span role="alert">
                    <strong>{{$message}}</strong>
                </span>
            @enderror

            <button type="submit">Войти</button>
        </form>
    </div>
</div>
</body>
</html>

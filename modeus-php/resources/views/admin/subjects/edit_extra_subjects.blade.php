<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="stylesheet" href={{asset("laracrud/css/bootstrap.min.css")}}>
    <link rel="stylesheet" href="{{asset("css/login.css")}}">
    <link rel="stylesheet" href={{asset("css/admin.css")}}>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
</head>
<body>

<div class="side-menu open pinned" data-wui-theme="dark">
    <ul class="side-menu-items">
        <li>
            <a href="{{route('admin')}}" class="side-menu-item">
                <span class="box-title">Статистика</span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.basic_subjects.index')}}" class="side-menu-item">
                <span class="box-title">Основные дисциплины</span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.extra_subjects.index')}}" class="side-menu-item active">
                <span class="box-title">Доп. дисциплины</span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.occupations.index')}}" class="side-menu-item">
                <span class="box-title">Профессии</span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.curriculum.index')}}"  class="side-menu-item">
                <span class="box-title">Учебные планы</span>
            </a>
        </li>
    </ul>
</div>
<div class="content">
    @include("laracrud.includes.alerts")
    <div class="card-header">Редактирование {{$extra_subject->name}}</div>
    <div class="form big">
        <form class="login-form" action="{{route('admin.extra_subjects.update', ['extra_subject'=>$extra_subject->id])}}" method="POST">
            @csrf
            {{method_field("PUT")}}
            <label for="name">Название</label><input id="name" value="{{$extra_subject->name}}" type="text" name="name" placeholder='{{$extra_subject->name}}'/>
            <label for="description">Описание</label><input id="description" value="{{$extra_subject->description}}" type="text" name="description" placeholder='{{$extra_subject->description}}'/>
            <label for="creditUnits">Количество зачётных единиц</label><input id="creditUnits" value="{{$extra_subject->creditUnits}}" type="number" name="creditUnits" placeholder='{{$extra_subject->creditUnits}}'/>
            <label for="category">Категория</label><input id="category" value="{{$extra_subject->category}}" type="text" name="category" placeholder='{{$extra_subject->category}}'/>
            <div class="form-check">Форма контроля:
                <br>
                <label for="formOfControl1">зачёт</label><input id="formOfControl1" value="зачёт" type="radio" name="formOfControl"
                @if($extra_subject->formOfControl=="зачёт")
                    {{"checked"}}/>
                @endif
                <label for="formOfControl2">экзамен</label><input id="formOfControl2" value="экзамен" type="radio" name="formOfControl"
                @if($extra_subject->formOfControl=="экзамен")
                    {{"checked"}}/>
                @endif
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Сохранить</button>
        </form>
    </div>
</div>
</body>
</html>

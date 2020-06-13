<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="stylesheet" href={{asset("css/database_tables.css")}}>
    <link rel="stylesheet" href={{asset("laracrud/css/bootstrap.min.css")}}>
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
            <a href="{{route('admin.basic_subjects.index')}}"  class="side-menu-item active">
                <span class="box-title">Основные дисциплины</span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.extra_subjects.index')}}" class="side-menu-item">
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
    <div class="card-header">Основные дисциплины</div>
    <div class="card-body">
    <div class="database-table-container">
        <a href="{{route('admin.basic_subjects.create')}}">
            <button type="button" class="btn btn-primary">Создать новую дисциплину</button>
        </a>
        <table class="database-table">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Название</th>
                <th scope="col">Описание</th>
                <th scope="col">Количество зачётных единиц</th>
                <th scope="col">Форма контроля</th>
                <th scope="col" colspan="2">Доступные действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($basic_subjects as $basic_subject)
                <tr>
                    <td>{{$basic_subject->id}}</td>
                    <td>{{$basic_subject->name}}</td>
                    <td>{{$basic_subject->description}}</td>
                    <td>{{$basic_subject->creditUnits}}</td>
                    <td>{{$basic_subject->formOfControl}}</td>
                    <td><a href="{{route('admin.basic_subjects.edit', $basic_subject->id)}}">
                            <button type="button" class="btn btn-primary btn-sm">Редактировать</button>
                        </a>
                    </td>
                    <td>
                        <form action="{{route('admin.basic_subjects.destroy', $basic_subject->id)}}" method="POST">
                            @csrf
                            {{method_field("DELETE")}}
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>

</body>
</html>

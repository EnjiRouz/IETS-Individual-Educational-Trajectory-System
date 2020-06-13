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
            <a href="{{route('admin.basic_subjects.index')}}"  class="side-menu-item">
                <span class="box-title">Основные дисциплины</span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.extra_subjects.index')}}" class="side-menu-item">
                <span class="box-title">Доп. дисциплины</span>
            </a>
        </li>

        <li>
            <a href="{{route('admin.occupations.index')}}" class="side-menu-item active">
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
    @inject('provider', 'App\Http\Controllers\Admin\OccupationsController')
    <div class="card-header">Доступные профессии</div>
    <div class="card-body">
    <div class="database-table-container">
        <a href="{{route('admin.occupations.create')}}">
            <button type="button" class="btn btn-primary">Создать новую профессию</button>
        </a>
        <table class="database-table">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Название</th>
                <th scope="col">Описание</th>
                <th scope="col">Рекомендуемые курсы</th>
                <th scope="col" colspan="2">Доступные действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($occupations as $occupation)
                <tr>
                    <td>{{$occupation->id}}</td>
                    <td>{{$occupation->name}}</td>
                    <td>{{Str::limit($occupation->description, 100)}}</td>
                    <td>{{Str::limit($provider::getExtraSubjectsNames($occupation->occupation_extra_subjects_preset), 100)}}</td>
                    <td><a href="{{route('admin.occupations.edit', $occupation->id)}}">
                            <button type="button" class="btn btn-primary btn-sm">Редактировать</button>
                        </a>
                    </td>
                    <td>
                        <form action="{{route('admin.occupations.destroy', $occupation->id)}}" method="POST">
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

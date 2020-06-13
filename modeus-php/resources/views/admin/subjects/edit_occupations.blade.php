<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="stylesheet" href={{asset("laracrud/css/bootstrap.min.css")}}>
    <link rel="stylesheet" href="{{asset("css/login.css")}}">
    <link rel="stylesheet" href={{asset("css/admin.css")}}>

    <script type="text/javascript" src={{asset("js/occupationsService.js")}} defer></script>

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
    <div class="card-header">Редактирование {{$occupation->name}}</div>
    <div class="form big">
        <form class="login-form" action="{{route('admin.occupations.update', ['occupation'=>$occupation->id])}}" method="POST">
            @csrf
            {{method_field("PUT")}}
            <label for="name">Название</label><input id="name" value="{{$occupation->name}}" type="text" name="name" placeholder='{{$occupation->name}}'/>
            <label for="description">Описание</label><input id="description" value="{{$occupation->description}}" type="text" name="description" placeholder='{{$occupation->description}}'/>
            <label for="average">Средняя заработная плата</label><input id="average" value="{{$salary->average}}" type="number" name="average" placeholder='50000'/>
            <label for="highest">Высшая заработная плата</label><input id="highest" value="{{$salary->highest}}" type="number" name="highest" placeholder='70000'/>
            <label for="year_of_experience">Заработная плата с годом опыта работы</label><input id="year_of_experience" value="{{$salary->year_of_experience}}" type="number" name="year_of_experience" placeholder='40000'/>
            <label for="without_experience">Заработная плата без опыта работы</label><input id="without_experience" value="{{$salary->without_experience}}" type="number" name="without_experience" placeholder='20000'/>
            <label for="stats">График в виде html-элемента</label><input id="stats" value="{{$salary->stats}}" type="text" name="stats" placeholder='<svg viewBox="0 0 1000 230" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><style type="text/css"></style><rect x="150" y="30" width="700" height="50" rx="0" fill="#A3C1F3"></rect><rect x="150" y="0" width="3" height="30" rx="0" fill="#A3C1F3"></rect><rect x="847" y="0" width="3" height="30" rx="0" fill="#A3C1F3"></rect><rect x="267" y="30" width="353" height="50" rx="0" fill="#967BCE"></rect><rect x="267" y="80" width="3" height="60" rx="0" fill="#967BCE"></rect><rect x="617" y="80" width="3" height="60" rx="0" fill="#967BCE"></rect><rect x="443" y="30" width="3" height="170" rx="0" fill="#F06E9C"></rect><g transform="translate(157, 17)" font-size="20" font-family="PT Sans" fill="#A3C1F3"><text>10 перцентиль 25k руб.</text></g><g transform="translate(843, 17)" font-size="20" font-family="PT Sans" fill="#A3C1F3"><text text-anchor="end">90 перцентиль 85k руб.</text></g><g transform="translate(260, 105)" font-size="20" font-family="PT Sans" fill="#967BCE"><text text-anchor="end">25 перцентиль</text></g><g transform="translate(260, 130)" font-size="20" font-family="PT Sans" fill="#967BCE"><text text-anchor="end">35k руб.</text></g><g transform="translate(627, 105)" font-size="20" font-family="PT Sans" fill="#967BCE"><text>75 перцентиль</text></g><g transform="translate(627, 130)" font-size="20" font-family="PT Sans" fill="#967BCE"><text>65k руб.</text></g><g transform="translate(454, 170)" font-size="20" font-family="PT Sans" fill="#F06E9C"><text>Медиана</text></g><g transform="translate(454, 195)" font-size="20" font-family="PT Sans" fill="#F06E9C"><text>50k руб.</text></g></svg>'
            />
            <br>
            <div class="form-check">Направления, на которых можно освоить профессию:
                <p></p>
                @foreach($majors as $major)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$major->id}}" type="checkbox" name="majors[]" onchange="checkChosenMajors([{{$occupation->occupation_extra_subjects_preset}}])"
                            {{$occupation->hasAnyMajor([$major->id])?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$major->name}}</label>
                        </div>
                    </div>
                @endforeach

                <script>
                    document.addEventListener('DOMContentLoaded', () =>{
                        checkChosenMajors([{{$occupation->occupation_extra_subjects_preset}}]);
                    })
                </script>

            </div>
            <div id="subjects"></div>
            <button type="submit" class="btn btn-primary btn-sm">Сохранить</button>
        </form>
    </div>
</div>
</body>
</html>

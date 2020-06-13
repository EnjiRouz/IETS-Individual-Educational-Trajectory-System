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
            <a href="{{route('admin.occupations.index')}}" class="side-menu-item">
                <span class="box-title">Профессии</span>
            </a>
        </li>
        <li>
            <a href="{{route('admin.curriculum.index')}}" class="side-menu-item active">
                <span class="box-title">Учебные планы</span>
            </a>
        </li>
    </ul>
</div>
<div class="content">
    @include("laracrud.includes.alerts")
    <div class="card-header">Редактирование учебного плана для направления {{$major->name}}</div>
    <div class="form big">
        <form class="login-form" action="{{route('admin.curriculum.update', ['curriculum'=>$curriculum->curriculum_id])}}" method="POST">
            @csrf
            {{method_field("PUT")}}

            <div class="form-check">Предметы 1 семестра:
                <p></p>
                @foreach($basic_subjects as $basic_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$basic_subject->id}}" type="checkbox" name="basic_subjects_1[]"
                            {{$basic_subject->isInCurriculum($curriculum->curriculum_id,$basic_subject->id,1)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$basic_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-check">Предметы 2 семестра:
                <p></p>
                @foreach($basic_subjects as $basic_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$basic_subject->id}}" type="checkbox" name="basic_subjects_2[]"
                                {{$basic_subject->isInCurriculum($curriculum->curriculum_id,$basic_subject->id,2)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$basic_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-check">Предметы 3 семестра:
                <p></p>
                @foreach($basic_subjects as $basic_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$basic_subject->id}}" type="checkbox" name="basic_subjects_3[]"
                                {{$basic_subject->isInCurriculum($curriculum->curriculum_id,$basic_subject->id,3)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$basic_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-check">Предметы 4 семестра:
                <p></p>
                @foreach($basic_subjects as $basic_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$basic_subject->id}}" type="checkbox" name="basic_subjects_4[]"
                                {{$basic_subject->isInCurriculum($curriculum->curriculum_id,$basic_subject->id,4)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$basic_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-check">Предметы 5 семестра:
                <p></p>
                @foreach($basic_subjects as $basic_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$basic_subject->id}}" type="checkbox" name="basic_subjects_5[]"
                                {{$basic_subject->isInCurriculum($curriculum->curriculum_id,$basic_subject->id,5)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$basic_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-check">Предметы 6 семестра:
                <p></p>
                @foreach($basic_subjects as $basic_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$basic_subject->id}}" type="checkbox" name="basic_subjects_6[]"
                                {{$basic_subject->isInCurriculum($curriculum->curriculum_id,$basic_subject->id,6)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$basic_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-check">Предметы 7 семестра:
                <p></p>
                @foreach($basic_subjects as $basic_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$basic_subject->id}}" type="checkbox" name="basic_subjects_7[]"
                                {{$basic_subject->isInCurriculum($curriculum->curriculum_id,$basic_subject->id,7)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$basic_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-check">Предметы 8 семестра:
                <p></p>
                @foreach($basic_subjects as $basic_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$basic_subject->id}}" type="checkbox" name="basic_subjects_8[]"
                                {{$basic_subject->isInCurriculum($curriculum->curriculum_id,$basic_subject->id,8)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$basic_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-check">Предметы на выбор:
                <p></p>
                @foreach($extra_subjects as $extra_subject)
                    <div class="form-group row bg-light">
                        <div class="col-md-1">
                            <input value="{{$extra_subject->id}}" type="checkbox" name="extra_subjects[]"
                                {{$extra_subject->isInCurriculum($curriculum->curriculum_id, $extra_subject->id)?'checked':''}}/>
                        </div>
                        <div class="col-md-6">
                            <label style="float: left">{{$extra_subject->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Сохранить</button>
        </form>
    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="stylesheet" href={{asset("css/Chart.min.css")}}>
    <link rel="stylesheet" href={{asset("css/login.css")}}>
    <link rel="stylesheet" href={{asset("laracrud/css/bootstrap.min.css")}}>
    <link rel="stylesheet" href={{asset("css/admin.css")}}>

    <script type="text/javascript" src={{asset("js/Chart.min.js")}}></script>
    <script type="text/javascript" src={{asset("js/admin.js")}} defer></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
</head>
<body>
<div class="side-menu open pinned" data-wui-theme="dark">
    <ul class="side-menu-items">
        <li>
            <a href="{{route('admin')}}" class="side-menu-item active">
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
            <a href="{{route('admin.curriculum.index')}}" class="side-menu-item">
                <span class="box-title">Учебные планы</span>
            </a>
        </li>
    </ul>
</div>
<div class="content">
    @include("laracrud.includes.alerts")
    <div class="card-header">Статистика</div>
    <div class="card-body">

        <H5 style="text-align: center">Общая статистика</H5><hr>
        <div class="chart-container" style="position: relative; width:400px; height:200px; ">
            <canvas id="extraSubjectsChart"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:400px; height:200px;">
            <canvas id="professionsChart"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:800px; height:400px;">
            <canvas id="majorsChart"></canvas>
        </div>

        <hr><H5 style="text-align: center">Абитуриенты</H5><hr>
        <div class="chart-container" style="position: relative; width:400px; height:200px; ">
            <canvas id="extraSubjectsChart-e"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:400px; height:200px;">
            <canvas id="professionsChart-e"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:800px; height:400px;">
            <canvas id="majorsChart-e"></canvas>
        </div>

        <hr><H5 style="text-align: center">1 курс</H5><hr>
        <div class="chart-container" style="position: relative; width:400px; height:200px; ">
            <canvas id="extraSubjectsChart-1"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:400px; height:200px;">
            <canvas id="professionsChart-1"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:800px; height:400px;">
            <canvas id="majorsChart-1"></canvas>
        </div>

        <hr><H5 style="text-align: center">2 курс</H5><hr>
        <div class="chart-container" style="position: relative; width:400px; height:200px; ">
            <canvas id="extraSubjectsChart-2"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:400px; height:200px;">
            <canvas id="professionsChart-2"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:800px; height:400px;">
            <canvas id="majorsChart-2"></canvas>
        </div>

        <hr><H5 style="text-align: center">3 курс</H5><hr>
        <div class="chart-container" style="position: relative; width:400px; height:200px; ">
            <canvas id="extraSubjectsChart-3"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:400px; height:200px;">
            <canvas id="professionsChart-3"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:800px; height:400px;">
            <canvas id="majorsChart-3"></canvas>
        </div>

        <hr><H5 style="text-align: center">4 курс</H5><hr>
        <div class="chart-container" style="position: relative; width:400px; height:200px; ">
            <canvas id="extraSubjectsChart-4"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:400px; height:200px;">
            <canvas id="professionsChart-4"></canvas>
        </div>
        <div class="chart-container" style="position: relative; width:800px; height:400px;">
            <canvas id="majorsChart-4"></canvas>
        </div>
    </div>
</div>
</body>
</html>

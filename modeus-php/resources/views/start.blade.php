<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="stylesheet" href={{asset("css/style.css")}}>
    <link rel="stylesheet" href={{asset("css/preloader.css")}}>

    <script type="text/javascript" src={{asset("js/redips-drag-min.js")}}></script>
    <script type="text/javascript" src={{asset("js/jspdf.min.js")}}></script>
    <script type="text/javascript" src={{asset("js/jspdf.plugin.autotable.js")}}></script>
    <script type="text/javascript" src={{asset("js/drag-n-drop.js")}}></script>
    <script type="text/javascript" src={{asset("js/encodedFont.js")}}></script>

    <script type="text/javascript" src={{asset("js/main.js")}}></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выбор траектории студента</title>
</head>
<body>
<main>
    <header class="main-header">
        <nav class="main-menu">
            <ul>
                <li><a href="https://rtf.urfu.ru/ob-institute/" target="_blank">Об институте</a></li>
                <li><a href="https://priem-rtf.urfu.ru/ru/" target="_blank">Абитуриенту</a></li>
                <li><a href="https://rtf.urfu.ru/ru/student/" target="_blank">Студенту</a></li>
                <li><a href="https://rtf.urfu.ru/ru/graduate/" target="_blank">Выпускнику</a></li>
                <li><a href="https://rtf.urfu.ru/ru/staffer/" target="_blank">Сотруднику</a></li>
                <li><a href="https://rtf.urfu.ru/ru/science/" target="_blank">Наука</a></li>
                <li><a href="https://rtf.urfu.ru/ru/kontakty/" target="_blank">Контакты</a></li>
            </ul>
        </nav>
    </header>
    <section class="header-promo">
        <div class="container">
            <div class="header-top">
                <div class="promo">
                    <a class="non-decorated" href="">
                        <div class="logo">
                            <img src={{asset("img/logo.png")}} class="img" width="118" height="72" alt="Лого ИРИТ-РТФ">
                        </div>
                    </a>
                    <div class="promo-title">
                        <h3>Начни свой путь!</h3>
                        <p class="promo-description">Перед тобой открыто множество дорог. Тебе решать, кем быть.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () =>{
            getDataByRequest("major/all",initializeMajorsList);
        })
    </script>

    <div id="preloader">
        <!--https://icons8.com/cssload-->
        <div class="windows8">
            <div class="wBall" id="wBall_1">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_2">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_3">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_4">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_5">
                <div class="wInnerBall"></div>
            </div>
        </div>
    </div>

    <div id="page-content">
        <section id="majors">
            <div class="container">
                <div class="content">
                    <div class="tabs-container">
                        <select id="tabs" class="tabs" onchange="checkMajorOptions()">
                            <option class="tab" >Выберите направление</option>
                            <template id="tabs-template">
                                <option id="major-name" class="tab" data-tab-target="">Название вкладки</option>
                            </template>
                        </select>
                    </div>

                    <div class="tab-content-container">
                        <div id="small-preloader" style="display:none;">
                            <div class="windows8">
                                <div class="wBall" id="wBall_1s">
                                    <div class="wInnerBall"></div>
                                </div>
                                <div class="wBall" id="wBall_2s">
                                    <div class="wInnerBall"></div>
                                </div>
                                <div class="wBall" id="wBall_3s">
                                    <div class="wInnerBall"></div>
                                </div>
                                <div class="wBall" id="wBall_4s">
                                    <div class="wInnerBall"></div>
                                </div>
                                <div class="wBall" id="wBall_5s">
                                    <div class="wInnerBall"></div>
                                </div>
                            </div>
                        </div>
                        <template id="tab-content-template">
                            <div id="major-info" data-tab-content class="major-professions">
                                <select id="professions" class="professions" onchange="checkProfessionOptions()">
                                    <option style="white-space: pre-line">Выберите профессию</option>
                                    <template id="object-template" class="object-template">
                                        <option class="profession-name" id="profession-name" style="white-space: pre-line"></option>
                                    </template>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>
        <section id="interactive-table" class="object-zone text-zone hide">
            <div class="container instruction">
                <h2>Познакомься с учебным планом</h2>
                <p style="white-space: pre-line">Ты можешь дополнить его курсами на выбор среди тех, что скрыты ниже в разноцветных категориях. Достаточно перетащить интересующий курс в соответствующее поле таблицы. После составления индивидуального учебного плана его можно сохранить в формате PDF.

                </p>
            </div>
            <div class="container">
                <div id="draggable-content" class="draggable-content-area" style="overflow-x:auto;">
                    <table id="curriculumTable" class="curriculumTable" style="width: 100%">
                        <tbody>
                        <tr class="table-main-header">
                            <td class="non-editable-table-cell dark" rowspan="4" style="width: 10%">Базовые дисциплины</td>
                            <td colspan="2" class="non-editable-table-cell dark">1 курс</td>
                            <td colspan="2" class="non-editable-table-cell dark">2 курс</td>
                            <td colspan="2" class="non-editable-table-cell dark">3 курс</td>
                            <td colspan="2" class="non-editable-table-cell dark">4 курс</td>
                        </tr>
                        <tr class="table-main-header">
                            <td class="non-editable-table-cell dark" style="width: 10%">1 семестр</td>
                            <td class="non-editable-table-cell dark" style="width: 10%">2 семестр</td>
                            <td class="non-editable-table-cell dark" style="width: 10%">3 семестр</td>
                            <td class="non-editable-table-cell dark" style="width: 10%">4 семестр</td>
                            <td class="non-editable-table-cell dark" style="width: 10%">5 семестр</td>
                            <td class="non-editable-table-cell dark" style="width: 10%">6 семестр</td>
                            <td class="non-editable-table-cell dark" style="width: 10%">7 семестр</td>
                            <td class="non-editable-table-cell dark" style="width: 10%">8 семестр</td>
                        </tr>
                        <tr class="table-mobile-header">
                            <td class="non-editable-table-cell blank" colspan="8">Базовые дисциплины</td>
                        </tr>
                        <tr id="basic-subjects">

                            <td class="non-editable-table-cell blank table-mobile-header" colspan="8">1 курс (1 семестр)</td>
                            <td style="white-space: pre-line; text-align: left; vertical-align: top" class="non-editable-table-cell basic" id="1-semester"></td>

                            <td class="non-editable-table-cell blank table-mobile-header" colspan="8">1 курс (2 семестр)</td>
                            <td style="white-space: pre-line; text-align: left; vertical-align: top" class="non-editable-table-cell basic" id="2-semester"></td>

                            <td class="non-editable-table-cell blank table-mobile-header" colspan="8">2 курс (3 семестр)</td>
                            <td style="white-space: pre-line; text-align: left; vertical-align: top" class="non-editable-table-cell basic" id="3-semester"></td>

                            <td class="non-editable-table-cell blank table-mobile-header" colspan="8">2 курс (4 семестр)</td>
                            <td style="white-space: pre-line; text-align: left; vertical-align: top" class="non-editable-table-cell basic" id="4-semester"></td>

                            <td class="non-editable-table-cell blank table-mobile-header" colspan="8">3 курс (5 семестр)</td>
                            <td style="white-space: pre-line; text-align: left; vertical-align: top" class="non-editable-table-cell basic" id="5-semester"></td>

                            <td class="non-editable-table-cell blank table-mobile-header" colspan="8">3 курс (6 семестр)</td>
                            <td style="white-space: pre-line; text-align: left; vertical-align: top" class="non-editable-table-cell basic" id="6-semester"></td>

                            <td class="non-editable-table-cell blank table-mobile-header" colspan="8">4 курс (7 семестр)</td>
                            <td style="white-space: pre-line; text-align: left; vertical-align: top" class="non-editable-table-cell basic" id="7-semester"></td>

                            <td class="non-editable-table-cell blank table-mobile-header" colspan="8">4 курс (8 семестр)</td>
                            <td style="white-space: pre-line; text-align: left; vertical-align: top" class="non-editable-table-cell basic" id="8-semester"></td>
                        </tr>
                        <tr>
                            <td class="non-editable-table-cell blank" colspan="8">Дисциплины на выбор</td>
                        </tr>
                        <tr>
                            <td class="non-editable-table-cell dark">Предмет на выбор 1</td>
                            <td class="non-editable-table-cell" colspan="2" rowspan="3" style="background-color: #F9F9F9">ВЫБОР НЕДОСТУПЕН</td>

                            <td rowspan="3" class="extraSubject"><p class="table-mobile-header" style="color: grey">2 курс (3 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">2 курс (4 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">3 курс (5 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">3 курс (6 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">4 курс (7 семестр)</p></td>
                            <td class="non-editable-table-cell" rowspan="3" style="background-color: #F9F9F9">ВЫБОР НЕДОСТУПЕН</td>
                        </tr>
                        <tr>
                            <td class="non-editable-table-cell dark">Предмет на выбор 2</td>
                            <td rowspan="2" class="extraSubject"><p class="table-mobile-header" style="color: grey">2 курс (4 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">3 курс (5 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">3 курс (6 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">4 курс (7 семестр)</p></td>
                        </tr>
                        <tr>
                            <td class="non-editable-table-cell dark">Предмет на выбор 3</td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">3 курс (5 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">3 курс (6 семестр)</p></td>
                            <td class="extraSubject"><p class="table-mobile-header" style="color: grey">4 курс (7 семестр)</p></td>
                        </tr>
                        </tbody>

                        <tr>
                            <td colspan="1" class="non-editable-table-cell"><button onclick="resetTable()">Очистить таблицу</button></td>
                            <td class="trash" colspan="7"><img src={{asset("img/trash.png")}} alt="Корзина"></td>
                            <td colspan="1" class="non-editable-table-cell"><button onclick="checkCells()" id="save-as-pdf-button">Сохранить как pdf</button></td>
                        </tr>
                        <tr>
                            <td class="non-editable-table-cell" colspan="9" style="white-space: pre-line; padding: 10px;">Ниже представлен каталог доступных курсов, которые можно использовать при составлении индивидуального учебного плана.
                                Достаточно перетащить интересующий курс в соответствующее поле таблицы.
                                После составления индивидуального учебного плана его можно сохранить в формате PDF</td>
                        </tr>
                        <tbody id="subjectsOfChoiceTable" class="subjectsOfChoiceTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <div id="occupations-preloader" style="display:none;">
            <div class="windows8">
                <div class="wBall" id="wBall_1o">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_2o">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_3o">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_4o">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_5o">
                    <div class="wInnerBall"></div>
                </div>
            </div>
        </div>
        <div id="profession-info" class="profession-info">
            <template id="profession-template">
                <div id="profession" class="profession hide">
                    <section id="about" class="object-zone text-zone gray">
                        <div class="container">
                            <h2>Кто это и чем занимается?</h2>
                            <p style="white-space: pre-line">Веб-программист — это специалист, который занимается разработкой и оформлением внешнего вида сайта, мобильного приложения или других веб проектов.
                                Задача web-программиста — сделать интернет проект удобным, визуально красивым (привлекательным) и понятным для пользователей.</p>
                        </div>
                    </section>

                    <section id="salary" class="object-zone text-zone">
                        <div class="container">
                            <h2>А зарплата какая?</h2>
                            <p style="white-space: pre-line">График средних зарплат в рублях</p>
                            <div class="center-container">
                                <div id="chart" class="chart">
                                    <!--<img src="https://cdn.dribbble.com/users/976153/screenshots/2667285/dribbble_graph-01.png" width="400" height="300" alt="график зарплат">-->
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="direction" class="object-zone text-zone gray">
                        <div class="container">
                            <h2>Куда пойти учиться?</h2>
                            <p style="white-space: pre-line">В нашем институте есть несколько направлений, на которых ты можешь освоить эту профессию. Можешь выбрать любое из них: </p>
                        </div>
                    </section>

                    <section id="partners" class="object-zone text-zone">
                        <div class="container">
                            <h2>А где работать потом?</h2>
                            <p style="white-space: pre-line">С нашим институтом сотрудничает множество крутых компаний-партнёров, которые могут помочь тебе начать свою карьеру.  С некоторыми из них ты познакомишься прямо на занятиях, а с некоторыми встретишься на проводимых институтом мероприятиях.

                                У тебя будет возможность попасть на стажировки в эти компании, а при успешном их прохождении - трудоустроиться, и порадовать маму с папой своей самостоятельностью :)

                                Тебя возьмут на работу настоящие гиганты IT-индустрии города Екатеринбург. Вот они слева направо: </p>
                            <br>
                            <div class="center-container">
                                <img class="round" src="http://www.delmoscow.ru/resources/news/preview_f33ecfa3.jpg" width="100" height="100" alt="компания 1">
                                <img class="round" src="https://images.ctfassets.net/oxjq45e8ilak/3M5czHSkoEmYM8SCQQ8I2q/ff1cb68bdabe164fcffc140598614193/____________________800_800.png?w=150" width="100" height="100" alt="компания 2">
                                <img class="round" src="https://cdn1.flamp.ru/4d2e77c56108637e08f2516a7a068bf4_100_100.jpg" width="100" height="100" alt="компания 3">
                                <img class="round" src="http://thomas-lindemann.com/wp-content/uploads/2018/12/logo.jpg" width="100" height="100" alt="компания 4">
                                <img class="round" src="https://static.tildacdn.com/tild3039-3461-4535-b833-363439626639/1899883.png" width="100" height="100" alt="компания 5">
                            </div>
                        </div>
                    </section>
                </div>
            </template>
        </div>

    <div id="popup" class="popup">
        <div class="popup-header">
            <div class="popup-title">Выбери свой курс:</div>
            <button class="close-button" onclick="closePopup()" style="color: #005092">&#10006;</button>
        </div>
        <div class="popup-body">
            <form name="course">
                <fieldset id="course">
                    <input id="entrant" type="radio" value="Абитуриент" name="course" checked="checked">
                    <label for="entrant">Абитуриент</label>

                    <input id="1st-course" type="radio" value="1 курс" name="course">
                    <label for="1st-course">1 курс</label>

                    <input id="2nd-course" type="radio" value="2 курс" name="course">
                    <label for="2nd-course">2 курс</label>

                    <input id="3rd-course" type="radio" value="3 курс" name="course">
                    <label for="3rd-course">3 курс</label>

                    <input id="4th-course" type="radio" value="4 курс" name="course">
                    <label for="4th-course">4 курс</label>
                </fieldset>
            </form>
        </div>
            <button id="create-PDF-from-popup" class="submit-button">Скачать PDF</button>
    </div>
    <div id="overlay" onclick="closePopup()"></div>

    </div>
</main>

<footer class="main-footer">
    <div class="container">
        <div class="footer-top">
            <div class="copyright">
                <p>© ФГАОУ ВО «УрФУ имени первого Президента России Б.Н. Ельцина»</p>
            </div>
            <div class="social-nets">
                <h3>Мы в социальных сетях:</h3>
                <ul>
                    <li><a href="https://vk.com/iritrtf_urfu" target="_blank">
                            <img src={{asset("img/vk-logo.png")}} width="48" height="48" alt="Лого VK - Абитуриенту">
                        </a></li>
                    <li><a href="https://www.instagram.com/rtfurfu" target="_blank">
                            <img src={{asset("img/instagram-logo.png")}} width="48" height="48" alt="Лого Instagram - Союз студентов">
                        </a></li>
                    <li><a href="https://vk.com/irit_rtf" target="_blank">
                            <img src={{asset("img/vk-logo.png")}} width="48" height="48" alt="Лого VK - Союз студентов">
                        </a></li>
                </ul>
                <p>© 2020 Институт радиоэлектроники и информационных технологий - РтФ</p>
            </div>
            <div class="address">
                <p style="white-space: pre-line">Институт радиоэлектроники и информационных технологий, Россия, г. Екатеринбург, ул. Мира 32
                    Дирекция: +7 (343) 375-97-00
                    E-mail: rtf@urfu.ru</p>
            </div>
        </div>
    </div>
</footer>
</body>
</html>

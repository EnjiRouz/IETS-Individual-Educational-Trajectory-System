const host = "http://localhost:8080/";

/**
 * Получение элементов по id
 * @param id - идентификатор элемента
 * @returns {HTMLElement}
 */
function getById(id){
    return document.getElementById(id);
}

/**
 * Получение элементов по имени класса
 * @param className - имя класса
 * @returns {HTMLCollectionOf<Element>}
 */
function getByClassName(className){
    return document.getElementsByClassName(className);
}

/**
 * Получение ответа от сервера
 * @param request - запрос, на который должен быть получен ответ
 * @param method - способ обработки полученной информации
 * @param additionalMethodValue - дополнительный аргумент для обрабатывающего метода
 * @returns {null}  если ответ не может быть получен
 */
function getDataByRequest(request, method, additionalMethodValue=null){
    let httpRequest= new XMLHttpRequest();
    let url=host+request;

    // в случае успешного запроса происходит передача полученных данных в обрабатывающий их метод
    httpRequest.onreadystatechange = function() {
        if(this.status===200 && this.readyState===4) {
            // выполнение метода с возможностью использовать дополнительнче аргументы
            if(additionalMethodValue)
                method(httpRequest.responseText, additionalMethodValue);
            else
                method(httpRequest.responseText);
        }
        if(this.status===500){
            console.log("Ошибка: сервер недоступен либо на нём отсутствует запрашиваемая информация");
        }
    };

    httpRequest.open("GET", url,true);
    httpRequest.send();
}

/**
 * Получение массива значений из ассоциативного массива (словаря)
 * @param obj - исходный ассоциативный массив
 * @returns {*[]} - массив значений
 */
function convertToArray(obj) {
    return Object.keys(obj).map(function(key) {
        return obj[key];
    });
}

/**
 * Построение графика напралений обучения
 * @param elementId - элемент для расположения графика
 * @param dataToShow - данные, на основе которых будет построен график
 * @param title - заголовок графика
 */
function drawNewMajorsGraph(elementId, dataToShow, title) {
    let chartCanvas = document.getElementById(elementId);
    new Chart(chartCanvas, {
        type: 'bar', //horizontalBar
        data: {
            labels: Object.keys(dataToShow),
            datasets: [{
                data: convertToArray(dataToShow),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: title
            },
            legend: {
                display: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

/**
 * Построение графика дополнительных дисциплин
 * @param elementId - элемент для расположения графика
 * @param dataToShow - данные, на основе которых будет построен график
 * @param title - заголовок графика
 */
function drawNewExtraSubjectsGraph(elementId, dataToShow, title) {
    let extraSubjectsChartCanvas = document.getElementById(elementId);
    new Chart(extraSubjectsChartCanvas, {
        type: 'doughnut',
        data: {
            labels: Object.keys(dataToShow),
            datasets: [{
                data: convertToArray(dataToShow),
                backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: title
            }
        }
    });
}

/**
 * Построение графика профессий
 * @param elementId - элемент для расположения графика
 * @param dataToShow - данные, на основе которых будет построен график
 * @param title - заголовок графика
 */
function drawNewProfessionsGraph(elementId, dataToShow, title) {
    let professionsChartCanvas = document.getElementById(elementId);
    new Chart(professionsChartCanvas, {
        type: 'pie',
        data: {
            labels: Object.keys(dataToShow),
            datasets: [{
                data: convertToArray(dataToShow),
                backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: title
            }
        }
    });
}

/**
 * Создание визуального представления статистики (рисование графиков)
 * Documentation: https://www.chartjs.org/docs/latest/general/responsive.html
 * @param response - ответ сервера
 */
function drawCharts(response) {
    let statistic=JSON.parse(response);
    let chosen_extra_subjects_statistic = statistic.chosen_extra_subjects;
    let chosen_extra_subjects_statistic_entrant=statistic.chosen_extra_subjects_entrant;
    let chosen_extra_subjects_1_course=statistic.chosen_extra_subjects_1_course;
    let chosen_extra_subjects_2_course=statistic.chosen_extra_subjects_2_course;
    let chosen_extra_subjects_3_course=statistic.chosen_extra_subjects_3_course;
    let chosen_extra_subjects_4_course=statistic.chosen_extra_subjects_4_course;

    drawNewExtraSubjectsGraph('extraSubjectsChart', chosen_extra_subjects_statistic,'Самые выбираемые дополнительные дисциплины');
    drawNewExtraSubjectsGraph('extraSubjectsChart-e',chosen_extra_subjects_statistic_entrant,'Самые выбираемые дополнительные дисциплины среди абитуриентов');
    drawNewExtraSubjectsGraph('extraSubjectsChart-1',chosen_extra_subjects_1_course,'Самые выбираемые дополнительные дисциплины среди учащихся 1 курса');
    drawNewExtraSubjectsGraph('extraSubjectsChart-2',chosen_extra_subjects_2_course,'Самые выбираемые дополнительные дисциплины среди учащихся 2 курса');
    drawNewExtraSubjectsGraph('extraSubjectsChart-3',chosen_extra_subjects_3_course,'Самые выбираемые дополнительные дисциплины среди учащихся 3 курса');
    drawNewExtraSubjectsGraph('extraSubjectsChart-4',chosen_extra_subjects_4_course,'Самые выбираемые дополнительные дисциплины среди учащихся 4 курса');

    let chosen_majors_statistic = statistic.chosen_majors;
    let chosen_majors_statistic_entrant=statistic.chosen_majors_entrant;
    let chosen_majors_1_course=statistic.chosen_majors_1_course;
    let chosen_majors_2_course=statistic.chosen_majors_2_course;
    let chosen_majors_3_course=statistic.chosen_majors_3_course;
    let chosen_majors_4_course=statistic.chosen_majors_4_course;

    drawNewMajorsGraph('majorsChart', chosen_majors_statistic,'Самые выбираемые направления обучения');
    drawNewMajorsGraph('majorsChart-e',chosen_majors_statistic_entrant,'Самые выбираемые направления обучения среди абитуриентов');
    drawNewMajorsGraph('majorsChart-1',chosen_majors_1_course,'Самые выбираемые направления обучения среди учащихся 1 курса');
    drawNewMajorsGraph('majorsChart-2',chosen_majors_2_course,'Самые выбираемые направления обучения среди учащихся 2 курса');
    drawNewMajorsGraph('majorsChart-3',chosen_majors_3_course,'Самые выбираемые направления обучения среди учащихся 3 курса');
    drawNewMajorsGraph('majorsChart-4',chosen_majors_4_course,'Самые выбираемые направления обучения среди учащихся 4 курса');

    let chosen_professions_statistic = statistic.chosen_professions;
    let chosen_professions_statistic_entrant=statistic.chosen_professions_entrant;
    let chosen_professions_1_course=statistic.chosen_professions_1_course;
    let chosen_professions_2_course=statistic.chosen_professions_2_course;
    let chosen_professions_3_course=statistic.chosen_professions_3_course;
    let chosen_professions_4_course=statistic.chosen_professions_4_course;

    drawNewProfessionsGraph('professionsChart', chosen_professions_statistic,'Самые выбираемые профессии');
    drawNewProfessionsGraph('professionsChart-e',chosen_professions_statistic_entrant,'Самые выбираемые профессии среди абитуриентов');
    drawNewProfessionsGraph('professionsChart-1',chosen_professions_1_course,'Самые выбираемые профессии среди учащихся 1 курса');
    drawNewProfessionsGraph('professionsChart-2',chosen_professions_2_course,'Самые выбираемые профессии среди учащихся 2 курса');
    drawNewProfessionsGraph('professionsChart-3',chosen_professions_3_course,'Самые выбираемые профессии среди учащихся 3 курса');
    drawNewProfessionsGraph('professionsChart-4',chosen_professions_4_course,'Самые выбираемые профессии среди учащихся 4 курса');
}

window.onload=function() {
    getDataByRequest('get_statistic', drawCharts);
};


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
 * Получение элементов по тегу
 * @param tag - тег элемента
 * @returns {HTMLCollectionOf<HTMLElementTagNameMap[*]>}
 */
function getByTag(tag){
    return document.getElementsByTagName(tag);
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
            let subjectsContentPlace=getById("subjects");
            subjectsContentPlace.innerHTML="<br>ОТСУТСТВУЕТ ЗАПРАШИВАЕМАЯ ИНФОРМАЦИЯ!<p></p>";
            console.log("Ошибка: сервер недоступен либо на нём отсутствует запрашиваемая информация");
        }

    };

    httpRequest.open("GET",url,true);
    httpRequest.send();
}

/**
 * Отправление информации на сервер
 * @param request - запрос, который должен быть отправлен
 * @param dataToSend - данные для отправки
 */
function postDataByRequest(request, dataToSend){
    let httpRequest= new XMLHttpRequest();
    let url=host+request;

    httpRequest.open("POST",url,true);
    httpRequest.send(dataToSend);
}

/**
 * Проверка того, какие направления обучения выбраны, чтобы получить список доступных для них дополнительных курсов (общих)
 */
function checkChosenMajors(additionalData=null) {
    let checkboxes = document.getElementsByName('majors[]');
    let checked=[];

    for(let i=0; i<checkboxes.length;i++){
        if(checkboxes[i].checked)
            checked.push(checkboxes[i].value);
    }

    if(checked.length>0)
        getDataByRequest("get_extra_subjects_for_chosen_majors="+checked, showExtraSubjects, additionalData);
}

/**
 * Показ пересечений среди курсов выбранных направлений в виде элементов checkbox
 * @param response - ответ сервера
 * @param presetsIds - id пресетов рекомендуемых дополнительных дисциплин
 */
function showExtraSubjects(response, presetsIds=null) {
    let subjectsContentPlace=getById("subjects");
    subjectsContentPlace.innerHTML="<br>Рекомендуемые курсы (выберите не более 12):<p></p>";

    let parsedExtraSubjects=JSON.parse(response);
    if (parsedExtraSubjects.length>0) {
        for (let i = 0; i < parsedExtraSubjects.length; i++) {
            if (presetsIds) {
                if (presetsIds.includes(parsedExtraSubjects[i].id))
                    subjectsContentPlace.innerHTML += "<div class='form-group row bg-light'><div class='col-md-1'><input value='" + parsedExtraSubjects[i].id + "' type='checkbox' name='extras[]' onchange='checkChosenSubjects()' checked/></div><div class='col-md-6'><label style='float:left'>" + parsedExtraSubjects[i].name + "</label></div></div>";
                else subjectsContentPlace.innerHTML += "<div class='form-group row bg-light'><div class='col-md-1'><input value='" + parsedExtraSubjects[i].id + "' type='checkbox' name='extras[]' onchange='checkChosenSubjects()'/></div><div class='col-md-6'><label style='float:left'>" + parsedExtraSubjects[i].name + "</label></div></div>";
            }
            else subjectsContentPlace.innerHTML += "<div class='form-group row bg-light'><div class='col-md-1'><input value='" + parsedExtraSubjects[i].id + "' type='checkbox' name='extras[]' onchange='checkChosenSubjects()'/></div><div class='col-md-6'><label style='float:left'>" + parsedExtraSubjects[i].name + "</label></div></div>";
        }
    }
    else
        subjectsContentPlace.innerHTML="<br>ПЕРЕСЕЧЕНИЙ СРЕДИ КУРСОВ НАПРАВЛЕНИЙ НЕ НАЙДЕНО<p></p>";
}

/**
 * Проверка на то, что выбрано не более 12 дисциплин (ограничение по количеству данных в учебном плане)
 */
function checkChosenSubjects() {
    // проверить, что выбранных элементов не более 12
    let checkboxes = document.getElementsByName('extras[]');
    let checked=[];

    for(let i=0; i<checkboxes.length;i++){
        if(checkboxes[i].checked)
            checked.push(checkboxes[i].value);
    }

    if(checked.length>12)
        alert("Вы выбрали более 12 курсов! Уберите лишние из списка, чтобы продолжить");
}

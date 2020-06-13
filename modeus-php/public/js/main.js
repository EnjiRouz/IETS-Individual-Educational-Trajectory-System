const host = "http://localhost:8080/";

// цвета, используемые в категориях таблицы
const Colors = Object.freeze([
    "red",
    "pink",
    "purple",
    "blue",
    "sea",
    "green",
    "yellow",
    "orange",
    "fox",
    "pink2",
    "purple2",
    "blue2",
    "sea2",
]);

let baseSubjectsForPdf;
let userChoices;
let occupationsPreset;
let chosenProfession;

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
 * Получение hashCode объекта типа string
 */
Object.defineProperty(String.prototype, 'hashCode', {
    value: function() {
        let hash = 0, i, chr;
        for (i = 0; i < this.length; i++) {
            chr   = this.charCodeAt(i);
            hash  = ((hash << 5) - hash) + chr;
            hash |= 0; // Convert to 32bit integer
        }
        return hash;
    }
});

/**
 * Получение ответа от сервера
 * @param request - запрос, на который должен быть получен ответ
 * @param method - способ обработки полученной информации
 * @param pageContentToHide - id контента, который нужно скрыть
 * @param hidePageContent - скрывать ли контент страницы во время загрузки
 * @param preloaderName - идентификатор элемента, отображающего предзагрузку
 * @param additionalMethodValue - дополнительный аргумент для обрабатывающего метода
 * @returns {null}  если ответ не может быть получен
 */
function getDataByRequest(request, method, pageContentToHide="page-content",hidePageContent=true, preloaderName="preloader", additionalMethodValue=null){
    let httpRequest= new XMLHttpRequest();
    let url=host+request;
    let preloader=getById(preloaderName);
    if(preloader)
        preloader.style.display="block";

    // сокрытие некоторого содержимого страницы
    let pageContent=getById(pageContentToHide);
    if(pageContent && hidePageContent)
        pageContent.style.display="none";

    let searchBar=document.forms["search"];
    if(searchBar)
        searchBar.style.display="none";

    // в случае успешного запроса происходит передача полученных данных в обрабатывающий их метод
    httpRequest.onreadystatechange = function() {
        if(this.status===200 && this.readyState===4) {
            // выполнение метода с возможностью использовать дополнительнче аргументы
            if(additionalMethodValue)
                method(httpRequest.responseText, additionalMethodValue);
            else
                method(httpRequest.responseText);

            // восстановление отображения основного содержимого страницы
            if(preloader)
                preloader.style.display="none";
            if(searchBar)
                searchBar.style.display="block";
            if(pageContent)
                pageContent.style.display="block";
        }
        if(this.status===500){
            if(preloader)
                preloader.style.display="none";

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
 * Получение информации о доступных направлениях обучения из ответа сервера
 * и последующая инициализация объектов на странице в алфавитном порядке
 * @param response - ответ сервера на запрос
 */
function initializeMajorsList(response)
{
    let majors=JSON.parse(response);
    let templateContent = getById("tabs-template").content;
    let tabs=getByClassName("tabs");

    // сортировка по алфавиту в зависимости от названия направления
    majors.sort((head, tail) => head.name.localeCompare(tail.name));

    // создание элементов в соответствии с шаблоном для возможности работы с переключением между вкладками внутри страницы
    for (let i=0; i<majors.length; i++) {
        templateContent.getElementById("major-name").setAttribute("data-tab-target", "#majorId" + majors[i].id);
        templateContent.getElementById("major-name").textContent = majors[i].name;
        tabs[0].appendChild(templateContent.cloneNode(true));

        // установка уникального id
        getById("major-name").setAttribute("id", "major-name=" + majors[i].name);
    }

    // первая вкладка по умолчанию будет активна, соответственно, информация о её содержимым также загружается первой
    getById("major-name=" + majors[0].name).classList.add("active");
    getInfoAboutChosenMajorId(majors[0].id);
}

/**
 * Получение информации о выбранном направлении через запрос к серверу
 * @param majorId - идентификатор направления обучения
 */
function getInfoAboutChosenMajorId(majorId) {
    getDataByRequest("occupation_by_major_id="+majorId,
        setMajorInfoById, "page-content",false,"small-preloader", majorId);
}


/**
 * Получение информации о выбранной профессии через запрос к серверу
 * @param professionId - идентификатор профессии
 */
function getInfoAboutChosenProfessionId(professionId) {
    getDataByRequest("occupation_id="+professionId,
        setPageContent, "profession-info",true,"occupations-preloader", professionId);
}


/**
 * Инициализация объекта,содержащего информацию о направлении обучения по заданному шаблону
 * для возможности работы с переключением между вкладками внутри страницы
 * @param response - ответ сервера на запрос
 * @param majorId - идентификатор направления обучения
 */
function setMajorInfoById(response,majorId){
    let occupations=JSON.parse(response);
    let templateContent = getById("tab-content-template").content;
    let contentContainer=getByClassName("tab-content-container");

    // установка метода для проверки нужного селектора
    templateContent.getElementById("professions").setAttribute("onchange", "checkProfessionOptions('professionsFromMajorId" + majorId+"')");
    contentContainer[0].appendChild(templateContent.cloneNode(true));

    // установка уникального id для поверки нужного селектора
    getById("professions").setAttribute("id","professionsFromMajorId"+majorId);

    // установка уникального id для возможности работы с переключением между вкладками внутри страницы
    getById("major-info").setAttribute("id", "majorId" + majorId);

    // активация объекта
    getById("majorId" + majorId).classList.add("active");

    occupationsPreset=occupations;

    setOccupationsObjects(occupations, "majorId" + majorId);
}

/**
 * Инициализация объектов, содержащих названия профессий, доступных к освоению на направлении
 * @param occupations - ответ сервера, содержащий названия профессий и их описания
 * @param parentNodeId - идентификатор контейнера, к которому будут добавлены объекты
 */
function setOccupationsObjects(occupations, parentNodeId){
    let templateContent = getById("object-template").content;
    let contentContainer=getById(parentNodeId).getElementsByClassName("professions")[0];

    for (let i = 0; i < occupations.length; i++) {
        templateContent.getElementById("profession-name").setAttribute("value", "#occupationsId" + occupations[i].id);
        templateContent.getElementById("profession-name").textContent =occupations[i].name;
        contentContainer.appendChild(templateContent.cloneNode(true));
    }

    checkMajorOptions();
}


/**
 * Проверка изменения выбранной опции нправления обучения, при которой активируется сама опция и её контент
 * В случае отсутствия контента - он загружается, отправляя запрос на сервер
 */
function checkMajorOptions(){
    resetTable();

    // отключаются активные опции
    let selections= getByClassName("professions");
    for(let i=0; i<selections.length; i++)
        selections[i].selectedIndex=0;

    let options=getById("tabs").options;
    let selectedOption=getById("tabs").selectedIndex;
    for (let i=0; i<options.length; i++) {
        options[i].classList.remove("active");
    }

    // отключается активный контент
    let tabContent=getByClassName("major-professions");
    for (let i=0; i<tabContent.length; i++){
        tabContent[i].classList.remove("active");
    }

    let professions= getByClassName("profession");
    for(let i=0; i< professions.length; i++) {
        professions[i].classList.add("hide");
    }

    // включается выбранная опция и подгружается контент для неё
    options[selectedOption].classList.add("active");
    let loadCount=0;
    let target = document.querySelector(options[selectedOption].dataset.tabTarget);
    if (target===null && selectedOption>0) {
        // при отсутствии контента для вкладки производится его загрузка (запрос будет послан 1 раз)
        if(loadCount===0) {
            let majorId = Number(options[selectedOption].dataset.tabTarget.replace("#majorId", ""));
            getInfoAboutChosenMajorId(majorId);
            loadCount++;
        }
    } else {
        // проверка на то, что выбрана не опция по умолчанию
        if(selectedOption>0)
            target.classList.add("active");
    }

    // заполнение таблицы программы обучения по выбранному направлению
    createTable(options,selectedOption);
}

/**
 * Проверка изменения выбранной опции профессии, при которой активируется сама опция и её контент
 * В случае отсутствия контента - он загружается, отправляя запрос на сервер
 * @param activeSelectionId - активный селектор, связанный с конкретным направлением
 */
function checkProfessionOptions(activeSelectionId){
    resetTable();

    // отключается активный контент
    let professions= getByClassName("profession");
    for(let i=0; i< professions.length; i++) {
        professions[i].classList.add("hide");
    }

    let options=getById(activeSelectionId).options;
    let selectedOption=getById(activeSelectionId).selectedIndex;
    chosenProfession=Number(options[selectedOption].value.replace("#occupationsId", ""));

    let loadCount=0;
    let target = getById(options[selectedOption].value);
    if (target===null && selectedOption>0) {

        // при отсутствии контента для вкладки производится его загрузка (запрос будет послан 1 раз)
        if(loadCount===0) {
            let professionId = Number(options[selectedOption].value.replace("#occupationsId", ""));
            getInfoAboutChosenProfessionId(professionId);
            loadCount++;
        }
    } else {
        // проверка на то, что выбрана не опция по умолчанию
        if(selectedOption>0)
            getById(options[selectedOption].value).classList.remove("hide");
    }

    let majorId=Number(activeSelectionId.replace("professionsFromMajorId",''));
    getDataByRequest("curriculum_by_major_id=" + majorId, initializeExtraSubjects, "profession-info", true, "occupations-preloader", majorId);
}


/**
 * Устанавка контента на странице в соответствии с интересующей пользователя профессией
 * @param response - ответ сервера на запрос по конкретной профессии
 */
function setPageContent(response) {
    let parsedProfession=JSON.parse(response);

    let templateContent = getById("profession-template").content;
    let contentContainer=getByClassName("profession-info")[0];
    contentContainer.appendChild(templateContent.cloneNode(true));

    // присваивание уникального id элементу, чтобы можно было к нему обращаться
    getById("profession").setAttribute("id", "#occupationsId" + parsedProfession.id);

    // присваивание элементам значения НЕ в шаблоне, чтобы не изменять шаблон (возможно, в будущем все тексты и значения будут подгружаться из БД)
    getById("about").getElementsByTagName("p")[0].firstChild.textContent=parsedProfession.description;
    getById("about").setAttribute("id","about#occupationsId" + parsedProfession.id);

    getById("salary").getElementsByTagName("p")[0].firstChild.textContent+=" (средняя заработная плата: "+parsedProfession.salary.average+" рублей)";
    getById("salary").setAttribute("id","salary#occupationsId" + parsedProfession.id);

    getById("chart").insertAdjacentHTML('afterbegin', parsedProfession.salary.stats);
    getById("chart").setAttribute("id","chart#occupationsId" + parsedProfession.id);

    getById("direction").getElementsByTagName("p")[0].firstChild.textContent+=parseItemsList(parsedProfession.majors);
    getById("direction").setAttribute("id","direction#occupationsId" + parsedProfession.id);

    getById("partners").getElementsByTagName("p")[0].firstChild.textContent+="\n• Naumen;\n• СКБ-Контур;\n• Технопарк университетский;\n• Microsoft;\n• Сбербанк Технологии.\n";
    getById("partners").setAttribute("id","partners#occupationsId" + parsedProfession.id);

    // активация объекта
    getById("#occupationsId" + parsedProfession.id).classList.remove("hide");
}

/**
 * Заполнение таблицы программы обучения по выбранному направлению
 * @param options - множество направлений обучения
 * @param selectedOption - выбранное направление обучения
 */
function createTable(options,selectedOption) {
    // проверка на то, что выбрано направление. по которому требуется загрузить учебный план
    if (getById("tabs").selectedIndex>0) {

        // показ таблицы
        getById("interactive-table").classList.remove("hide");

        // очищение элементов таблицы
        getById("subjectsOfChoiceTable").innerHTML="";
        for (let i=0; i<8; i++) {
            getById(i+1+"-semester").innerText="";
        }

        // инициализация объекта таблицы в соответствии с выбранным направлением
        let majorId = Number(options[selectedOption].dataset.tabTarget.replace("#majorId", ""));
        getDataByRequest("curriculum_by_major_id=" + majorId, initializeBaseSubjects, "profession-info", true, "occupations-preloader", majorId);
        getDataByRequest("curriculum_by_major_id=" + majorId, initializeExtraSubjects, "profession-info", true, "occupations-preloader", majorId);
    }
    else
        getById("interactive-table").classList.add("hide");
}

/**
 * Преобразование необходимых элементов из json в строку для дальнейшего размещения в поле экрана
 * @param objectArray json array, в котром содержатся искомые элементы
 * @return string элементов в строке, который будет удобно поместить в поле
 */
function parseItemsList(objectArray) {
    let items = "";
    for (let objectsCount = 0; objectsCount < objectArray.length; objectsCount++) {
        items = items.concat("\n• "+objectArray[objectsCount].name);
    }
    return items;
}

/**
 * Поиск по названиям профессий на странице профессий, который возможен после инициализации объектов
 */
function filter() {
    let searchBar=getById("search-input-field");
    let objects = getByClassName("object");
    if(searchBar) {
        searchBar.addEventListener('input', function () {
            let term = searchBar.value;
            if(term) {
                term=term.toLowerCase();
                Array.from(objects).forEach(function (object) {
                    let title = object.getElementsByClassName("profession-name")[0].firstChild.textContent;
                    if (title.toLowerCase().includes(term)){
                        object.style.display = "inline-block";
                    } else {
                        object.style.display = "none";
                    }
                })
            }else{
                Array.from(objects).forEach(function (object){
                    object.style.display = "inline-block";
                })
            }
        })
    }
}

/**
 * Скрывает элементы заданного класса (в данном случае используется для таблицы курсов, которые можно скрыть/показать
 * в отдельной части таблицы, меняя при этом заголовок)
 * @param className имя класса, элементы которого требуется скрыть
 */
function hideElementsWithClass(className){
    let foundElements=getByClassName(className);
    let categoryTitle=document.getElementsByClassName("clickable "+className);

    if(categoryTitle[0].textContent.includes("(показать)"))	{
        categoryTitle[0].textContent=categoryTitle[0].textContent.replace("(показать)", "(скрыть)");
        for(let i=0; i<foundElements.length; i++)
            if(foundElements[i].parentElement.classList.contains("can-be-hidden")) {
                foundElements[i].classList.remove("hide");
                foundElements[i].parentElement.classList.remove("hide");
            }
    }
    else {
        categoryTitle[0].textContent = categoryTitle[0].textContent.replace("(скрыть)", "(показать)");
        for (let i = 0; i < foundElements.length; i++)
            if(foundElements[i].parentElement.classList.contains("can-be-hidden")) {
                foundElements[i].classList.add("hide");
                foundElements[i].parentElement.classList.add("hide");
            }
    }
    categoryTitle[0].classList.remove("hide");
}

/**
 * Инициализация базовых дисциплин таблицы
 * @param response - ответ сервера
 */
function initializeBaseSubjects(response) {
    let baseSubjects = JSON.parse(response);
    baseSubjectsForPdf=baseSubjects.basics;

    for (let i=0; i<Object.keys(baseSubjects.basics).length; i++) {
        getById(i+1+"-semester").innerText=baseSubjects.basics[i].subjects;
    }
}

/**
 * Инициализация дополнительных дисциплин
 * @param response - ответ сервера
 */
function initializeExtraSubjects(response) {
    resetTable();

    // нахождение пресетов курсов для профессии
    let presetIds=[];
    let presetsNames=new Set();
    for (let p=0; p<occupationsPreset.length; p++){
        if(occupationsPreset[p].id===chosenProfession)
        {
            if(occupationsPreset[p].occupation_extra_subjects_preset)
                presetIds=(occupationsPreset[p].occupation_extra_subjects_preset).split(',');
            break;
        }
    }

    let extraSubjects = JSON.parse(response);
    let categories = new Set();

    // выделение множества категорий курсов
    for (let i = 0; i < Object.keys(extraSubjects.extras).length; i++) {
        categories.add(extraSubjects.extras[i].category);
    }

    // создание ячеек с дополнительными дисциплиными, разделенных по цветам
    let categoriesArray = Array.from(categories);
    let category = getById("subjectsOfChoiceTable");
    category.innerHTML="";
    for (let i=0; i<categoriesArray.length; i++) {
        category.innerHTML += "<tr><td class='non-editable-table-cell clickable "+ Colors[i] +"' colspan='9' id='category-"+ i +"' onclick='hideElementsWithClass("+'"'+ Colors[i] + '"' +")'>"+ categoriesArray[i] +" (показать) </td></tr>";

        // подсчёт количества курсов для конкретной категории
        let categoryCourses=[];
        for (let j=0; j<Object.keys(extraSubjects.extras).length; j++) {
            if (categoriesArray[i] === extraSubjects.extras[j].category) {
                categoryCourses.push(extraSubjects.extras[j].name);
            }
            if(presetIds.includes(extraSubjects.extras[j].id.toString())) {
                presetsNames.add(extraSubjects.extras[j].name);
            }
        }

        // разделение курсов по строкам внутри категории
        let coursesContent="";
        for(let k=0; k<categoryCourses.length; k++){
            if(k===0)
                coursesContent += "<tr>";

            if(k % 9===0 && k!==0)
                coursesContent += "</tr><tr>";

            // добавление в таблицу пресетов
            if(presetsNames.has(categoryCourses[k])){
                let extraSubjectsCells = getByClassName("extraSubject");
                for (let z = 0; z < extraSubjectsCells.length; z++) {
                    if (extraSubjectsCells[z].childNodes[1] === undefined) {
                        extraSubjectsCells[z].innerHTML+="<div id='course-" + k + "_category-" + i + "' class='draggable-content " + Colors[i] + "'>" + categoryCourses[k] + "</div>";
                        break;
                    }
                }

            }
            coursesContent += "<td class='non-editable-table-cell can-be-hidden hide'><div id='course-" + k + "_category-" + i + "' class='cloneable-content draggable-content " + Colors[i] + "'>" + categoryCourses[k] + "</div></td>";

            // добавление ячеек в конец таблицы
            if(k===categoryCourses.length-1) {
                if(k % 9!==0) {
                    for(let m=0;m<9-k%9-1;m++)
                        coursesContent += "<td class='non-editable-table-cell can-be-hidden hide'><div class='non-editable-table-cell " + Colors[i] + "' style='display: none;'></div></td>"
                }
                coursesContent += "</tr>";
            }
        }
        category.innerHTML+=coursesContent;
    }
    redips.init();
}

/**
 * Очищение таблицы от выбранных курсов, чтобы можно было начать выбор заново
 */
function resetTable(){
    let extraSubjectsCells = getByClassName("extraSubject");
    for (let i = 0; i < extraSubjectsCells.length; i++) {
        if (extraSubjectsCells[i].hasChildNodes() && !(extraSubjectsCells[i].childNodes[1] === undefined)) {
            extraSubjectsCells[i].childNodes[1].remove();
        }
    }
}

/**
 * Проверка заполненности ячеек таблицы и вывод таблицы в pdf в случае полностью заполненной таблицы
 */
function checkCells() {
    let extraSubjectsCells = getByClassName("extraSubject");
    let nonEmptyCells = 0;
    userChoices = [];
    for (let i = 0; i < extraSubjectsCells.length; i++) {
        if (extraSubjectsCells[i].hasChildNodes() && !(extraSubjectsCells[i].childNodes[1] === undefined)) {
            userChoices.push(extraSubjectsCells[i].childNodes[1].textContent);
            nonEmptyCells++;
        }
    }

    // проверка на повторяющиеся курсы (можно использовать не название, а id, если потребуется)
    let duplicates = userChoices.reduce((accumulatorArray, currentValue, index, array) => {
        if(array.indexOf(currentValue)!==index && !accumulatorArray.includes(currentValue))
            accumulatorArray.push(currentValue);
        return accumulatorArray;
    }, []);

    // проверка возможности подготовки pdf-файла (если нет дубликатов и заполнены все ячейки)
    if(duplicates.length>0)
        alert("Курс может быть добавлен только один раз. Уберите или замените следующие повторяющиеся курсы: " + duplicates.join(', '));
    else if (nonEmptyCells === extraSubjectsCells.length) {
        openPopup();
    }
    else
        alert("Остались незаполненные ячейки дополнительных дисциплин");
}

/**
 * Открытие pop-up формы
 */
function openPopup() {
    let popup=getById("popup");
    if (popup == null) return;
    popup.classList.add("active");

    let overlay=getById("overlay");
    if(overlay==null) return;
    overlay.classList.add("active");

    getById("create-PDF-from-popup").addEventListener("click", ()=>{
        createPdf();
    });
}

/**
 * Закрытие pop-up формы
 */
function closePopup() {
    let popup=getById("popup");
    if (popup == null) return;
    popup.classList.remove("active");

    let overlay=getById("overlay");
    if(overlay==null) return;
    overlay.classList.remove("active");
}

/**
 * Создание pdf-файла с выборами пользователя
 * Документация: https://github.com/simonbengtsson/jsPDF-AutoTable
 */
function createPdf() {
    let baseSubjects = baseSubjectsForPdf;

    let doc = new jsPDF({
        orientation: "l", //p for portrait
        unit: "pt",
        format: "letter"
    });
    doc.addFileToVFS("./resources/fonts/times_new_roman.ttf", font);
    doc.addFont("./resources/fonts/times_new_roman.ttf", "TNR", "normal");
    doc.setFont("TNR");
    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);

    doc.autoTable({
        head: [[
            // заголовки
            { content: "Базовые дисциплины", rowSpan: 4, styles:{ halign: "center",	valign: "middle"}},
            { content: "1 курс", colSpan: 2, styles:{ halign: "center",	valign: "middle",}},
            { content: "2 курс", colSpan: 2, styles:{ halign: "center", valign: "middle",}},
            { content: "3 курс", colSpan: 2, styles:{ halign: "center", valign: "middle",}},
            { content: "4 курс", colSpan: 2, styles:{ halign: "center", valign: "middle",}},
        ]],
        body: [
            // вторая строка
            [
                {},
                {content:"1 семестр", styles:{halign: "center",	valign: "middle",}},
                {content:"2 семестр", styles:{halign: "center", valign: "middle",}},
                {content:"3 семестр", styles:{halign: "center", valign: "middle",}},
                {content:"4 семестр", styles:{halign: "center", valign: "middle",}},
                {content:"5 семестр", styles:{halign: "center", valign: "middle",}},
                {content:"6 семестр", styles:{halign: "center", valign: "middle",}},
                {content:"7 семестр", styles:{halign: "center", valign: "middle",}},
                {content:"8 семестр", styles:{halign: "center", valign: "middle",}},
            ],

            // третья строка - базовые дисциплины
            [{},baseSubjects[0].subjects, baseSubjects[1].subjects, baseSubjects[2].subjects, baseSubjects[3].subjects,
                baseSubjects[4].subjects, baseSubjects[5].subjects, baseSubjects[6].subjects,baseSubjects[7].subjects],

            // дисциплины на выбор
            [{},{content: "Дисциплины на выбор", colSpan:8,styles:{ halign: "center", valign: "middle", fillColor: [41, 128, 186], textColor: [255, 255, 255]}}],
            [
                {content: "Предмет на выбор 1", styles:{ halign: "center", valign: "middle", fillColor: [41, 128, 186], textColor: [255, 255, 255]}},
                {content: "ВЫБОР НЕДОСТУПЕН", colSpan: 2, rowSpan:3,styles:{ halign: "center", valign: "middle",}},
                {content: userChoices[0], rowSpan:3,styles:{ valign: "middle",}},
                {content: userChoices[1], styles:{}},
                {content: userChoices[2], styles:{}},
                {content: userChoices[3], styles:{}},
                {content: userChoices[4], styles:{}},
                {content: "ВЫБОР НЕДОСТУПЕН", rowSpan:3,styles:{ halign: "center", valign: "middle",}}
            ],

            [
                {content: "Предмет на выбор 2", styles:{ halign: "center", valign: "middle", fillColor: [41, 128, 186], textColor: [255, 255, 255]}},
                {content: userChoices[5], rowSpan:2, styles:{}},
                {content: userChoices[6], styles:{}},
                {content: userChoices[7], styles:{}},
                {content: userChoices[8], styles:{}},
            ],

            [
                {content: "Предмет на выбор 3", styles:{ halign: "center", valign: "middle", fillColor: [41, 128, 186], textColor: [255, 255, 255]}},
                {content: userChoices[9], styles:{}},
                {content: userChoices[10], styles:{}},
                {content: userChoices[11], styles:{}},
            ]
        ],
        styles: {
            font: "TNR",
            fontStyle: "normal",
            fontSize: 8,
            overflow: "linebreak",
            lineColor: 240,
            lineWidth: 1,
        },
        margin: {top: 60},
    });

    doc.save("student-program.pdf");

    sendStatistic();
}

/**
 * Отправление данных на сервер для сбора статистики:
 *      - уникальный id пользователя
 *      - выбранное направление обучения
 *      - дополнительные дисциплины
 */
function sendStatistic(){
    let options=getById("tabs").options;
    let selectedOption=getById("tabs").selectedIndex;

    let majorName = options[selectedOption].id.replace("major-name=", "");

    let activeProfessionOptions = getById(options[selectedOption].dataset.tabTarget.replace("#majorId","professionsFromMajorId")).options;
    let selectedProfessionOption = getById(options[selectedOption].dataset.tabTarget.replace("#majorId","professionsFromMajorId")).selectedIndex;
    let professionName = activeProfessionOptions[selectedProfessionOption].textContent;

    let data = new FormData();
    data.append("chosen_major", majorName);
    data.append("user_choices", userChoices);
    data.append("course", document.forms.course.course.value);
    data.append("profession", professionName);

    postDataByRequest("send_statistic", data);
}

window.onload=function() {};

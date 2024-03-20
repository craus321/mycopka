<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
</head>
<body>
<div class="v-main__wrap">
    <div class="container">
        <div>
            <h1>Добавить</h1>
            <form id="uploadForm" action="{{ route('upload.submit') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="box-body mt-5">
                    <div class="form-group">
                        <label for="pi-source-marker_name" class="control-label">Файл</label>
                        <input id="pi-source-marker_name" name="file" title="Файл" type="file" class="" accept=".csv">
                    </div>
                </div>
                <div class="box-footer mt-5">
                    <button type="button" onclick="uploadFile()" class="v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--default success">
                        <span class="v-btn__content">Загрузить</span>
                    </button>
                    <a href="/photo" class="btn btn-primary">Инструкция</a>

                </div>
            </form>
        </div>
        <div id="dataFormContainer"></div>
        <div id="loader" class="loader" style="display: none"></div>

    </div>
</div>

<script>
    const buttons = document.querySelectorAll('button');

    buttons.forEach(button => {
        button.addEventListener('mouseover', function() {
            this.style.transform = 'scale(1.1)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
</script>
</body>
</html>
<script>
    function uploadFile() {
        // Показываем лоадер
        document.getElementById('loader').style.display = 'block';

        var formData = new FormData(document.getElementById('uploadForm'));
        var xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                // Скрываем лоадер после получения ответа
                document.getElementById('loader').style.display = 'none';
                if (xhr.status === 200) {
                    // Обновляем контейнер с формой данными из файла
                    document.getElementById('dataFormContainer').innerHTML = xhr.responseText;
                } else {
                    console.error('Ошибка при загрузке файла:', xhr.responseText);
                    document.getElementById('dataFormContainer').innerHTML = xhr.responseText;
                }
            }
        };

        xhr.open('POST', '{{ route("upload.submit") }}', true);
        xhr.send(formData);
    }

    function saveDataToBase()
    {
        document.getElementById('loader').style.display = 'block';

        var formData1 = new FormData(document.getElementById('uploadForm'));
        // Получаем данные из формы saveToDb
        var formData2 = new FormData(document.getElementById('saveToDb'));

        // Объединяем данные из обеих форм в один объект FormData
        console.log(formData1.entries())
        var combinedFormData = new FormData();
        for (var pair of formData1.entries()) {
            combinedFormData.append(pair[0], pair[1]);
        }
        for (var pair of formData2.entries()) {
            combinedFormData.append(pair[0], pair[1]);
        }

        // Отправляем данные на сервер
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                document.getElementById('loader').style.display = 'none';

                if (xhr.status === 200) {
                    // Обработка успешного ответа от сервера
                    console.log('Данные успешно отправлены на сервер.');
                    // Редирект на другой шаблон
                    window.location.href = "{{ route('ok') }}"; // Замените 'redirect.routeName' на имя маршрута вашего шаблона
                } else {
                    // Обработка ошибки при отправке данных на сервер
                    console.error('Ошибка при отправке данных на сервер:', xhr.statusText);
                }
            }
        };

        xhr.open('POST', '{{ route('upload.saveToDatabase') }}', true);
        xhr.send(combinedFormData);


    }
</script>



<style>

    /* Стили для кнопки */
.btn {
    display: inline-block;
    padding: 8px 16px;
    border: 1px solid transparent;
    border-radius: 4px;
    background-color: #007bff;
    color: #fff;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Стили для кнопки при наведении курсора */
.btn:hover {
    background-color: #0056b3;
}

/* Стили для кнопки при активации (нажатии) */
.btn:active {
    background-color: #0056b3;
    box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}

/* Стили для кнопки при фокусе (для доступности) */
.btn:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}


    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 100%; /* Ширина контейнера по максимальной ширине экрана */
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-size: 24px;
        color: #333;
    }

    form {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        color: #555;
    }

    input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: box-shadow 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border: 2px solid #ccc;
    }

    th, td {
        padding: 10px;
        border-bottom: 1px solid #ccc;
        text-align: left;
        border-right: 1px solid #ccc;
        word-wrap: break-word; /* Перенос слов при нехватке места */
    }

    .checkbox-container {
        display: flex;
        align-items: center;
    }

    .checkbox-label {
        margin-left: 10px;
        color: #333;
    }

    .checkbox-container input[type="checkbox"],
    .checkbox-container input[type="radio"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #ccc;
        border-radius: 4px;
        margin: 0;
        transition: all 0.3s ease;
    }

    .checkbox-container input[type="checkbox"]:checked,
    .checkbox-container input[type="radio"]:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    .checkbox-container input[type="checkbox"]::before,
    .checkbox-container input[type="radio"]::before {
        content: '';
        display: block;
        width: 10px;
        height: 10px;
        margin: 3px;
        background-color: #fff;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .checkbox-container input[type="checkbox"]:checked::before,
    .checkbox-container input[type="radio"]:checked::before {
        transform: scale(1);
        background-color: #fff;
    }

    .checkbox-container input[type="checkbox"]:hover,
    .checkbox-container input[type="radio"]:hover {
        border-color: #0056b3;
    }

    .checkbox-container input[type="checkbox"]:active,
    .checkbox-container input[type="radio"]:active {
        border-color: #0056b3;
    }
    /* Стили для лоадера */
    .loader {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 50px;
        height: 50px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
        margin: 0 auto;
        margin-top: 20px;
        position: fixed; /* делаем элемент фиксированным */
        top: 50%; /* располагаем его по вертикали в середине экрана */
        left: 50%; /* располагаем его по горизонтали в середине экрана */
        transform: translate(-50%, -50%); /* центрируем элемент точно по центру экрана */
        z-index: 9999; /* устанавливаем высокий уровень z-index, чтобы он был поверх других элементов */
        opacity: 0.8; /* задаем прозрачность элемента */
    }

    /* Анимация вращения */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Успешная загрузка данных</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        .success-message {
            margin-top: 20px;
            font-size: 18px;
            color: #007bff;
        }

        #okButton {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: box-shadow 0.3s ease;
        }

        #okButton:hover {
            background-color: #0056b3;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Данные успешно загружены</h1>
    <p class="success-message">Нажмите на кнопку "OK"</p>
    <button id="okButton" onclick="redirectToHomePage()">OK</button>
</div>

<script>
    function redirectToHomePage() {
        // Перенаправление на главную страницу
        window.location.href = "/";
    }
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Увеличение фото</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .photo-container {
            position: relative;
        }
        .photo-container img {
            max-width: 100%;
            height: auto;
            cursor: pointer;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
        }
        .overlay img {
            max-width: 90%;
            max-height: 90%;
        }
        .overlay.active {
            display: flex;
        }
    </style>
</head>
<body>
<div class="photo-container">
    <img src="{{ asset('/storage/img/image.png') }}" alt="Ваша фотография">
</div>

<div class="overlay">
    <img src="{{ asset('/storage/img/image.png') }}" alt="Ваша фотография">
</div>


<script>
    document.querySelector('.photo-container img').addEventListener('click', function() {
        document.querySelector('.overlay').classList.toggle('active');
    });
</script>
</body>
</html>

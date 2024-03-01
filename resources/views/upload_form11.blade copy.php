<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
</head>
<body>
<div class="v-main__wrap">
    <div class="container">
        <div>
            <h1>Добавить</h1>
            <form action="{{ route('upload.submit') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="box-body mt-5">
                    <div class="form-group">
                        <label for="pi-source-marker_name" class="control-label">Файл</label>
                        <input id="pi-source-marker_name" name="file" title="Файл" type="file" class="">
                    </div>
                </div>
                <div class="box-footer mt-5">
                    <button type="submit" class="v-btn v-btn--is-elevated v-btn--has-bg theme--light v-size--default success"><span class="v-btn__content">Загрузить</span></button>
                </div>
            </form>
        </div>
        @if(isset($records))
            <div>
                <h2>Данные из файла CSV</h2>
                <table>
                    <thead>
                    <tr>
                        @foreach($headers as $key)
                            <th>{{ $key }}</th>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            @foreach($record as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</div>
</body>
</html>


<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
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
    }

    button:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        border-bottom: 1px solid #ccc;
        text-align: left;
    }

    thead {
        background-color: #f0f0f0;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

</style>

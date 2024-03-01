


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
                    @if(isset($headers))
                        <thead>
                            <tr>
                                @foreach($headers as $key)
                                    <th>{{ $key }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    @endif
                    <tbody>
                        @php
                            $recordsToShow = is_array($records) ? array_slice($records, 0, 50) : $records->take(50);
                        @endphp
                        @foreach($recordsToShow as $record)
                            <tr>
                                @foreach($record as $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form action="{{ route('upload.saveToDatabase') }}" method="post">
                    @csrf
                    <button type="submit">Загрузить в базу</button>
                </form>
            </div>
        @endif
    </div>
</div>
</body>
</html>



<style>



    table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border-left: 2px solid #ccc; 
    border-top: 2px solid #ccc; 
}

th, td {
    padding: 10px;
    border-bottom: 1px solid #ccc;
    text-align: left;
    border-right: 1px solid #ccc; 
}

    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1800px;
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
    }

    th, td {
        padding: 10px;
        border-bottom: 1px solid #ccc;
        text-align: left;
        border-right: 2px solid #ccc; /* толстая вертикальная граница */
    }

    th {
        background-color: #f0f0f0;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    @media only screen and (max-width: 600px) {
        .container {
            padding: 10px;
        }
        h1 {
            font-size: 20px;
        }
        button {
            padding: 8px 16px;
        }
        input[type="file"] {
            padding: 8px;
        }
        th, td {
            padding: 8px;
        }
    }
</style>
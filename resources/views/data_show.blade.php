@if(isset($records))
<form id="saveToDb" action="{{ route('upload.saveToDatabase') }}" method="post">
    <div>
        <h2>Данные из файла CSV</h2>
        <button type="button" onclick="saveDataToBase(); ">Загрузить в базу</button>
        <table>
            <tbody>
            @php
            $recordsToShow = is_array($records) ? array_slice($records, 0, 50) : $records->take(50);
            @endphp
            @php($i = 0)
            @foreach($recordsToShow as $record)
            <tr>
                @foreach($record as $key => $value)
                @if($i == 0 && false)
                <td>
                    <div class="field-container">
                        <span>{{ $value }}</span>
                        <input type="text" name="fieldName[{{$key}}][name]" placeholder="Новое название">
                        <input type="text" hidden="" name="fieldName[{{$key}}][attributeName]" value=<?= $value ?>>
                        <label class="checkbox-container">
                            <input name="fieldName[{{$key}}][needSave]" type="checkbox">
                            <span class="checkbox-label">Сохранить в базу данных</span>
                        </label>
                    </div>
                </td>
                @else
                <td>{{ $value }}</td>
                @endif
                @endforeach
            </tr>
            @php($i++)
            @endforeach
            </tbody>
        </table>
        @csrf
        <br>

    </div>
</form>

@endif

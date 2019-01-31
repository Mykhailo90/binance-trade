@extends('layouts.app')

@section('content')
    <div>
        <h3>Тайминг обработки информации</h3>
        <h2 class="green-text"><span id="timer" data="0">30</span> cек</h2>
        @if ($cast->count() && $countCurrency)
            <button type="button" id="start" class="btn btn-success btn-lg btn-block">Запуск мониторинга</button>
            <button type="button" id="stop" class="btn btn-danger btn-lg btn-block">Остановить мониторинг</button>
        @else
            <button type="button" id="start" class="btn btn-success btn-lg btn-block" disabled="disabled">Запуск мониторинга</button>
            <div class="alert alert-warning" role="alert">
                ***Для запуска мониторинга необходимо добавить валюты и иметь хоть один стартовый слепок!
            </div>
            <button type="button" id="stop" class="btn btn-danger btn-lg btn-block" disabled="disabled">Остановить мониторинг</button>
        @endif

        <button type="button" id="cast" class="btn btn-primary btn-lg btn-block">Cделать слепок</button>
        <input type="name" class="cast form-control" id="cast_name" placeholder="Имя слепка" style="display: none">
        <button type="button" id="save-cast" class="btn btn-success btn-lg btn-block cast" style="display: none">Сохранить</button>
    </div>
    <div>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Мониторинг изменения количества валют
                @if (!$globalParams)
                    <span class="badge badge-danger badge-pill">Не установлено</span>
                @elseif ($globalParams->check_new_pairs == 0)
                    <span class="badge badge-danger badge-pill">Выключено</span>
                @else
                    <span class="badge badge-primary badge-pill">Включено</span>
                @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Количество валютных пар при мониторинге
                <span class="badge badge-primary badge-pill">{{ $countCurrency  }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Количество сохраненных слепков
                @if (!$cast || $cast->count() == 0)
                    <span class="badge badge-danger badge-pill">0</span>
                @else
                    <span class="badge badge-primary badge-pill">{{ $cast->count()  }}</span>

                @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Количество новых уведомлений
                @if ($countAlarms == 0)
                    <span class="badge badge-primary badge-pill">0</span>
                @else
                    <span class="badge badge-danger badge-pill">{{ $countAlarms }}</span>
                @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Глобальный параментр роста валют
                @if (!$globalParams)
                    <span class="badge badge-danger badge-pill">Не установлено</span>
                @else
                    <span class="badge badge-primary badge-pill">{{ $globalParams->max_value }} %</span>
                @endif

            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Глобальный параметр снижения стоимости

                @if (!$globalParams)
                    <span class="badge badge-danger badge-pill">Не установлено</span>
                @else
                    <span class="badge badge-primary badge-pill">{{ $globalParams->min_value }} %</span>
                @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Опция звукового уведомления при наличии сообщений

                @if (!$globalParams)
                    <span class="badge badge-danger badge-pill">Не установлено</span>
                @elseif ($globalParams->use_sound_alert == 0)
                    <span class="badge badge-danger badge-pill">Выключено</span>
                @else
                    <span class="badge badge-primary badge-pill">Включено</span>
                @endif
            </li>

        </ul>
    </div>
    <script>
        function incTimer() {
            var currentSeconds = totalSecs % 60;
            // if(currentSeconds <= 9) currentSeconds = "0" + currentSeconds;
            totalSecs--;
            $("#timer").text(currentSeconds);
            if (totalSecs > 0 && $("#timer").attr('data') == 1)
                setTimeout('incTimer()', 1000);
            if (totalSecs == 0)
                location.reload();
        }

        totalSecs = 30;

        $(document).ready(function() {
            $("#save-cast").click(function() {

                var nameCast = $("#cast_name").val();
                if (nameCast.trim().length > 0){
                    $.post( "http://binance-trade.local/api/cast-create", { 'castName': nameCast  })
                        .done(function() {
                            alert('Слепок успешно создан!');
                            location.reload();
                        });
                }
            });

            $("#start").click(function() {
                $("#timer").attr("data", 1);
                incTimer();
            });

            $("#cast").click(function() {
                $(".cast").toggle();
            });

            $("#stop").click(function() {
                $("#timer").attr("data", 0);
            });
            if ($("#timer").attr('data') == 1)
                incTimer();
        });
    </script>
@endsection

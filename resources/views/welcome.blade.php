@extends('layouts.app')

@section('content')
    <div>
        <h3>Тайминг обработки информации</h3>
        @if ($monitoringState && $monitoringState->state == 1)
            <h2 class="green-text"><span id="timer" data="0">{{ $monitoringState->timer }}</span> cек</h2>
        @else
            <h2 class="green-text"><span id="timer" data="0">30</span> cек</h2>
        @endif
        @if ($cast->count() && $countCurrency && (!$monitoringState || $monitoringState->state == 0))
            <button type="button" id="start" class="btn btn-success btn-lg btn-block monitoring">Запуск мониторинга</button>
        @elseif ($monitoringState->state == 1)
            <button type="button" id="stop" class="btn btn-danger btn-lg btn-block monitoring">Процесс запущен / Остановить</button>
        @else
            <button type="button" id="start" class="btn btn-success btn-lg btn-block" disabled="disabled">Запуск мониторинга</button>
            <div class="alert alert-warning" role="alert">
                ***Для запуска мониторинга необходимо добавить валюты и иметь хоть один стартовый слепок!
            </div>
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
        totalSecs = <?php echo  ($monitoringState) ? $monitoringState->timer : 30; ?>;
        state = <?php echo  ($monitoringState) ? $monitoringState->state : 0; ?>;


        function incTimer() {
            var currentSeconds = totalSecs % 60;
            totalSecs--;
            $("#timer").text(currentSeconds);
            if (totalSecs > 0)
                setTimeout('incTimer()', 1000);
            if (totalSecs == 0)
            {
                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/start-monitoring-process?state=1&timer=30",
                    cache: false,
                    success: function(html){
                        location.reload();
                    }
                });
            }
        }



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

            $(".monitoring").click(function() {
                if (this.id == 'start'){
                    $(".monitoring").attr('id', 'stop');
                    $('.monitoring').html('Процесс запущен / Остановить');
                    $('.monitoring').removeClass('btn-success');
                    $('.monitoring').addClass('btn-danger');
                    $("#timer").attr("data", 1);
                    incTimer();
                }
                else if(this.id == 'stop'){
                    $(".monitoring").attr('id', 'start');
                    $('.monitoring').html('Запустить мониторинг');
                    $('.monitoring').removeClass('btn-danger');
                    $('.monitoring').addClass('btn-success');
                    $("#timer").attr("data", 0);
                }

            });

            $("#cast").click(function() {
                $(".cast").toggle();
            });

            if (state == 1)
                incTimer();
        });
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div>
        <h3>Тайминг обработки информации</h3>
        <h2 class="green-text"><span id="timer" data="0">30</span> cек</h2>
        <button type="button" id="start" class="btn btn-success btn-lg btn-block">Запуск мониторинга</button>
        <button type="button" id="stop" class="btn btn-danger btn-lg btn-block">Остановить мониторинг</button>
    </div>
    <div>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Мониторинг изменения количества валют
                <span class="badge badge-primary badge-pill">Включено</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Количество валютных пар при мониторинге
                <span class="badge badge-primary badge-pill">438</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Количество пар исключенных из мониторинга
                <span class="badge badge-primary badge-pill">12</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Количество новых уведомлений
                <span class="badge badge-primary badge-pill">0</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Глобальный параментр роста валют
                <span class="badge badge-primary badge-pill">30 %</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Глобальный параметр снижения стоимости
                <span class="badge badge-primary badge-pill">50 %</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Опция звукового уведомления при наличии сообщений
                <span class="badge badge-primary badge-pill">Включена</span>
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
            $("#start").click(function() {
                $("#timer").attr("data", 1);
                incTimer();
            });
            $("#stop").click(function() {
                $("#timer").attr("data", 0);
            });
            if ($("#timer").attr('data') == 1)
                incTimer();
        });
    </script>
@endsection

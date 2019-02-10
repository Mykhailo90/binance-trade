@extends('layouts.app')

@section('content')
    <style>
        span{
            font-size: 1vw;
        }
    </style>
    <h3>Новые уведомления</h3>
    @if ($newAlarms->count() == 0)
        <div class="alert alert-primary" role="alert">
            ***На данный момент новых уведомлений нет!
        </div>
    @else
        <button type="button" id="check-all" class="btn btn-success btn-lg btn-block">Добавить все в историю</button>
        @foreach($newAlarms as $alarm)
            <ul class="list-group" style="margin-top: 30px;">
                <li class="list-group-item d-flex justify-content-between align-items-center" style="color: #1d643b; font-size: large;">
                    <button type="button" id="{{$alarm->id}}" class="btn btn-success btn-count alarm-save" style="margin-left: 30px;">В историю</button>
                    <span>{{ $alarm->title }}</span>
                    <span>{{ $alarm->pair_name }}</span>
                    <span style="color: #761b18">{{ $alarm->text }}</span>
                    <span>{{ $alarm->date }}</span>

                    <button type="button" id="{{$alarm->id}}" class="btn btn-danger btn-count alarm-delete" style="margin-left: 30px;">Удалить</button>
                </li>
            </ul>
        @endforeach
    @endif

    <h3 style="margin-top: 30px">Просмотренные уведомления</h3>
    @if ($oldAlarms->count() == 0)
        <div class="alert alert-primary" role="alert" id="stringAlarm">
            ***На данный момент сохраненных уведомлений не обнаружено!
        </div>
    @else
        <button type="button" id="dell-all" class="btn btn-outline-danger btn-lg btn-block">Очистить историю уведомлений</button>
        @foreach($oldAlarms as $alarm)
            <ul class="list-group" style="margin-top: 30px;" id="listOldAlarm">
                <li class="list-group-item d-flex justify-content-between align-items-center" style="color: #1d643b; font-size: large;">
                    <span>{{ $alarm->title }}</span>
                    <span>{{ $alarm->pair_name }}</span>
                    <span style="color: #761b18">{{ $alarm->text }}</span>
                    <span>{{ $alarm->date }}</span>
                    <button type="button" id="{{$alarm->id}}" class="btn btn-danger btn-count alarm-delete" style="margin-left: 30px;">Удалить</button>
                </li>
            </ul>
        @endforeach
    @endif
    <script>

        $(document).ready(function() {

            var newAlarms = <?php echo  $newAlarms->count(); ?>;

            if (newAlarms){
                $("#alarmsPage").css("color", "red");
            }

            $(".alarm-save").click(function() {
                let alarmId = this.id;


                    $.post( "http://binance-trade.local/api/alarm-save", { 'id': alarmId  })
                        .done(function() {
                            alert('Объект перемещен в архив!');
                            location.reload();
                        });

            });

            $(".alarm-delete").click(function() {
                let alarmId = this.id;


                $.post( "http://binance-trade.local/api/alarm-delete", { 'id': alarmId  })
                    .done(function() {
                        alert('Объект удален безвозвратно!');
                        location.reload();
                    });

            });

            $("#check-all").click(function() {
                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/alarm-save-all",
                    cache: false,
                    success: function(html){
                        alert("Операция выполнена успешно!");
                        location.reload();
                    }
                });
            });

            $("#dell-all").click(function() {
                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/alarm-delete-all",
                    cache: false,
                    success: function(html){
                        alert("Операция выполнена успешно!");
                        location.reload();
                    }
                });
            });

            setTimeout(function() {
                location.reload();
            }, 10000);

        });
    </script>
@endsection

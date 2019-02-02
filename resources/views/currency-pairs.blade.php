@extends('layouts.app')

@section('content')
    <style>
        .red{
            color: #b91d19;
        }
    </style>
    <div class="text-center">
        <button type="button" id="update-currency" class="btn btn-primary btn-lg btn-block">Обновить список валют биржи</button>
        <p>***Рекомендуется обновлять после срабатывания уведомления о новых валютах или изменении статуса!</p>
    </div>
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Список отслеживаемых пар
                    </button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="card-body">
                    <div style="margin-bottom: 20px">
                        @if (count($monitoringList) != 0)
                            <button type="button" id="del-all" class="btn btn-danger btn-lg btn-block">Очистить список</button>
                        @endif
                        {{--<button type="button" id="add-chosen" class="btn btn-warning btn-lg btn-block" disabled="disabled">Добавить выбранные</button>--}}
                    </div>
                    <div id="currency-list">
                        @if (count($monitoringList) === 0)
                            <div class="alert alert-warning" role="alert">
                                Добавьте валюдные пары в список для мониторинга!!!
                            </div>
                        @else
                            <div class="container">
                                @foreach ($monitoringList as $item)
                                    <div class="row" id="row_{{ $item->id }}" style="margin-bottom: 10px">
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->name }}</span>
                                        </div>
                                        <div class="col-sm">
                                           <input type="number" style="color: #761b18" id="min_{{ $item->id }}" value="{{ $item->min_value }}">
                                        </div>
                                        <div class="col-sm">
                                            <input type="number" style="color: #2d995b" id="max_{{ $item->id }}" value="{{ $item->max_value }}">
                                        </div>
                                        <div class="col-sm">
                                                <button class="btn btn-outline-danger currency-btn-monitoring" type="button" data="{{ $item->id }}" id="btn_{{ $item->id }}">Удалить из мониторинга</button>
                                        </div>
                                        <div class="col-sm">
                                            <button class="btn btn-outline-primary currency-btn-change" type="button" data="{{ $item->id }}" id="btn_{{ $item->id }}">Изменить параметры</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Список валютных пар биржи
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <div style="margin-bottom: 20px; text-align: center">
                        @if (($checkParams === 1) && (count($binanceList) != count($monitoringList)))
                            <button type="button" id="add-all" class="btn btn-success btn-lg btn-block">Добавить все валюты</button>
                        @else
                            <button type="button" id="add-all" class="btn btn-success btn-lg btn-block" disabled="disabled">Добавить все валюты</button>
                            <p>***Необходимо установить глобальные параметры!!!</p>
                        @endif

                        {{--<button type="button" id="add-chosen" class="btn btn-warning btn-lg btn-block" disabled="disabled">Добавить выбранные</button>--}}
                    </div>
                    <div id="currency-list">
                        @if (count($binanceList) === 0)
                            <div class="alert alert-warning" role="alert">
                               Необходимо обновить список валютных пар!!!
                            </div>
                        @else
                            <div class="container">
                                @foreach ($binanceList as $item)
                                    <div class="row" style="margin-bottom: 10px">
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->status }}</span>
                                        </div>
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->name }}</span>
                                        </div>
                                        <div class="col-sm">
                                            <input type="number" style="color: #761b18" placeholder="% падения" id="binance_min_{{$item->id}}">
                                        </div>
                                        <div class="col-sm">
                                            <input type="number" style="color: #2d995b" placeholder="% роста" id="binance_max_{{$item->id}}">
                                        </div>
                                        <div class="col-sm">
                                            @if ($item->monitoring === 0)
                                                <button class="btn btn-outline-success currency-btn-add" type="button" data="{{ $item->id }}" id="binance_btn_{{ $item->id }}">Добавить</button>
                                            @else
                                                <button class="btn btn-outline-warning" type="button" disabled="disabled">Мониторинг</button>
                                            @endif

                                        </div>
                                    </div>


                                {{--<div class="input-group">--}}
                                    {{--<div class="input-group-prepend">--}}
                                        {{--<span class="input-group-text">{{ $item->name }}</span>--}}
                                    {{--</div>--}}
                                    {{--<input type="number" aria-label="Min value" class="form-control">--}}
                                    {{--<input type="number" aria-label="Max value" class="form-control">--}}
                                    {{--<button class="btn btn-outline-secondary" type="button" id="{{ $item->id }}">Button</button>--}}
                                {{--</div>--}}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {

            var newAlarms = <?php echo  $newAlarms->count(); ?>;

            if (newAlarms){
                $("#alarmsPage").css("color", "red");
            }

            var monitoringListCount = <?php echo  count($monitoringList); ?>;
            var currencyListCount = <?php echo  count($binanceList); ?>;

            if (monitoringListCount == currencyListCount)
            {
                $("#add-all").prop('disabled',true);
            }

            $(".currency-btn-add").click(function(){
                currencyId = $(this).attr('data');

                currencyMin =$("#binance_min_"+ currencyId).val();
                currencyMax =$("#binance_max_"+ currencyId).val();

                if (currencyMin > 0 && currencyMax > 0)
                {
                    $.post( "http://binance-trade.local/api/add-currency", { 'id': currencyId, 'min': currencyMin, 'max': currencyMax })
                        .done(function() {
                            $("#binance_btn_"+ currencyId).prop('disabled', 'disable');
                            $("#binance_btn_"+ currencyId).text('Мониторинг');
                            $("#binance_btn_"+ currencyId).removeClass('btn-success').addClass('btn-outline-warning');
                            alert('Данные Добавлены!');
                        });
                }
                else alert('Введите корректные значения!');

            });

            $(".currency-btn-change").click(function(){
                id = $(this).attr('data');
                min =$("#min_"+ id).val();
                max =$("#max_"+ id).val();

                if (min > 0 && max > 0)
                {
                    $.post( "http://binance-trade.local/api/update-settings", { 'id': id, 'min': min, 'max': max })
                        .done(function() {
                            alert('Данные обновлены!');
                        });
                }
                else alert('Введите корректные значения!');

            });

            $(".currency-btn-monitoring").click(function(){
                elementId = $(this).attr('data');

                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/delete-currency/"+elementId,
                    cache: false,
                    success: function(html){

                        $("#row_"+elementId).remove();
                        monitoringListCount -= 1;
                        if (monitoringListCount == 0)
                        {
                            location.reload();
                        }
                    }
                });
            });

            $("#update-currency").click(function() {
                $("#update-currency").text('Процесс обновления информации запущен!');
                $("#update-currency").removeClass("btn-primary");
                $("#update-currency").addClass("btn-warning");
                $("#update-currency").prop('disabled',true);
                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/update-currency",
                    cache: false,
                    success: function(html){
                        location.reload();
                    }
                });
            });

            $("#add-all").click(function () {
                $("#add-all").text('Процесс добавления информации запущен!');
                $("#add-all").removeClass("btn-success");
                $("#add-all").addClass("btn-warning");
                $("#add-all").prop('disabled',true);
                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/add-all-currency",
                    cache: false,
                    success: function(html){
                        location.reload();
                    }
                });
            });

            $("#del-all").click(function() {

                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/delete-monitoring-list",
                    cache: false,
                    success: function(html){
                        location.reload();
                    }
                });
            });
            if ($("#timer").attr('data') == 1)
                incTimer();
        });
    </script>
@endsection

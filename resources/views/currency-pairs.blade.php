@extends('layouts.app')

@section('content')
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
                    <div id="currency-list">
                        @if (count($binanceList) === 0)
                            <div class="alert alert-warning" role="alert">
                                Необходимо обновить список валютных пар!!!
                            </div>
                        @else
                            <div class="container">
                                @foreach ($binanceList as $item)
                                    <div class="row">
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->status }}</span>
                                        </div>
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->name }}</span>
                                        </div>
                                        <div class="col-sm">
                                            <input type="number" placeholder="% падения" id="min_{{$item->id}}">
                                        </div>
                                        <div class="col-sm">
                                            <input type="number" placeholder="% роста" id="max_{{$item->id}}">
                                        </div>
                                        <div class="col-sm">
                                            @if ($item->monitoring === 0)
                                                <button class="btn btn-outline-success currency-btn" type="button" id="btn_{{ $item->id }}">Добавить</button>
                                            @else
                                                <button class="btn btn-outline-warning" type="button" disabled="disabled">Мониторинг</button>
                                            @endif

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
                    <div style="margin-bottom: 20px">
                        <button type="button" id="add-all" class="btn btn-success btn-lg btn-block">Добавить все валюты</button>
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
                                    <div class="row">
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->status }}</span>
                                        </div>
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->name }}</span>
                                        </div>
                                        <div class="col-sm">
                                            <input type="number" placeholder="% падения" id="min_{{$item->id}}">
                                        </div>
                                        <div class="col-sm">
                                            <input type="number" placeholder="% роста" id="max_{{$item->id}}">
                                        </div>
                                        <div class="col-sm">
                                            @if ($item->monitoring === 0)
                                                <button class="btn btn-outline-success currency-btn" type="button" id="btn_{{ $item->id }}">Добавить</button>
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
            $("#update-currency").click(function() {
                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/update-currency",
                    cache: false,
                    success: function(html){
                        location.reload();
                    }
                });
            });

            $("#stop").click(function() {
                $("#timer").attr("data", 0);
            });
            if ($("#timer").attr('data') == 1)
                incTimer();
        });
    </script>
@endsection

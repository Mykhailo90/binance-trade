@extends('layouts.app')

@section('content')
    <style>
        #listName, #listNameSave, #listNameValue, #add-all{
            margin-bottom: 10px;
        }
    </style>
    <div class="text-center">
        <button type="button" id="update-currency" class="btn btn-primary btn-lg btn-block">Обновить список валют биржи</button>
        <p>***Рекомендуется обновлять после срабатывания уведомления о новых валютах или изменении статуса!</p>
    </div>

    <button type="button" id="listName" class="btn btn-success btn-lg btn-block">Cоздать мониторинг лист</button>
    <input type="text" class="listName form-control" id="listNameValue" placeholder="Имя мониторинг листа" style="display: none">
    <button type="button" id="listNameSave" class="btn btn-success btn-lg btn-block listName" style="display: none">Сохранить</button>

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
                    <div class="container">
                        <?php $listTmp = '';?>
                        @if (count($monitoringList) != 0)
                            @foreach ($monitoringList as $item)
                                @if ($listTmp != $item->listname->name)
                                <div style="margin-bottom: 20px">
                                    <button type="button" id="{{ $item->listname->id }}" class="btn btn-danger btn-lg btn-block del-all">Очистить список</button>
                                </div>
                                <?php $listTmp = $item->listname->name?>
                                @endif
                                <div id="currency-list">
                                    <div class="row" id="row_{{ $item->id }}" style="margin-bottom: 10px">
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->listname->name }}</span>
                                        </div>
                                        <div class="col-sm">
                                            <span class="currency-title">{{ $item->symbol }}</span>
                                        </div>
                                        <div class="col-sm">
                                           <input type="number" style="color: #761b18" id="min_{{ $item->id }}" value="{{ $item->min_value }}">
                                        </div>
                                        <div class="col-sm">
                                            <input type="number" style="color: #2d995b" id="max_{{ $item->id }}" value="{{ $item->max_value }}">
                                        </div>
                                        <div class="col-sm">
                                                <button class="btn btn-outline-danger currency-btn-monitoring" type="button" data="{{ $item->id }}" id="btn_{{ $item->id }}">Удалить из листа</button>
                                        </div>
                                        <div class="col-sm">
                                            <button class="btn btn-outline-primary currency-btn-change" type="button" data="{{ $item->id }}" id="btn_{{ $item->id }}">Изменить параметры</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning" role="alert">
                                Добавьте валюдные пары в список для мониторинга!!!
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
                        @if ($checkParams === 1 && $listNames->count())
                            <button type="button" id="add-all" class="btn btn-success btn-lg btn-block">Добавить все валюты</button>
                        @else
                            <button type="button" id="add-all" class="btn btn-success btn-lg btn-block" disabled="disabled">Добавить все валюты</button>
                            <p>***Необходимо установить глобальные параметры!!!</p>
                        @endif
                            <div class="input-group add-all">
                                <select class="custom-select" id="nameOfList" aria-label="Monitoring List Name">
                                    <option selected>Выбор имени списка: </option>
                                    @foreach($listNames as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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
                                            <div class="input-group add-one">
                                                <select class="custom-select nameOfListOne" id="nameOfListOne_{{$item->id}}" aria-label="Monitoring List Name">
                                                    <option selected>Список: </option>
                                                    @foreach($listNames as $n)
                                                        <option value="{{$n->id}}">{{$n->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                                <button class="btn btn-outline-success currency-btn-add" type="button"  data="{{ $item->id }}" id="binance_btn_{{ $item->id }}">Добавить</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $("#listNameSave").click(function() {
            var name = $("#listNameValue").val();
            if (name.trim().length > 0){
                $.post( "http://binance-trade.local/api/list-name-create", { 'name': name  })
                    .done(function() {
                        alert('Список успешно создан!');
                        location.reload();
                    });
            }
        });

        $("#add-all").click(function () {
            let id = $("#nameOfList").val();

            if (parseInt(id)){

                $("#add-all").text('Процесс добавления информации запущен!');
                $("#add-all").removeClass("btn-success");
                $("#add-all").addClass("btn-warning");

                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/add-all-currency?id=" + id,
                    cache: false,
                    success: function(html){
                        location.reload();
                    }
                });
            }
            else{
                alert('Выберите имя списка мониторинга')
            }
        });

        $("#listName").click(function() {
            $(".listName").toggle();
        });



        $(document).ready(function() {

            var newAlarms = <?php echo  $newAlarms->count(); ?>;

            if (newAlarms){
                $("#alarmsPage").css("color", "red");
            }

            var monitoringListCount = <?php echo  count($monitoringList); ?>;
            var currencyListCount = <?php echo  count($binanceList); ?>;


            $(".currency-btn-add").click(function(){
                currencyId = $(this).attr('data');
                currencyMin =$("#binance_min_"+ currencyId).val();
                currencyMax =$("#binance_max_"+ currencyId).val();
                listId = $("#nameOfListOne_"+currencyId).val();
                if (parseInt(listId)){
                        $.post( "http://binance-trade.local/api/add-currency", { 'id': currencyId, 'min': currencyMin, 'max': currencyMax, 'listId': listId })
                            .done(function() {
                                alert('Данные Добавлены!');
                            });
                }
                else
                    alert('Выберите имя мониторинг листа!');

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



            $(".del-all").click(function() {
                listNameId = this.id;

                $.ajax({
                    type: "GET",
                    url: "http://binance-trade.local/api/delete-monitoring-list?nameListId="+listNameId,
                    cache: false,
                    success: function(html){
                        location.reload();
                    }
                });
            });

        });
    </script>
@endsection

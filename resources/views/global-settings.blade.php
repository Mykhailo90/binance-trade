@extends('layouts.app')

@section('content')
    <div>
        <h3>Глобальные настройки мониторинга</h3>
        @if ($globalParams)
            <button type="button" class="btn btn-primary btn-lg btn-block settings-save">Изменить настройки</button>
        @else
            <button type="button" class="btn btn-success btn-lg btn-block settings-save">Cохранить настройки</button>
        @endif


    </div>
    <div>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Мониторинг изменения количества валют
                @if (($globalParams && $globalParams->check_new_pairs == 0) || !$globalParams)
                    <button type="button" id="on-count-currency" class="btn btn-primary">Включить</button>
                @else
                    <button type="button" id="off-count-currency" class="btn btn-success">Выключить</button>
                @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Опция звукового уведомления
                @if (($globalParams && $globalParams->use_sound_alert == 0) || !$globalParams)
                    <button type="button" id="on-sound-alert" class="btn btn-primary">Включить</button>
                @else
                    <button type="button" id="off-sound-alert" class="btn btn-success">Выключить</button>
                @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Глобальный % роста
                <input type="number" id="binance_max" class="input-params">
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Глобальный % падения
                <input type="number" id="binance_min" class="input-params">
            </li>

        </ul>
    </div>
    <script>
        function initParams() {
            minValue = <?php
                if ($globalParams)
                    echo  ($globalParams->min_value);
                else
                    echo (0);
                ?>

                maxValue = <?php
            if ($globalParams)
                echo  ($globalParams->min_value);
            else
                echo (0);
            ?>


        }


        function checkBtnStatus() {

            if (minValue != 0 && maxValue != 0)
            {
                $(".settings-save").removeAttr("disabled");
            }
            else{
                $(".settings-save").prop('disabled', 'disable');
            }
        }

        $(document).ready(function() {
            initParams();
            checkBtnStatus();

            $(".input-params").change(function () {
                minValue = $("#binance_min").val();
                maxValue = $("#binance_max").val();

                checkBtnStatus();
            });

            $(".settings-save").click(function () {

            });

        });
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div>
        <h3>Глобальные настройки мониторинга</h3>
        @if ($globalParams)
            <button type="button" class="btn btn-primary btn-lg btn-block settings-save">Изменить настройки</button>
        @else
            <button type="button" class="btn btn-success btn-lg btn-block settings-save">Cохранить настройки</button>
        @endif
        <h3 style="color: #761b18">***При изменении глобальных настроек не забудьте проверить настройки валютных пар добавленных раннее!
            Валютные пары, добавленные раннее, сохраняют настройки, действующие до внесения изменений!
        </h3>


    </div>
    <div>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Мониторинг изменения количества валют
                @if (($globalParams && $globalParams->check_new_pairs == 0) || !$globalParams)
                    <button type="button" id="on-count-currency" class="btn btn-primary btn-count">Включить</button>
                @else
                    <button type="button" id="off-count-currency" class="btn btn-danger btn-count">Выключить</button>
                @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Опция звукового уведомления
                @if (($globalParams && $globalParams->use_sound_alert == 0) || !$globalParams)
                    <button type="button" id="on-sound-alert" class="btn btn-primary btn-sound">Включить</button>
                @else
                    <button type="button" id="off-sound-alert" class="btn btn-danger btn-sound">Выключить</button>
                @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Глобальный % роста
                <input type="number" id="binance_max" class="input-params"
                       @if ($globalParams)
                            value="{{ $globalParams->max_value }}">
                       @endif
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Глобальный % падения
                <input type="number" id="binance_min" class="input-params"
                       @if ($globalParams)
                            value="{{ $globalParams->min_value }}">
                       @endif

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

            btnSound = <?php
            if ($globalParams)
                echo  ($globalParams->use_sound_alert);
            else
                echo (0);
            ?>

            btnCount = <?php
            if ($globalParams)
                echo  ($globalParams->check_new_pairs);
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

            if (btnSound == 0)
            {
                $(".btn-sound").addClass('btn-primary');
                $(".btn-sound").removeClass('btn-danger');
                $(".btn-sound").attr('id', 'on-sound-alert');
                $(".btn-sound").text('Включить');
            }
            else{
                $(".btn-sound").removeClass('btn-primary');
                $(".btn-sound").addClass('btn-danger');
                $(".btn-sound").attr('id', 'off-sound-alert');
                $(".btn-sound").text('Выключить');
            }

            if (btnCount == 0)
            {
                $(".btn-count").addClass('btn-primary');
                $(".btn-count").removeClass('btn-danger');
                $(".btn-count").attr('id', 'on-count-currency');
                $(".btn-count").text('Включить');
            }
            else {
                $(".btn-count").removeClass('btn-primary');
                $(".btn-count").addClass('btn-danger');
                $(".btn-count").attr('id', 'off-count-currency');
                $(".btn-count").text('Выключить');
            }
        }

        $(document).ready(function() {
            initParams();
            checkBtnStatus();
            var newAlarms = <?php echo  $newAlarms->count(); ?>;

            if (newAlarms){
                $("#alarmsPage").css("color", "red");
            }

            $(".input-params").change(function () {
                minValue = $("#binance_min").val();
                maxValue = $("#binance_max").val();

                checkBtnStatus();
            });

            $(".settings-save").click(function () {
                $.post( "http://localhost:8089/api/add-global-settings", { 'checkCount': btnCount, 'checkSound': btnSound, 'min': minValue, 'max': maxValue })
                    .done(function() {
                        alert('Настройки успешно сохранены! Можете добавить валютные пары и запустить мониторинг!');
                    });
            });

            $(".btn-count").click(function () {
                let id = $(".btn-count").attr('id');
                if (id == 'on-count-currency')
                    btnCount = 1;
                else
                    btnCount = 0;
                checkBtnStatus();
            });

            $(".btn-sound").click(function () {
                let id = $(".btn-sound").attr('id');
                if (id == 'on-sound-alert')
                    btnSound = 1;
                else
                    btnSound = 0;
                checkBtnStatus();
            });

            setTimeout(function() {
                location.reload();
            }, 30000);

        });
    </script>
@endsection

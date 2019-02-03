@extends('layouts.app')

@section('content')
    <h3>Слепки состояний валютных пар</h3>

    <button type="button" id="cast" class="btn btn-primary btn-lg btn-block">Cделать слепок</button>
    <input type="name" class="cast form-control" id="cast_name" placeholder="Имя слепка" style="display: none">
    <button type="button" id="save-cast" class="btn btn-success btn-lg btn-block cast" style="display: none">Сохранить</button>

    @if ($allCast->count() == 0)
        <div class="alert alert-primary" role="alert">
            ***На данный момент сохраненных слепков состояний не обнаружено!
        </div>
    @else
        @foreach($castNames as $castName)
            <ul class="list-group" style="margin-top: 30px;">

                <li class="list-group-item d-flex justify-content-center align-items-center" style="color: #1d643b; font-size: large;">
                    {{ $castName }}

                    <button type="button" id="{{$castName}}" class="btn btn-danger btn-count cast-del" style="margin-left: 30px;">Удалить</button>
                </li>
                @foreach($allCast->where('name', $castName) as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item->symbol }}
                        <span class="badge badge-primary badge-pill">{{ $item->last_price }}</span>
                    </li>
                @endforeach

            </ul>
        @endforeach
    @endif

    <script>

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

        $("#cast").click(function() {
            $(".cast").toggle();
        });


        $(document).ready(function() {

            var newAlarms = <?php echo  $newAlarms->count(); ?>;

            if (newAlarms){
                $("#alarmsPage").css("color", "red");
            }

            $(".cast-del").click(function() {
                let nameCast = this.id;

                if (nameCast.trim().length > 0){
                    $.post( "http://binance-trade.local/api/cast-delete", { 'castName': nameCast  })
                        .done(function() {
                            alert('Удаление прошло успешно!');
                            location.reload();
                        });
                }
            });

        });
    </script>
@endsection

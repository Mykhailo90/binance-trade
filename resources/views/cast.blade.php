@extends('layouts.app')

@section('content')
    <h3>Слепки состояний валютных пар</h3>

    <button type="button" id="cast" class="btn btn-primary btn-lg btn-block">Cделать слепок</button>
    <div class="input-group add-all">
        <select class="custom-select" id="nameOfList" aria-label="Monitoring List Name">
            <option selected>Выбор имени списка: </option>
            @foreach($listNames as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
    </div>


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

        $("#cast").click(function() {
            var nameCast = $("#nameOfList").val();
            if (parseInt(nameCast)){
                $.post( "http://localhost:8089/api/cast-create", { 'castNameId': nameCast  })
                    .done(function() {
                        alert('Слепок успешно создан!');
                        location.reload();
                    });
            }
            else alert('Выберите имя списка мониторинга для слепка!')
        });


        $(document).ready(function() {

            var newAlarms = <?php echo  $newAlarms->count(); ?>;

            if (newAlarms){
                $("#alarmsPage").css("color", "red");
            }

            $(".cast-del").click(function() {
                let nameCast = this.id;

                if (nameCast.trim().length > 0){
                    $.post( "http://localhost:8089/api/cast-delete", { 'castName': nameCast  })
                        .done(function() {
                            alert('Удаление прошло успешно!');
                            location.reload();
                        });
                }
            });

            setTimeout(function() {
                location.reload();
            }, 10000);

        });
    </script>
@endsection

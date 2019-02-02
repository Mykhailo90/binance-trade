@extends('layouts.app')

@section('content')
    <style>
        span{
            font-size: 1vw;
        }
        .header{
            font-weight: bold;
            font-size: 1.2vw;
        }
        .red{
            color: #b91d19;
        }
    </style>
    <h3>Обзор последних изменений</h3>
    @if ($overview->count() == 0)
        <div class="alert alert-primary" role="alert">
            ***На данный момент информации об изменениях не поступало!
        </div>
    @else
        <ul class="list-group" style="margin-top: 30px;">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="header">Слепок</span>
                <span class="header">Валютная пара</span>
                <span class="header">Начальная цена</span>
                <span class="header">Актуальная цена</span>
                <span class="header">Изменение цены</span>
            </li>
        @foreach($overview as $alarm)

                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <span>{{ $alarm->cast_name }}</span>
                    <span>{{ $alarm->symbol }}</span>
                    <span>{{ $alarm->first_price }}</span>
                    <span>{{ $alarm->price }}</span>
                    @if ($alarm->percent_change > 0)
                        <span style="color: #2a9055">{{ $alarm->percent_change }}</span>
                    @else
                        <span style="color: #b91d19">{{ $alarm->percent_change }}</span>
                    @endif
                </li>

        @endforeach
        </ul>
    @endif

    <script>

        $(document).ready(function() {

            var newAlarms = <?php echo  $newAlarms->count(); ?>;

            if (newAlarms){
                $("#alarmsPage").css("color", "red");
            }


            setTimeout(function() {
                location.reload();
            }, 10000);

        });
    </script>
@endsection

@extends('app')

@section('content')
    <h3>Resultados</h3>
    <hr>
    <form action="/optafeeds" method="POST" id="optaform">
        {!! csrf_field() !!}
        <div class="form-group row">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-primary btn-dayscore btn-block">Enviar</button>
            </div>
        </div>

    </form>
    <div class="results">

    </div>
@stop
@section('scripts')
    <script>
        $('#optaform').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url:'/optafeeds',
                headers: {
                    'x-meta-feed-type':'1',
                    'x-meta-feed-parameters':'feed params',
                    'x-meta-default-filename': 'filename.xml',
                    'x-meta-game-id': '1',
                    'x-meta-competition-id': '1',
                    'x-meta-season-id': '2010',
                    'x-meta-game-id': '',
                    'x-meta-gamesystem-id': '1',
                    'x-meta-matchday': '1',
                    'x-meta-away-team-id': '1',
                    'x-meta-home-team-id': '1',
                    'x-meta-game-status': '11',
                    'x-meta-language': 'en',
                    'x-meta-production-server': 'server',
                    'x-meta-production-server-timestamp': '1',
                    'x-meta-production-server-module': '1',
                    'x-meta-mime-type': 'text/xml',
                    'x-meta-encoding':'UTF-8'
                },
                method: 'POST',
                data: '<xml>Test Content</xml>'
//                    "_token": $(this).find('input[name=_token]').val()
                ,
                success: function(data){
                    $(".results").html(data);
                }
            });
        });
    </script>
@stop

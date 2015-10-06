@extends('app')

@section('content')
    <h3>Opta Feeds</h3>
    <hr>
    @foreach($optafeeds as $feed)
        <div class="card">{{ $feed }}
        </div>
    @endforeach
@stop


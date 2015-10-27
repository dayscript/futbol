@if($team && $team->team)
    <img class="" width="15" src="{{$team->team->image()}}" alt="{{$team->team->name}}">
@else
    {{$team?$team->order:'-'}}
@endif

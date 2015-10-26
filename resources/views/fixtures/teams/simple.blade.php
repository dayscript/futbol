@if($team->team && !isset($right))
    <img class="" width="15" src="{{$team->team->image()}}" alt="{{$team->team->name}}">
@endif
{{$team?($team->team?$team->team->name:$team->name):'-'}}
@if($team->team && isset($right))
    <img class="" width="15" src="{{$team->team->image()}}" alt="{{$team->team->name}}">
@endif
@extends('layouts.app')

@section('content')
<div class="container">
<form class="form-horizontal" role="form" method="POST" action="{{ url('workvacations') }}">
{!! csrf_field() !!}
  <div>
    <h2 id="line">休暇申請</h2>
  </div>
    <div class="clearfix">
      <div class="pull-right" class="btn-group btn-group-sm">
  			<a href="{{url('workvacations/create')}}" class="btn btn-primary" >新規登録</a>
  		</div>
    </div>
	<table class="table table-striped">
	<thead>
      <tr>
        <th>日付</th>
        <th>休暇種類</th>
        <th>休暇時間</th>
        <th>振替日付</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
		@forelse ($workVacations as $workVacation)
		<tr>
			<td>{{date_formatA($workVacation->date_at,'Y/m/d')}}</td>
      <td>{{getvacationname($workVacation->groupvacation_id)}}</td>
			<td>{{$workVacation->start_at}}～{{$workVacation->end_at}}</td>
      <td>{{date_formatA($workVacation->change_at,'Y/m/d')}}</td>
			<td><a href="{{url('workvacations/edit')."/".$workVacation->id}}"name="details" class="btn btn-info btn-sm">変更</a></td>
      <td><a href="{{url('workvacations/destroy')."/".$workVacation->id}}"name="details" class="btn btn-danger btn-sm">削除</a></td>
	    </tr>
	    @empty

		@endforelse
	</tbody>
	</table>
</form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
<form class="form-horizontal" role="form" method="POST" action="{{ url('workvacations') }}">
{!! csrf_field() !!}
    <h2>休暇申請一覧</h2>
    <div class="row">
        <div class="col-sm-12">
            <a href="/workvacations/create" class="btn btn-primary" style="margin:20px;">新規登録</a>
        </div>
    </div>

    <table class="table table-striped">
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
			<td>{{$workVacation->date_at}}</td>
      <td>
      @if ($workVacation->groupvacation_id == 1) 有給
      @elseif  ($workVacation->groupvacation_id == 2) 午前有給
      @elseif  ($workVacation->groupvacation_id == 3) 午後有給
      @elseif  ($workVacation->groupvacation_id == 4) 代休
      @elseif  ($workVacation->groupvacation_id == 5) 夏季休暇
      @elseif  ($workVacation->groupvacation_id == 6) 冬季休暇
      @elseif  ($workVacation->groupvacation_id == 7) 慶弔休暇
      @elseif  ($workVacation->groupvacation_id == 8) 産休（産前休業／産後休業）
      @elseif  ($workVacation->groupvacation_id == 9) 生理休暇
      @elseif  ($workVacation->groupvacation_id == 10) 育児休暇
      @elseif  ($workVacation->groupvacation_id == 11) 子の看護休暇
      @elseif  ($workVacation->groupvacation_id == 12) 裁判員休暇
      @elseif  ($workVacation->groupvacation_id == 13) 母性健康管理のための休暇
      @elseif  ($workVacation->groupvacation_id == 14) 介護休業
      @elseif  ($workVacation->groupvacation_id == 15) 介護休暇
      @endif
      </td>
			<td>{{$workVacation->start_at}}～{{$workVacation->end_at}}</td>
      <td>{{$workVacation->change_at}}</td>
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

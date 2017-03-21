@extends('layouts.app')

@section('content')
<div class="container">
<form class="form-horizontal" role="form" method="POST" action="{{ url('workconfirmation') }}"> 
{!! csrf_field() !!}
	<div class="form-group form-inline">
		<label for="period" class="control-label  col-sm-2">期間</label>
		<div class="col-sm-2">
			<select class="form-control combo_select" id="period" name="period">
	            @forelse ($dates as $date)
	               <option value="{{ $date }}" @if( $date == $period ) selected @endif >{{ $date }}</option>
	    		@empty
				@endforelse
          </select>
		</div>
		<div class="col-sm-2" >
			<button type="submit" value="search" name="search" class="btn btn-success btn-sm">検 索</button>
		</div>
		<div class="col-sm-2" >
			<button value="search" name="search" class="btn btn-success btn-sm">報告書</button>
		</div>
	</div>
	<table class="table table-striped">
	<thead>
      <tr>
        <th>日付</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>総時間</th>
        <th>所定内</th>
        <th>時間外</th>
        <th>深夜</th>
        <th>休日</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
		@forelse ($works as $work)
		<tr>
	    	<td><span style="color :{{ holiday_color($work->date_at) }};">
	    	{{ date_formatA($work->date_at,"m/d") . date_week($work->date_at) }}</span></td>
	    	<td>{{ date_formatA($work->attendance_at,"G:i") }}</td>
	    	<td><span>{{wdate_nextDay($work->date_at,$work->leaving_at) }} {{ date_formatA($work->leaving_at,"G:i") }}</span></td>
	    	<td>{{ gethour($work->worktime) }}</td>
	    	<td>{{ gethour($work->predeterminedtime) }}</td>
	    	<td>{{ gethour($work->overtime)}}</td>
	    	<td>{{ gethour($work->nighttime) }}</td>
	    	<td>{{ gethour($work->holidaytime) }}</td>
	    	<td><a href="workregister?date_at={{ $work->date_at }}"name="details" class="btn btn-info btn-sm">詳細</a></td>
	    </tr>
	    @empty

		@endforelse
		<tr>
			<td colspan="3"><b>合計</b></td>
			<td colspan="6"><b class="lead">{{ gethour($max_worktime) }} h</b></td>
		</tr>
	</tbody>
	</table>
</form>
</div>
@endsection
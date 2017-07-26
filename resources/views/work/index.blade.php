@extends('layouts.app')

@section('content')
<div class="container">
<form id="form1" class="" role="form" method="POST" action="{{ url('workconfirmation') }}">
{!! csrf_field() !!}
  <div class="clearfix">
	  <div class="pull-left"><h3>{{ $period }}度</h3></div>
    <div class="pull-right" class="btn-group btn-group-sm">
      <input id="period" name="period" type="hidden" value="{{ $period }}"/>
			<a class="btn btn-default" id="calender"><i id="YearMonth" class="fa fa-calendar" aria-hidden="true" ></i></a>
		  &nbsp;&nbsp;
			<a id="BackMonth" href=""
				 class="btn btn-default"><i class="fa fa-chevron-left" aria-hidden="true" title="先月"></i></a>
			<a id="NowMonth" href="{{ url('workconfirmation') }}"
				 class="btn btn-default">今月</a>
			<a id="NextMonth" href=""
				 class="btn btn-default"><i class="fa fa-chevron-right" aria-hidden="true" title="翌月"></i></a>
		</div>
  </div>
  <div class="clearfix">
    <div class="pull-right" >
			<button type="submit" value="report" name="report" class="btn btn-success btn-sm">報告書出力</button>
      <button type="submit" value="csv" name="csv" class="btn btn-success btn-sm">CSV出力</button>
		</div>
  </div>
  <br>
  <div class="text-center">
    <table class="table table-bordered" >
    <tr>
      <th><span class='lead'>出勤</span></th>
      <td><span class='lead'>{{$gokei['date']}}</span> 日</td>
      <th><span class='lead'>総労働 </span></th>
      <td><span class='lead'>{{gethour($gokei['worktime'])}}</span> 時間</td>
      <th><span class='lead'>残業</span></th>
      <td><span class='lead'>{{gethour($gokei['overtime'])}}</span> 時間</td>
    </tr>
    </table>
  </div>
	<table class="table table-striped" id="workerlist">
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
				<th>休暇</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
		@forelse ($works as $work)
		<tr>
	    	<td><span style="color :{{ holiday_color($work['date_at']) }};">
	    	{{ date_formatA($work['date_at'],"m/d")}}({{date_week($work['date_at']) }})</span></td>
	    	<td>{{ date_formatA($work['attendance_at'],"G:i") }}</td>
	    	<td><span>{{wdate_nextDay($work['date_at'],$work['leaving_at']) }} {{ date_formatA($work['leaving_at'],"G:i") }}</span></td>
	    	<td>{{ gethour($work['worktime']) }}</td>
	    	<td>{{ gethour($work['predeterminedtime']) }}</td>
	    	<td>{{ gethour($work['overtime'])}}</td>
	    	<td>{{ gethour($work['nighttime']) }}</td>
	    	<td>{{ gethour($work['holidaytime']) }}</td>
				<td>{{ getvacationname($work['groupvacation_id']) }}</td>
	    	<td><a href="workregister?date_at={{ $work['date_at'] }}"name="details" class="btn btn-info btn-sm">詳細</a></td>
	    </tr>
	    @empty

		@endforelse
		<tr>
			<td colspan="1"><b>合計</b></td>
			<td></td>
			<td></td>
			<td><b class="lead">{{ gethour($gokei['worktime'])}} h</b></td>
			<td><b class="lead">{{ gethour($gokei['predeterminedtime'])}} h</b></td>
			<td><b class="lead">{{ gethour($gokei['overtime'])}} h</b></td>
			<td><b class="lead">{{ gethour($gokei['nighttime'])}} h</b></td>
			<td><b class="lead">{{ gethour($gokei['holidaytime'])}} h</b></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
	</table>
</form>
</div>
<script>
$(function(){
  $('#BackMonth').on('click', function() {
    var date=new Date($('#period').val().substr(0,4),$('#period').val().substr(5,2)-1,1);
    date.setMonth(date.getMonth()-1);
    $('#period').val(date.getFullYear() + "年" + ('00' + (date.getMonth() + 1)).slice(-2) + "月");
    $('#BackMonth').attr('href','{{ url('workconfirmation') }}' + '?period=' + $('#period').val());
  });
  $('#NowMonth').on('click', function() {
    $('#form1').submit();
  });
  $('#NextMonth').on('click', function() {
    var date=new Date($('#period').val().substr(0,4),$('#period').val().substr(5,2)-1,1);
    date.setMonth(date.getMonth()+1);
    $('#period').val(date.getFullYear() + "年" + ('00' + (date.getMonth() + 1)).slice(-2) + "月");
    $('#NextMonth').attr('href','{{ url('workconfirmation') }}' + '?period=' + $('#period').val());
  });
	$('#calender').on('click', function() {
		 $('#YearMonth').datepicker({
       dateFormat: "yyyy年mm月",
       language: 'ja',
       minViewMode: 1,
       autoclose: true,
     });
		 $('#YearMonth').datepicker('show');
     $('#YearMonth').datepicker().on('changeDate', function(e) {
       $('#period').val(e.format(0,"yyyy年mm月"));
       $('#form1').submit();
     });
	});
});
</script>
@endsection

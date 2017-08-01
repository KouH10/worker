@extends('layouts.app')

@section('content')
<div class="container">
  {!! csrf_field() !!}
  <div>
    <h2 id="line">グループ実績表</h2>
  </div>
    <div class="clearfix">
  	  <div class="pull-left"><h3>{{ $period }}度</h3></div>
      <div class="pull-right" class="btn-group btn-group-sm">
        <input id="period" name="period" type="hidden" value="{{ $period }}"/>
  			<a class="btn btn-default" id="calender"><i id="YearMonth" class="fa fa-calendar" aria-hidden="true" ></i></a>
  		  &nbsp;&nbsp;
  			<a id="BackMonth" href=""
  				 class="btn btn-default"><i class="fa fa-chevron-left" aria-hidden="true" title="先月"></i></a>
  			<a id="NowMonth" href="{{ url('work/list') }}"
  				 class="btn btn-default">今月</a>
  			<a id="NextMonth" href=""
  				 class="btn btn-default"><i class="fa fa-chevron-right" aria-hidden="true" title="翌月"></i></a>
  		</div>
    </div>
  <div class ="table-responsive panel panel-default">
  <table class="table table-bordered table-striped">
    <thead>
        <tr>
          <th bgcolor="#2FCDB4" rowspan="3"></th>
          <td align="center" bgcolor="#2FCDB4" colspan="{{$date_cnts['month1']}}"><span style="color :white;">{{date_formatA($dates[0],"n")}}月</span></td>
          <td align="center" bgcolor="#2FCDB4" colspan="{{$date_cnts['month2']}}"><span style="color :white;">{{date_formatA($dates[20],"n")}}月</span></td>
        <tr>
          @forelse ($dates as $date)
            <td align="center" bgcolor="#2FCDB4" ><span style="color :white;">{{date_formatA($date,"d")}}<span></td>
          @empty
          @endforelse
        </tr>
        <tr>
          @forelse ($dates as $date)
            <td align="center" bgcolor="#2FCDB4"><span style="color :{{ holiday_color($date,'white') }};">
              {{ date_week($date) }}</span></td>
          @empty
          @endforelse
        </tr>
    </thead>
    <tbody class="">
    @forelse ($works as $work)
      <tr>
        <th style="white-space: nowrap;"><a href="{{ url('workconfirmation') }}?period={{$period}}&user_id={{$work[0]}}">{!! $work[1] !!}</a></th>
        @forelse (array_slice($work,2) as $s)
          <td align="center" style="white-space: nowrap;"><small>{!! $s !!}</small></td>
        @empty
        @endforelse
      </tr>
    @empty
    @endforelse
   </tbody>
  </table>
  </div>
</div>
<script>
  $(function(){
    $('#BackMonth').on('click', function() {
      var date=new Date($('#period').val().substr(0,4),$('#period').val().substr(5,2)-1,1);
      date.setMonth(date.getMonth()-1);
      $('#period').val(date.getFullYear() + "年" + ('00' + (date.getMonth() + 1)).slice(-2) + "月");
      $('#BackMonth').attr('href','{{ url('work/list') }}' + '?period=' + $('#period').val());
    });
    $('#NowMonth').on('click', function() {
      $('#form1').submit();
    });
    $('#NextMonth').on('click', function() {
      var date=new Date($('#period').val().substr(0,4),$('#period').val().substr(5,2)-1,1);
      date.setMonth(date.getMonth()+1);
      $('#period').val(date.getFullYear() + "年" + ('00' + (date.getMonth() + 1)).slice(-2) + "月");
      $('#NextMonth').attr('href','{{ url('work/list') }}' + '?period=' + $('#period').val());
    });
  	$('#calender').on('click', function() {
  		 $('#YearMonth').datepicker({
         dateFormat: "yyyy年mm月",
         language: 'ja',
         minViewMode: 1,
         autoclose: true,
       });
       $("#YearMonth").datepicker("setDate", "{{str_replace('月','/',str_replace('年','/',$period))}}01");
  		 $('#YearMonth').datepicker('show');
       $('#YearMonth').datepicker().on('changeDate', function(e) {
         $('#period').val(e.format(0,"yyyy年mm月"));
         window.location.href = '{{ url('work/list') }}' + '?period=' + $('#period').val();
       });
  	});
  });
</script>
@endsection

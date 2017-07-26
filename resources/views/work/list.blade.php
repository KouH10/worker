@extends('layouts.app')

@section('content')
<div class="container">
  {!! csrf_field() !!}
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
</div>
<div>
<table class="table table-bordered">
  <thead>
      <tr>
        <th rowspan="3"></th>
        <th colspan="{{$date_cnts['month1']}}">{{date_formatA($dates[0],"n")}}月</th>
        <th colspan="{{$date_cnts['month2']}}">{{date_formatA($dates[20],"n")}}月</th>
      <tr>
        @forelse ($dates as $date)
          <th>{{date_formatA($date,"d")}}</th>
        @empty
        @endforelse
      </tr>
      <tr>
        @forelse ($dates as $date)
          <td><span style="color :{{ holiday_color($date) }};">
            {{ date_week($date) }}</span></td>
        @empty
        @endforelse
      </tr>
  </thead>
  <tbody>
  @forelse ($works as $work)
    <tr>
      @forelse ($work as $s)
        <td>{!! $s !!}</td>
      @empty
      @endforelse
    </tr>
  @empty
  @endforelse
 </tbody>
</table>
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
  		 $('#YearMonth').datepicker('show');
       $('#YearMonth').datepicker().on('changeDate', function(e) {
         $('#period').val(e.format(0,"yyyy年mm月"));
         window.location.href = '{{ url('work/list') }}' + '?period=' + $('#period').val();
       });
  	});
  });
</script>
@endsection

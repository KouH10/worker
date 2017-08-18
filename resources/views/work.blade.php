@extends('layouts.app')

@section('content')
<div class="container">
<form class="form-horizontal" role="form" method="POST" action="{{ url('work') }}">
{!! csrf_field() !!}
    <div class="col-sm-9" align="center">
        <div class="row">
            <div class="col-xs-12 .col-md-12">
                <span id="time" class="clock"></span>
                <input type="hidden" id="date_before"/>
            </div>
        </div>
        <div style="padding-bottom:30px;" >
          <span id="date"  class="time"></span>
        </div>
        <div class="row" style="padding-bottom:20px;">
            <div class="col-xs-6 .col-md-6" >
                <button type="submit" value="attendance" name="attendance" class="line-btn"
                @if(is_null($work->attendance_at) === false or is_null($work->attendance_stamp_at) === false)
                     disabled
                @endif
                ><i class="fa fa-sun-o" aria-hidden="true"></i>  勤務開始</button></br>
                &nbsp;&nbsp;<span class="lead">{{ date_formatA($work->attendance_at,"G:i") }}</span>
                &nbsp;&nbsp;<span class="lead">(&nbsp;{{ date_formatA($work->attendance_stamp_at,"G:i") }}&nbsp;)</span>
            </div>
            <div class="col-xs-6 .col-md-6">
                <button type="submit"  value="leaving" name="leaving" class="line-btn"
                ><i class="fa fa-moon-o" aria-hidden="true"></i>  勤務終了</button></br>
                &nbsp;&nbsp;<span class="lead">{{ date_formatA($work->leaving_at,"G:i") }}</span>
                &nbsp;&nbsp;<span class="lead">(&nbsp;{{ date_formatA($work->leaving_stamp_at,"G:i") }}&nbsp;)</span>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <!-- パネルで囲む -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                {{$affiliation->group->name}}
            </div>
            <!-- <div class="panel-body"> -->
            <ul class="nav nav-pills nav-stacked">
                @forelse($datas as $data)
                    <li><a>{{$data->name}}&nbsp;&nbsp;
                    @if(!is_null($data->leaving_at)) <span class="label label-default">帰宅</span>
                    @elseif(!is_null($data->attendance_at)) <span class="label label-success">勤務中</span> @endif
                    </a></li>
                @empty
                @endforelse
            </ul>
            <!-- </div> -->
        </div>
        <div id="show_result"></div>
    </div>
    <input id="gps" name="gps" type="hidden" value="{{ \Auth::user()->gps}}"/>
    <input id="latitude" name="latitude" type="hidden" value="d"/>
    <input id="longitude" name="longitude" type="hidden" value=""/>
    <input id="altitude" name="altitude" type="hidden" value=""/>
    <input id="accuracy" name="accuracy" type="hidden" value=""/>
    <input id="altitudeAccuracy" name="altitudeAccuracy" type="hidden" value=""/>
    <input id="heading" name="heading" type="hidden" value=""/>
    <input id="speed" name="speed" type="hidden" value=""/>
</form>
</div>
<script type="text/javascript">
<!--
  $(function(){
    setDateTime(1);
    navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
  });

  /***** 位置情報が取得できない場合 *****/
  function errorCallback(error) {
    var err_msg = "";
    $('#gps').val("0");
    switch(error.code)
    {
      case 1:
        err_msg = "位置情報の利用が許可されていません";
        break;
      case 2:
        err_msg = "デバイスの位置が判定できません";
        break;
      case 3:
        err_msg = "タイムアウトしました";
        break;
    }
    $('#show_result').html(err_msg);
    //デバッグ用→　document.getElementById("show_result").innerHTML = error.message;
  }
  /***** ユーザーの現在の位置情報を取得 *****/
  function successCallback(position) {
    var gl_text = "緯度：" + position.coords.latitude + "<br>";
    gl_text += "経度：" + position.coords.longitude + "<br>";
    gl_text += "高度：" + position.coords.altitude + "<br>";
    gl_text += "緯度・経度の誤差：" + position.coords.accuracy + "<br>";
    gl_text += "高度の誤差：" + position.coords.altitudeAccuracy + "<br>";
    gl_text += "方角：" + position.coords.heading + "<br>";
    gl_text += "速度：" + position.coords.speed + "<br>";
    $('#show_result').html(gl_text);
    $('#latitude').val(position.coords.latitude);
    $('#longitude').val(position.coords.longitude);
    $('#altitude').val(position.coords.altitude);
    $('#accuracy').val(position.coords.accuracy);
    $('#altitudeAccuracy').val(position.coords.altitudeAccuracy);
    $('#heading').val(position.coords.heading);
    $('#speed').val(position.coords.speed);
  }
//-->
</script>
@endsection

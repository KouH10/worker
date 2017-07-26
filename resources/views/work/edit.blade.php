@extends('layouts.app')

@section('content')
<div class="container">
<!-- エラーメッセージの表示 -->
@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
<form class="form-horizontal" role="form" method="POST" action="{{ url('workregister') }}">
{!! csrf_field() !!}
  <div class="row" style="padding-bottom:20px;">
  	<div class="col-sm-12" >
	  	<h2 style="color :{{ holiday_color($work->date_at) }};" >{{ date("Y/m/d",strtotime($work->date_at))}}({{date_week($work->date_at) }})</h2>
	  	<input type="hidden" name="date_at" id="date_at" value="{{ date("Y-m-d",strtotime($work->date_at))}} "/>
	</div>
  </div>
  <div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">勤務時間</h3>
    </div>
    <div class="panel-body">
	  <div class="form-group @if(!empty($errors->first('attendance_at'))) has-error @endif">
		  	<label for="attendance_at" class="control-label  col-sm-2">勤務開始</label>
		  	<div class="col-sm-10">
          <ul class="list-inline">
            <li>
              <select class="form-control combo_select" id="attendance_at_h" name="attendance_at_h" style="width: 80px">
               <option value=""></option>
                @for ($i = 0; $i < 24; $i++)
                  <option value="{{$i}}" @if( old('attendance_at_h',getDateformat($work->attendance_at,'G')) == "$i" ) selected @endif  >{{$i}}</option>
                @endfor
              </select>
            </li>
            <li><span>:</span></li>
            <li>
              <select class="form-control combo_select" id="attendance_at_m" name="attendance_at_m" style="width: 80px">
                  <option value=""></option>
                  <option value="00" @if( old('attendance_at_m',getDateformat($work->attendance_at,'i')) == '00' ) selected @endif  >00</option>
                  <option value="15" @if( old('attendance_at_m',getDateformat($work->attendance_at,'i')) == '15' ) selected @endif  >15</option>
                  <option value="30" @if( old('attendance_at_m',getDateformat($work->attendance_at,'i')) == '30' ) selected @endif  >30</option>
                  <option value="45" @if( old('attendance_at_m',getDateformat($work->attendance_at,'i')) == '45' ) selected @endif  >45</option>
              </select>
            </li>
            <li>
              <label class="control-label">（ {{ date_formatA($work->attendance_stamp_at,"G:i")}} ）</label>
            </li>
          </ul>
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('leaving_at'))) has-error @endif">
		  	<label for="leaving_at" class="control-label  col-sm-2">勤務終了</label>
        <div class="col-sm-10">
          <ul class="list-inline">
            <li>
              <select class="form-control combo_select" id="leaving_at_h" name="leaving_at_h" style="width: 80px">
                <option value=""></option>
                @for ($i = 0; $i < 24; $i++)
                  <option value="{{$i}}" @if( old('leaving_at_h',getDateformatN($work->leaving_at,$work->date_at,'G')) == "$i" ) selected @endif  >{{$i}}</option>
                @endfor
                @for ($i = 0; $i < 24; $i++)
                  <option value="翌{{$i}}" @if( old('leaving_at_h',getDateformatN($work->leaving_at,$work->date_at,'G')) == ('翌'."$i") ) selected @endif  >翌{{$i}}</option>
                @endfor
              </select>
            </li>
            <li><span>:</span></li>
            <li>
              <select class="form-control combo_select" id="leaving_at_m" name="leaving_at_m" style="width: 80px">
                  <option value=""></option>
                  <option value="00" @if( old('leaving_at_m',getDateformat($work->leaving_at,'i')) == "00" ) selected @endif  >00</option>
                  <option value="15" @if( old('leaving_at_m',getDateformat($work->leaving_at,'i')) == "15" ) selected @endif  >15</option>
                  <option value="30" @if( old('leaving_at_m',getDateformat($work->leaving_at,'i')) == "30" ) selected @endif  >30</option>
                  <option value="45" @if( old('leaving_at_m',getDateformat($work->leaving_at,'i')) == "45" ) selected @endif  >45</option>
              </select>
            </li>
            <li>
              <label class="control-label">（ {{ date_formatA($work->leaving_stamp_at,"G:i")}} ）</label>
            </li>
          </ul>
        </div>
	  </div>
	  <div class="form-group">
		  	<label for="leaving_at" class="control-label  col-sm-2">合計時間</label>
		  	<div class="col-sm-4">
			  	<span class="lead">{{ gethour($work->worktime) }}</span>&nbsp;&nbsp;時間
			  	&nbsp;&nbsp;（<span class="lead">{{ $work->worktime }}</span>&nbsp;&nbsp;分）
		  	</div>
	  </div>
	  <div class="form-group">
		  	<label for="leaving_at" class="control-label  col-sm-2">所定内時間</label>
		  	<div class="col-sm-4">
			  	<span class="lead">{{ gethour($work->predeterminedtime) }}</span>&nbsp;&nbsp;時間
			  	&nbsp;&nbsp;（<span class="lead">{{ $work->predeterminedtime }}</span>&nbsp;&nbsp;分）
		  	</div>
	  </div>

	  <div class="form-group">
		  	<label for="leaving_at" class="control-label  col-sm-2">時間外</label>
		  	<div class="col-sm-4">
			  	<span class="lead">{{ gethour($work->overtime) }}</span>&nbsp;&nbsp;時間
			  	&nbsp;&nbsp;（<span class="lead">{{ $work->overtime }}</span>&nbsp;&nbsp;分）
		  	</div>
	  </div>
	  <div class="form-group">
		  	<label for="leaving_at" class="control-label  col-sm-2">深夜時間</label>
		  	<div class="col-sm-4">
			  	<span class="lead">{{ gethour($work->nighttime) }}</span>&nbsp;&nbsp;時間
			  	&nbsp;&nbsp;（<span class="lead">{{ $work->nighttime }}</span>&nbsp;&nbsp;分）
		  	</div>
	  </div>
	  <div class="form-group">
		  	<label for="leaving_at" class="control-label  col-sm-2">休日時間</label>
		  	<div class="col-sm-4">
			  	<span class="lead">{{ gethour($work->holidaytime) }}</span>&nbsp;&nbsp;時間
			  	&nbsp;&nbsp;（<span class="lead">{{ $work->holidaytime }}</span>&nbsp;&nbsp;分）
		  	</div>
	  </div>
	  <div class="form-group" >
	  		<label for="content" class="control-label col-sm-2">勤務内容</label>
	  		<div class="col-sm-10">
	  			<textarea  row="3" class="form-control" name="content" id="content" placeholder="出勤" >{{ old('content',$work->content) }}</textarea>
	  		</div>
	  </div>
	  <div class="row">
	  	<div class="col-sm-12">
	  	  <div style="float: right">
	      <button type="submit" value="work_regist" name="work_regist" class="btn btn-primary " >登 録</button>
	      &nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('workconfirmation') }}?period={{$period}}" value="back" name="back" class="btn btn-default">戻 る</a>
	      </div>
	    </div>
	  </div>
	 </div>
  </div>
</form>
</div>
<script>
	$(function(){
	    //checkedだったら最初からチェックする
	    $('div.check-group input').each(function(){
	        if ($(this).attr('checked') == 'checked') {
	            $(this).next().addClass('btn-danger');

	        }
	    });
	    //クリックした要素にクラス割り当てる
	    $('div.check-group label').click(function(){
	        if ($(this).hasClass('btn-danger')) {
	            $(this).removeClass('btn-danger');
	            $('next_day').removeAttr('checked');
	        } else {
	            $(this)
	                .addClass('btn-danger');
	            $('next_day').attr('checked','checked');
	        }
	    });
	});
</script>
@endsection

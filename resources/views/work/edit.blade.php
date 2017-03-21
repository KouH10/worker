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
	  	<h2 style="color :{{ holiday_color($work->date_at) }};" >{{ date("Y/m/d",strtotime($work->date_at)).date_week($work->date_at) }}</h2>
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
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="attendance_at" id="attendance_at" placeholder="HH:MI" 
		  				value ="{{ date_formatA(old('attendance_at',$work->attendance_at),"G:i")}}"/>
		  	</div>
		  	<div class="col-sm-2">
		  		<label class="control-label">（ {{ date_formatA($work->attendance_stamp_at,"G:i")}} ）</label>	  	
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('leaving_at'))) has-error @endif">
		  	<label for="leaving_at" class="control-label  col-sm-2">勤務終了</label>
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="leaving_at" id="leaving_at" placeholder="HH:MI" 
		  				value ="{{ date_formatA(old('leaving_at',$work->leaving_at),"G:i")}}"/>
		  	</div>
		  	<div class="col-sm-2">
		  		<label class="control-label">（ {{ date_formatA(old('leaving_stamp_at',$work->leaving_stamp_at),"G:i")}} ）</label>	  	
		  	</div>
		  	<div class="col-sm-3 check-group">
		  	    <div>
		  			<input id="next_day" name='next_day' type='checkbox' class="checkbox" value="1" @if($nextflg===1) checked="checked" @endif/>
		  			<label class="btn btn-default btn-sm" for="next_day">翌日</label>
		  		</div>
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
	      &nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('workconfirmation') }}" value="back" name="back" class="btn btn-default">戻 る</a>
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
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
<form class="form-horizontal" role="form" method="POST" action="{{url('/groups/update').'/'.$group->id}}"> 
{!! csrf_field() !!}
 <div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">グループ新規登録</h3>
    </div>
    <div class="panel-body">
	  <div class="form-group @if(!empty($errors->first('name'))) has-error @endif">
		  	<label for="attendance_at" class="control-label  col-sm-2">グループ名</label>
		  	<div class="col-sm-10">
			  	<input type="text"  class="form-control" name="name" id="name" placeholder="テスト" value ="{{old('name',$group->name)}}"/>
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('workingstart_st')) or !empty($errors->first('workingend_st'))) has-error @endif">
		  	<label for="leaving_at" class="control-label  col-sm-2">所定労働時間</label>
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="workingstart_st" id="workingstart_st" placeholder="9:00" value ="{{old('workingstart_st',$group->workingstart_st)}}"/>
		  	</div>
		  	<div class="col-sm-1">
		  		<label class="control-label">～</label>
		  	</div>
		  	<div class="col-sm-2">
		  	    <input type="text"  class="form-control" name="workingend_st" id="workingend_st" placeholder="18:00" value ="{{old('workingend_st',$group->workingend_st)}}"/>
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('reststart_st')) or !empty($errors->first('restend_st'))) has-error @endif">
		  	<label for="leaving_at" class="control-label  col-sm-2">休憩時間</label>
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="reststart_st" id="reststart_st" placeholder="12:00" value ="{{old('reststart_st',$group->reststart_st)}}"/>
		  	</div>
		  	<div class="col-sm-1">
		  		<label class="control-label">～</label>
		  	</div>
		  	<div class="col-sm-2">
		  	    <input type="text"  class="form-control" name="restend_st" id="restend_st" placeholder="13:00" value ="{{old('restend_st',$group->restend_st)}}"/>
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('nightstart_st')) or !empty($errors->first('nightend_st'))) has-error @endif" >
		  	<label for="leaving_at" class="control-label  col-sm-2">深夜時間</label>
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="nightstart_st" id="nightstart_st" placeholder="22:00" value ="{{old('nightstart_st',$group->nightstart_st)}}"/>
		  	</div>
		  	<div class="col-sm-1">
		  		<label class="control-label">～</label>
		  	</div>
		  	<div class="col-sm-2">
		  	    <input type="text"  class="form-control" name="nightend_st" id="nightend_st" placeholder="5:00" value ="{{old('nightend_st',$group->nightend_st)}}"/>
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('legalholiday'))) has-error @endif" >
		  	<label for="leaving_at" class="control-label  col-sm-2">法定休日</label>
	  		<div class="col-sm-3">
	          <select class="form-control combo_select" id="legalholiday" name="legalholiday">
	        	<option value="1" @if( old('legalholiday',$group->legalholiday) == "1" ) selected @endif  >月曜日</option>
	        	<option value="2" @if( old('legalholiday',$group->legalholiday) == "2" ) selected @endif  >火曜日</option>
	        	<option value="3" @if( old('legalholiday',$group->legalholiday) == "3" ) selected @endif  >水曜日</option>
	        	<option value="4" @if( old('legalholiday',$group->legalholiday) == "4" ) selected @endif  >木曜日</option>
	        	<option value="5" @if( old('legalholiday',$group->legalholiday) == "5" ) selected @endif  >金曜日</option>
	        	<option value="6" @if( old('legalholiday',$group->legalholiday) == "6" ) selected @endif  >土曜日</option>
	        	<option value="7" @if( old('legalholiday',$group->legalholiday) == "7" ) selected @endif  >日曜日</option>
	          </select>
			</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('legalholiday'))) has-error @endif" >
		  	<label for="leaving_at" class="control-label  col-sm-2">法定外休日</label>
	  		<div class="col-sm-3">
	          <select class="form-control combo_select" id="notlegalholiday" name="notlegalholiday">
	        	<option value="1" @if( old('notlegalholiday',$group->notlegalholiday) == "1" ) selected @endif >月曜日</option>
	        	<option value="2" @if( old('notlegalholiday',$group->notlegalholiday) == "2" ) selected @endif >火曜日</option>
	        	<option value="3" @if( old('notlegalholiday',$group->notlegalholiday) == "3" ) selected @endif >水曜日</option>
	        	<option value="4" @if( old('notlegalholiday',$group->notlegalholiday) == "4" ) selected @endif >木曜日</option>
	        	<option value="5" @if( old('notlegalholiday',$group->notlegalholiday) == "5" ) selected @endif >金曜日</option>
	        	<option value="6" @if( old('notlegalholiday',$group->notlegalholiday) == "6" ) selected @endif >土曜日</option>
	        	<option value="7" @if( old('notlegalholiday',$group->notlegalholiday) == "7" ) selected @endif >日曜日</option>
	          </select>
			</div>
	  </div>
  	  <div class="form-group @if(!empty($errors->first('weekstart'))) has-error @endif" >
	  	<label for="leaving_at" class="control-label  col-sm-2">週開始</label>
  		<div class="col-sm-3">
          <select class="form-control combo_select" id="weekstart" name="weekstart">
        	<option value="1" @if( old('weekstart',$group->weekstart) == "1" ) selected @endif >月曜日</option>
        	<option value="2" @if( old('weekstart',$group->weekstart) == "2" ) selected @endif >火曜日</option>
        	<option value="3" @if( old('weekstart',$group->weekstart) == "3" ) selected @endif >水曜日</option>
        	<option value="4" @if( old('weekstart',$group->weekstart) == "4" ) selected @endif >木曜日</option>
        	<option value="5" @if( old('weekstart',$group->weekstart) == "5" ) selected @endif >金曜日</option>
        	<option value="6" @if( old('weekstart',$group->weekstart) == "6" ) selected @endif >土曜日</option>
        	<option value="7" @if( old('weekstart',$group->weekstart) == "7" ) selected @endif >日曜日</option>
          </select>
		</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('monthstart'))) has-error @endif" >
	  	<label for="leaving_at" class="control-label  col-sm-2">月開始日</label>
  		<div class="col-sm-3">
          <select class="form-control combo_select" id="monthstart" name="monthstart">
            <option value="">選択なし</option>
            @for ($i = 1; $i <= 31; $i++)
               <option value="{{ $i }}" @if( $group->monthstart == $i ) selected @endif >{{ $i }}</option>
            @endfor
          </select>
		</div>
	  </div>
	  <div class="row">
	  	<div class="col-sm-12">
	  	  <div style="float: right">
	      <button type="submit" value="work_regist" name="work_regist" class="btn btn-primary " >登 録</button>
	      &nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('groups') }}" value="back" name="back" class="btn btn-default">戻 る</a>
	      </div>
	    </div>
	  </div>
	 </div>
  </div>
 </form>
@endsection

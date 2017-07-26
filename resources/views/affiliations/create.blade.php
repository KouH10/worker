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
<form class="form-horizontal" role="form" method="POST" action="{{ url('/affiliations/store') }}">
{!! csrf_field() !!}
 <div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">ユーザ所属新規登録</h3>
    </div>
    <div class="panel-body">
	  <div class="form-group @if(!empty($errors->first('user_id'))) has-error @endif">
		  	<label for="user_id" class="control-label  col-sm-2">ユーザ名</label>
		  	<div class="col-sm-5">
			  	<select class="form-control combo_select" id="user_id" name="user_id">
		            <option value="">選択なし</option>
				  	@forelse ($users as $user)
					  	<option value="{{ $user->id }}" @if( old('user_id') == $user->id ) selected @endif >{{ $user->name }}</option>
			        @empty

			        @endforelse
				</select>
		  	</div>
	  </div>
  	  <div class="form-group @if(!empty($errors->first('group_id'))) has-error @endif">
	  	<label for="group_id" class="control-label  col-sm-2">グループ名</label>
	  	<div class="col-sm-5">
		  	<select class="form-control combo_select" id="group_id" name="group_id">
	            <option value="">選択なし</option>
			  	@forelse ($groups as $group)
				  	<option value="{{ $group->id }}" @if( old('group_id') == $group->id ) selected @endif >{{ $group->name }}</option>
			    @empty

			    @endforelse
			</select>
	  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('employee_no'))) has-error @endif">
		  	<label for="employee_no" class="control-label  col-sm-2">社員番号</label>
		  	<div class="col-sm-5">
			  	<input type="text"  class="form-control" name="employee_no" id="employee_no" placeholder="99999" value ="{{ old('employee_no') }}"/>
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('entry_at'))) has-error @endif" >
		  	<label for="entry_at" class="control-label  col-sm-2">入社日</label>
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="entry_at" id="entry_at" placeholder="YYYY/MM/DD" value ="{{ old('entry_at') }}"/>
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('admin'))) has-error @endif" >
		  	<label for="admin" class="control-label  col-sm-2">権限</label>
	  		<div class="col-sm-3">
	          <select class="form-control combo_select" id="admin" name="admin">
	      		<option value="">選択なし</option>
	      		<option value="1" @if( old('admin') == "1" ) selected @endif>一般</option>
	        	<option value="99" @if( old('admin') == "99" ) selected @endif>管理者</option>
	          </select>
			</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('applystart_at')) or !empty($errors->first('applyend_at'))) has-error @endif" >
		  	<label for="applystart_at" class="control-label  col-sm-2">適用日</label>
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="applystart_at" id="applystart_at" placeholder="YYYY/MM/DD"" value ="{{ old('applystart_at') }}"/>
		  	</div>
		  	<div class="col-sm-1">
		  		<label class="control-label">～</label>
		  	</div>
		  	<div class="col-sm-2">
		  	    <input type="text"  class="form-control" name="applyend_at" id="applyend_at" placeholder="YYYY/MM/DD"" value ="{{ old('applyend_at') }}"/>
		  	</div>
	  </div>
	  <div class="row">
	  	<div class="col-sm-12">
	  	  <div style="float: right">
	      <button type="submit" value="work_regist" name="work_regist" class="btn btn-primary " >登 録</button>
	      &nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('affiliations') }}" value="back" name="back" class="btn btn-default">戻 る</a>
	      </div>
	    </div>
	  </div>
	 </div>
  </div>
 </form>
 </div>
 <script>
  $(function() {
    $("#entry_at").datepicker({
      dateFormat: "yyyy/mm/dd",
      language: 'ja',
      autoclose: true,
    });
    $("#applystart_at").datepicker({
      dateFormat: "yyyy/mm/dd",
      language: 'ja',
      autoclose: true,
    });
    $("#applyend_at").datepicker({
      dateFormat: "yyyy/mm/dd",
      language: 'ja',
      autoclose: true,
    });
  });
</script>
@endsection

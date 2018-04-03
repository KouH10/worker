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
<form class="form-horizontal" role="form" method="POST" action="{{ url('workvacations/update').'/'.$workVacation->id}}">
{!! csrf_field() !!}
 <div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">休暇申請更新</h3>
    </div>
    <div class="panel-body">
	  <div class="form-group @if(!empty($errors->first('date_at'))) has-error @endif">
		  	<label for="date_at" class="control-label  col-sm-2">日付</label>
		  	<div class="col-sm-3">
			  	<input type="text"  class="form-control" name="date_at" id="date_at" placeholder="2017/03/01" value ="{{ old('date_at',date_formatA($workVacation->date_at,'Y/m/d')) }}" readOnly=true/>
		  	</div>
	  </div>
	  <div class="form-group @if(!empty($errors->first('groupvacation_id'))) has-error @endif">
      <label for="groupvacation_id" class="control-label  col-sm-2">休暇種類</label>
      <div class="col-sm-4">
          <select class="form-control combo_select" id="groupvacation_id" name="groupvacation_id">
            <option value=1 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 1 ) selected @endif  >有給</option>
            <option value=2 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 2 ) selected @endif  >午前有給</option>
            <option value=3 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 3 ) selected @endif  >午後有給</option>
            <option value=4 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 4 ) selected @endif  >振休</option>
            <option value=5 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 5 ) selected @endif  >欠勤</option>
            <option value=6 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 6 ) selected @endif  >夏季休暇</option>
            <option value=7 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 7 ) selected @endif  >冬季休暇</option>
            <option value=8 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 8 ) selected @endif  >代休</option>
            <option value=9 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 9 ) selected @endif  >特別休暇</option>
            <option value=10 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 10 ) selected @endif  >慶弔休暇</option>
            <option value=11 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 11 ) selected @endif  >産休</option>
            <option value=12 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 12 ) selected @endif  >生理休暇</option>
            <option value=13 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 13 ) selected @endif  >育児休暇</option>
            <option value=14 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 14 ) selected @endif  >子の看護休暇</option>
            <option value=15 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 15 ) selected @endif  >裁判員休暇</option>
            <option value=16 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 16 ) selected @endif  >母性健康管理</option>
            <option value=17 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 17 ) selected @endif  >介護休業</option>
            <option value=18 @if( old('groupvacation_id',$workVacation->groupvacation_id) == 18 ) selected @endif  >介護休暇</option>
          </select>
    </div>
	  </div>
    <div id="div_vacation_at" class="form-group @if(!empty($errors->first('start_at')) or !empty($errors->first('end_at'))) has-error @endif" style="display: none;">
		  	<label for="leaving_at" class="control-label  col-sm-2">休暇時間</label>
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="start_at" id="start_at" placeholder="12:00" value ="{{old('start_at',$workVacation->start_at)}}"/>
		  	</div>
		  	<div class="col-sm-1">
		  		<label class="control-label">～</label>
		  	</div>
		  	<div class="col-sm-2">
		  	    <input type="text"  class="form-control" name="end_at" id="end_at" placeholder="13:00" value ="{{old('end_at',$workVacation->end_at)}}"/>
		  	</div>
	  </div>
    <div id="div_change_at" class="form-group @if(!empty($errors->first('change_at'))) has-error @endif" style="display: none;">
		  	<label for="change_at" class="control-label  col-sm-2">振替日付</label>
		  	<div class="col-sm-2">
			  	<input type="text"  class="form-control" name="change_at" id="change_at" placeholder="2017/03/01" value ="{{ old('change_at',date_formatA($workVacation->change_at,'Y/m/d')) }}"/>
		  	</div>
	  </div>
	  <div class="row">
	  	<div class="col-sm-12">
	  	  <div style="float: right">
	       &nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" value="work_regist" name="work_regist" class="btn btn-primary " >更新</button>
	       &nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('workvacations') }}" value="back" name="back" class="btn btn-default">戻 る</a>
	      </div>
	    </div>
	  </div>
	 </div>
  </div>
 </form>
 <script>
  $(function() {
    switch($('select[name="groupvacation_id"] option:selected').val()){
      case "1": case "2": case "3": case "5":
      case "6": case "7": case "9": case "10":
      case "12": case "13": case "14": case "15":
      case "16":case "17":case "18":
          $('#div_change_at').css('display','none');
          $('#div_vacation_at').css('display','none');
          break;
      case "4": case "8":
          $('#div_change_at').css('display','');
          $('#div_vacation_at').css('display','none');
          break;
      case "11":
          $('#div_change_at').css('display','none');
          $('#div_vacation_at').css('display','');
          break;
    }
    $("#change_at").datepicker({
      dateFormat: "yyyy/mm/dd",
      language: 'ja',
      autoclose: true,
    });
    $('select[name="groupvacation_id"]').change(function() {
      switch($('select[name="groupvacation_id"] option:selected').val()){
        case "1": case "2": case "3": case "5":
        case "6": case "7": case "9": case "10":
        case "12": case "13": case "14": case "15":
        case "16":case "17":case "18":
            $('#div_change_at').css('display','none');
            $('#div_vacation_at').css('display','none');
            $('#change_at').val() = '';
            $('#start_at').val() = '';
            $('#end_at').val() = '';
            break;
        case "4": case "8":
            $('#div_change_at').css('display','');
            $('#div_vacation_at').css('display','none');
            $('#start_at').val() = '';
            $('#end_at').val() = '';
            break;
        case "11":
            $('#div_change_at').css('display','none');
            $('#div_vacation_at').css('display','');
            $('#change_at').val() = '';
            break;
      }
    });
  });
</script>
@endsection

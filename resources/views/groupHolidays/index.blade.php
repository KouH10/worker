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
<form class="form-horizontal" role="form" method="POST" action=""> 
{!! csrf_field() !!}
<input type="button" class="btn btn-primary" id="createButton" value="休日追加"/>
<table class="table table-striped">
  <thead>
    <tr>
      <th>日付</th>
      <th>休日名</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
      @forelse ($groupHolidays as $groupholiday)
      <tr>
        <td>{{ $groupholiday->date_at }}</td>
        <td>{{ $groupholiday->name }}</td>
        <td><a href="#" name="details" class="btn btn-info btn-sm">編集</a></td>
        <td><a href="#" name="details" class="btn btn-info btn-sm">削除</a></td>
      </tr>
      @empty

      @endforelse
  </tbody>
</table>
<input type="hidden" name="groupid" value="{{$groupid}}"/>
<!-- 新規登録 モーダル-->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
        <h4 class="modal-title">休日追加</h4>
      </div>
      <div class="modal-body">
      <div class="container-fluid">
        <div class="form-group @if(!empty($errors->first('date_at'))) has-error @endif">
          <label for="date_at" class="control-label  col-sm-2">日付</label>
          <div class="col-sm-10">
            <input type="text"  class="form-control" name="date_at" id="date_at" placeholder="YYYY/MM/DD" value ="{{old('date_at')}}"/>
          </div>
        </div>
        <div class="form-group @if(!empty($errors->first('name'))) has-error @endif">
          <label for="name" class="control-label  col-sm-2">休暇</label>
          <div class="col-sm-10">
            <input type="text"  class="form-control" name="name" id="name" placeholder="冬季休暇" value ="{{old('name')}}"/>
          </div>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
        <button type="submit" class="btn btn-primary" id="submit" data-action="{{ url('/groupholidays/store') }}" >登録</button>
      </div>
    </div>
  </div>
</div>

<!-- 編集　モーダル -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
      <div class="container-fluid">
        <div class="form-group @if(!empty($errors->first('date_at'))) has-error @endif">
          <label for="date_at" class="control-label  col-sm-2">日付</label>
          <div class="col-sm-10">
            <input type="text"  class="form-control" name="date_at" id="date_at" placeholder="YYYY/MM/DD" value ="{{old('date_at')}}"/>
          </div>
        </div>
        <div class="form-group @if(!empty($errors->first('name'))) has-error @endif">
          <label for="name" class="control-label  col-sm-2">休暇</label>
          <div class="col-sm-10">
            <input type="text"  class="form-control" name="name" id="name" placeholder="冬季休暇" value ="{{old('name')}}"/>
          </div>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
        <button type="submit" class="btn btn-primary" id="submit" data-action="{{ url('/groupholidays/edit') }}" >登録</button>
      </div>
    </div>
  </div>
</div>
</form>
</div>
<script>

$( function() {
  
  $('button[type="submit"]').click(function() {
    $(this).parents('form').attr('action', $(this).data('action'));
    $(this).parents('form').submit();
  });

  $('#createButton').click( function () {
    $('#createModal').modal();
  });
});
</script>
@endsection
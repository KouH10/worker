@extends('layouts.app')

@section('content')
<div class="container">
<form class="form-horizontal" role="form" method="POST" action="{{ url('workvacations') }}">
{!! csrf_field() !!}
    <h2>休暇申請一覧</h2>
    <div class="row">
        <div class="col-sm-12">
            <a href="/workvacations/create" class="btn btn-primary" style="margin:20px;">新規登録</a>
        </div>
    </div>

    <table class="table table-striped">
	<table class="table table-striped">
	<thead>
      <tr>
        <th>日付</th>
        <th>休暇種類</th>
        <th>休暇内容</th>
        <th>申請日</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
		@forelse ($workVacations as $workVacation)
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td><a href=""name="details" class="btn btn-info btn-sm">詳細</a></td>
	    </tr>
	    @empty

		@endforelse
	</tbody>
	</table>
</form>
</div>
@endsection

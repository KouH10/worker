@extends('layouts.app')

@section('content')
<div class="container">
<form class="form-horizontal" role="form" method="POST" action="{{ url('') }}"> 
{!! csrf_field() !!}
    <h2>ユーザ所属一覧表示</h2>
    <div class="row">
        <div class="col-sm-12">
            <a href="/affiliations/create" class="btn btn-primary" style="margin:20px;">新規登録</a>
        </div>
    </div>

    <table class="table table-striped">
    <thead>
      <tr>
        <th>グループ名</th>
        <th>ユーザ名</th>
        <th>社員番号</th>
        <th>入社日</th>
        <th>権限</th>
        <th>適用日</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
        @forelse ($affiliations as $affiliation)
        <tr>
            <td>{{ $affiliation->group->name }}</td>
            <td>{{ $affiliation->user->name }}</td>
            <td>{{ $affiliation->employee_no }}</td>
            <td>{{ $affiliation->entry_at }}</td>
            <td>{{ $affiliation->admin }}</td>
            <td>{{ $affiliation->applystart_at}}～{{$affiliation->applyend_at}}</td>
            <td><a href="#" name="details" class="btn btn-info btn-sm">編集</a></td>
            <td><a href="#" name="details" class="btn btn-info btn-sm">削除</a></td>
        </tr>
        @empty

        @endforelse
    </tbody>
    </table>
</form>
</div>
@endsection


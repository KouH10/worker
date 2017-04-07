@extends('layouts.app')

@section('content')
<div class="container">
<form class="form-horizontal" role="form" method="POST" action="{{ url('') }}">
{!! csrf_field() !!}
    <h2>グループ一覧表示</h2>
    <div class="row">
        <div class="col-sm-12">
            <a href="{{ url('/groups/create') }}" class="btn btn-primary" style="margin:20px;">新規登録</a>
        </div>
    </div>

    <table class="table table-striped">
    <thead>
      <tr>
        <th>グループ</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
        @forelse ($groups as $group)
        <tr>
            <td>{{ $group->name }}</td>
            <td><a href="{{url('/groups/edit').'/',$group->id}}" name="details" class="btn btn-info btn-sm">編集</a></td>
            <td><a href="#" name="details" class="btn btn-info btn-sm">休日設定</a></td>
            <td><a href="#" name="details" class="btn btn-info btn-sm">休暇設定</a></td>
        </tr>
        @empty

        @endforelse
    </tbody>
    </table>
    {!! $groups->render() !!}
</form>
</div>
@endsection

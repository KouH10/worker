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
    </div>
</form>
</div>
<script type="text/javascript">
<!--
    setDateTime(1);
//-->
</script>
@endsection

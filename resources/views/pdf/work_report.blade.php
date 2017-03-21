<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>勤怠管理報告書</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>

<style>
table.border td,th {
  border: 1px solid #000000;
}
</style>

<div class="container">

    <div class="row">
        <div class="col-md-12">
        <h1>勤怠管理報告書</h1>

        <table class="border">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤時間</th>
                <th>退勤時間</th>
                <th>勤務時間</th>
                <th>休暇</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($works as $work)
            <tr>
                <td><span style="color :{{ holiday_color($work->date_at) }};">
                 {{ date_formatA($work->date_at,"m/d") . date_week($work->date_at) }}</span></td>
                <td>{{ date_formatA($work->attendance_at,"G:i") }}</td>
                <td><span>{{ wdate_nextDay($work->date_at,$work->leaving_at) }} {{ date_formatA($work->leaving_at,"G:i") }}</span></td>
                <td>{{ $work->worktime }}</td>
                <td>{{ $work->vacation_type }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3"><b>勤務時間合計</b></td>
                <td colspan="2"><b class="lead">{{ $max_worktime }} h</b></td>
            </tr>
        </tbody>
        </table>
        </div>
    </div> 
</div>
</body>
</html>
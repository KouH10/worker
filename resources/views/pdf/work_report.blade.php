<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>勤怠管理報告書</title>
</head>
<body>
<style>
table{
  border-collapse:collapse;
}
th{
  color: #3366CC;
  height: 18px;
  font-weight: bold;
  border-bottom: 2px solid #3366CC;
  text-align: center;
  vertical-align: middle;
}
td{
  padding: 0px;
  height: 17px;
  text-align: center;
  vertical-align: middle;
  border-bottom: 0.5px solid #999999;
}
td.tfoot{
  padding: 1px;
  text-align: center;
  font-weight: bold;
  border-bottom: none;
}
.red{
  color: #ff6347;
}
.title{
  display: inline-block;
  width: 100%;
  font-size: 150%;
  text-align: center;
  font-weight: bold;
}
</style>
<div>

    <p class="title">{{substr($period,0,4)}}年{{substr($period,5,2)}}月分　勤怠管理報告書</p>
    <div class="row">
         <label>会社名：{{$affiliation->group->name}}</label>
    </div>
    <div class="row">
    <label>氏名&nbsp;&nbsp;&nbsp;&nbsp;：{{$affiliation->user->name}}</label>
   </div>
   <div class="row"></div>
        <table id="table-01">
        <thead>
            <tr>
              <th width="65px">日付</th>
              <th width="50px" >出勤</th>
              <th width="50px" >退勤</th>
              <th width="60px" >総時間</th>
              <th width="60px" >所定内</th>
              <th width="60px" >時間外</th>
              <th width="50px" >深夜</th>
              <th width="50px" >休日</th>
      				<th width="100px" >休暇</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($works as $work)
            <tr>
              <td width="65px"><span class="{{ holiday_color($work['date_at']) }}">
      	    	{{ date_formatA($work['date_at'],"m/d") . date_week($work['date_at']) }}</span></td>
      	    	<td width="50px">{{ date_formatA($work['attendance_at'],"G:i") }}</td>
      	    	<td width="50px"><span>{{wdate_nextDay($work['date_at'],$work['leaving_at']) }} {{ date_formatA($work['leaving_at'],"G:i") }}</span></td>
      	    	<td width="60px">{{ gethour($work['worktime']) }}&nbsp;</td>
      	    	<td width="60px">{{ gethour($work['predeterminedtime']) }}&nbsp;</td>
      	    	<td width="60px">{{ gethour($work['overtime'])}}&nbsp;</td>
      	    	<td width="50px">{{ gethour($work['nighttime']) }}&nbsp;</td>
      	    	<td width="50px">{{ gethour($work['holidaytime']) }}&nbsp;</td>
      				<td width="100px">{{ getvacationname($work['groupvacation_id']) }}&nbsp;</td>
            </tr>
            @endforeach
            <tr>
                <td class="tfoot" colspan="3">勤務時間合計</td>
          			<td class="tfoot" >{{ gethour($gokei['worktime'])}} h</td>
          			<td class="tfoot" >{{ gethour($gokei['predeterminedtime'])}} h</td>
          			<td class="tfoot" >{{ gethour($gokei['overtime'])}} h</td>
          			<td class="tfoot" >{{ gethour($gokei['nighttime'])}} h</td>
          			<td class="tfoot" >{{ gethour($gokei['holidaytime'])}} h</td>
          			<td class="tfoot" ></td>
            </tr>
        </tbody>
        </table>
        </div>
    </div>
</div>
</body>
</html>

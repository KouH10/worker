<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Work;
use App\WorkOther;
use App\Group;
use App\Affiliation;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Libs\HolidayDateTime;

class RegisterController extends Controller
{
	/**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

       	/**
  	* Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
			$user_id = $request->get('user_id');
    	$date = Carbon::parse($request->get('date_at'));
    	$work = work::where('user_id', $user_id)
	            ->where('date_at',$date)->first();
	    if(count($work) === 0)
	    {
        $work = new Work(array('date_at'=>$date,'user_id'=>$user_id));
	    }

			$affiliation = Affiliation::where('user_id', $user_id)
									->where('applystart_at','<=',$date->format('Y/m/d'))
									->where('applyend_at','>=',$date->format('Y/m/d'))->first();

			if( $date->format('d') < $affiliation->group->monthstart ){
					$period = $date->format('Y年m月');
			}else{
					$period = $date->copy()->addMonths(1)->format('Y年m月');
			}
      $work_others = WorkOther::where('work_id', $work->id)
        ->where('kbn','1')->first();
      if(count($work_others) === 0)
      {
        $work_others = new WorkOther(array('work_id' => $work->id));
      }

    	return view('work/edit',compact('work','period','work_others'));
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
      	// バリエーション作成
      	$inputs = $request->all();
      	$rules = [
          ];
          $validation = \Validator::make($inputs,$rules);
          if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        $target = Carbon::parse($request->get('date_at'));
				$user_id = $request->get('user_id');
        //グループ情報取得
        $affiliation = Affiliation::where('user_id', $user_id)
                    ->where('applystart_at','<=',$target->format('Y/m/d'))
                    ->where('applyend_at','>=',$target->format('Y/m/d'))->first();

      	$work = work::where('user_id', $user_id)
          	->where('date_at',Carbon::parse($request->get('date_at')))->first();

        if(count($work) === 0)
        {
        	$work = new Work(array('user_id' => $user_id,'date_at'=>Carbon::parse($request->get('date_at'))));
        }

          // 出勤時間設定
          $work->attendance_at = $this->uniteDatime($request,'attendance_at_h','attendance_at_m');
          // 退勤時間設定
          $work->leaving_at = $this->uniteDatime($request,'leaving_at_h','leaving_at_m');

					$work->worktime = 0;
					$work->predeterminedtime = 0;
					$work->overtime = 0;
					$work->nighttime = 0;
					$work->holidaytime = 0;
					$holiday = new HolidayDateTime($target);
					// 総時間計算
					if(!empty($request->get('attendance_at_h')) and !empty($request->get('attendance_at_m'))
					   and !empty($request->get('leaving_at_h')) and !empty($request->get('leaving_at_m')))
					{
						$work->worktime = wdate_time($work->attendance_at ,$work->leaving_at);
							// 休憩時間の設定
							if($work->worktime > 360)
							{
									$work->worktime = $work->worktime
													- wdate_time($affiliation->group->reststart_st ,$affiliation->group->restend_st);
							}

							if($target->dayOfWeek == $affiliation->group->legalholiday)
							{// 休日時間
									$work->holidaytime = $work->worktime;
							}elseif($target->dayOfWeek == $affiliation->group->notlegalholiday or $holiday->holiday())
							{// 法定外休日及び祝日
									$work->overtime = $work->worktime;
							}elseif($work->worktime <= wdate_time($affiliation->group->workingstart_st ,$affiliation->group->workingend_st) - wdate_time($affiliation->group->reststart_st ,$affiliation->group->restend_st))
							{//所定労働時間内
									$work->predeterminedtime = $work->worktime;
							}else
							{//所定労働時間を超える
								$work->predeterminedtime = wdate_time($affiliation->group->workingstart_st ,$affiliation->group->workingend_st) - wdate_time($affiliation->group->reststart_st ,$affiliation->group->restend_st);
								$work->overtime =  $work->worktime - ( wdate_time($affiliation->group->workingstart_st ,$affiliation->group->workingend_st) - wdate_time($affiliation->group->reststart_st ,$affiliation->group->restend_st));
							}
							// 深夜時間
							$work->nighttime = getnighttime($work->attendance_at,$work->leaving_at
									,$affiliation->group->nightstart_st,$affiliation->group->nightend_st,$target);
					}

					$work->content = $request->get('content');
					$work->save();

          $work_others = WorkOther::where('work_id', $work->id)
            ->where('kbn','1')->first();
          if(count($work_others) === 0)
          {
            $work_others = new WorkOther(array('work_id' => $work->id));
          }
          //自社作業
          if( (!empty($request->get('company_work_start_h')) and !empty($request->get('company_work_start_m'))) or
              (!empty($request->get('company_work_end_h')) and !empty($request->get('company_work_end_m'))) or
              (!empty($request->get('company_work_memo'))))
          {
              $work_others->kbn = '1';
              $work_others->start_at = $this->uniteDatime($request,'company_work_start_h','company_work_start_m');
              $work_others->end_at = $this->uniteDatime($request,'company_work_end_h','company_work_end_m');
              $work_others->memo = $request->get('company_work_memo');
              $work_others->save();
          }else {
              $work_others->delete();
          }

					if( $target->format('d') < $affiliation->group->monthstart ){
							$period = $target->format('Y年m月');
					}else{
							$period = $target->copy()->addMonths(1)->format('Y年m月');
					}
					return redirect()->to('/workconfirmation?period='.$period .'&user_id=' .$user_id);

    }


    /**
    * 入力した時間と分を結合して日時を渡す
    *
    * @return String
    */
    private function uniteDatime(Request $request,$hour,$minute)
    {
        if(empty($request->get($hour)) or empty($request->get($minute))){
          return null;
        }elseif( substr($request->get($hour),0,3) == '翌'){
          return wdate_end( Carbon::parse($request->get('date_at'))->addDay(1)->toDateString()
            .substr($request->get($hour),3) .':' .$request->get($minute) );
        }else {
          return wdate_end( $request->get('date_at')
            .$request->get($hour) .':' .$request->get($minute) );
        }
    }
}

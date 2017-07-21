<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Work;
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
    	$date = Carbon::parse($request->get('date_at'));
    	$work = work::where('user_id', \Auth::user()->id)
	            ->where('date_at',$date)->first();
	    if(count($work) === 0)
	    {
        $work = new Work(array('date_at'=>$date));
	    }

			if( $date->format('d') <=20 ){
					$period = $date->format('Y年m月');
			}else{
					$period = $date->addMonths(1)->format('Y年m月');
			}

    	return view('work/edit',compact('work','period'));
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

        //グループ情報取得
        $affiliation = Affiliation::where('user_id', \Auth::user()->id)
                    ->where('applystart_at','<=',$target->format('Y/m/d'))
                    ->where('applyend_at','>=',$target->format('Y/m/d'))->first();

      	$work = work::where('user_id', \Auth::user()->id)
          	->where('date_at',Carbon::parse($request->get('date_at')))->first();

          if(count($work) === 0)
        {
        	$work = new Work(array('user_id' => \Auth::user()->id,'date_at'=>Carbon::parse($request->get('date_at'))));
        }

          // 出勤時間設定
          if(!empty($request->get('attendance_at_h')) and !empty($request->get('attendance_at_m')))
          {
          	$work->attendance_at = wdate_start($request->get('date_at')
						     .$request->get('attendance_at_h') .':' .$request->get('attendance_at_m') );
          }else
          {
          	$work->attendance_at = null;
          }

          // 退勤時間設定
          if(empty($request->get('leaving_at_h')) or empty($request->get('leaving_at_m'))){
						$work->leaving_at = null;
          }elseif( substr($request->get('leaving_at_h'),0,3) == '翌'){
          	$work->leaving_at = wdate_end( Carbon::parse($request->get('date_at'))->addDay(1)->toDateString()
						  .substr($request->get('leaving_at_h'),3) .':' .$request->get('leaving_at_m') );
          }else {
						$work->leaving_at = wdate_end( $request->get('date_at')
							.$request->get('leaving_at_h') .':' .$request->get('leaving_at_m') );
          }

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

					if( $target->format('d') <=20 ){
							$period = $target->format('Y年m月');
					}else{
							$period = $target->addMonths(1)->format('Y年m月');
					}
					return redirect()->to('/workconfirmation?period='.$period);

    }
}

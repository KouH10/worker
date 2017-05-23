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
	    $nextflg = 0;
	    if(count($work) === 0)
	    {
            $work = new Work(array('date_at'=>$date));
	    }elseif( $date->diffInDays(Carbon::parse($work->leaving_at)) === 1)
	    {
	    	$nextflg = 1;
	    }
    	return view('work/edit',compact('work','nextflg'));
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if($request->get('work_regist') == 'work_regist')
        {
        	// バリエーション作成
        	$inputs = $request->all();
        	$rules = [
            	'attendance_at'=>'date_format:"G:i"',
            	'leaving_at'=>'date_format:"G:i"'
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
            if(!empty($request->get('attendance_at')))
            {
            	$work->attendance_at = wdate_start($request->get('date_at') . $request->get('attendance_at'));
            }else
            {
            	$work->attendance_at = null;
            }

            //　退勤時間設定
            if(!empty($request->get('leaving_at')) and $request->get('next_day') == 1)
            {
            	$work->leaving_at = wdate_end( Carbon::parse($request->get('date_at'))->addDay(1)->toDateString() . $request->get('leaving_at'));
            }elseif(!empty($request->get('leaving_at')))
            {
            	$work->leaving_at = wdate_end( $request->get('date_at') . $request->get('leaving_at'));
            }else
            {
            	$work->leaving_at = null;
            }
						// 土曜日の場合、その週の労働時間を取得
		        $sumWeek = 0;
		        if($target->dayOfWeek == $affiliation->group->notlegalholiday)
		        {
								$sumWeek = work::where('user_id', \Auth::user()->id)
										->where('date_at','>=',$target->copy()->startOfWeek()->subDay(1))
										->where('date_at','<=',$target)
										->sum('worktime');
		        }
            $work->worktime = 0;
            $work->predeterminedtime = 0;
            $work->overtime = 0;
            $work->nighttime = 0;
            $work->holidaytime = 0;
            $holiday = new HolidayDateTime($target);
            // 総時間計算
           	if(!empty($request->get('attendance_at')) and !empty($request->get('leaving_at')))
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
                }
								elseif($target->dayOfWeek == $affiliation->group->notlegalholiday)
                {// 法定外休日及び祝日
									  if($sumWeek > 2400)
										{
												$work->overtime = $work->worktime;
										}else {
												$work->overtime = max(0,$sumWeek + $work->worktime - 2400);
												$work->predeterminedtime = max(0,$work->worktime - $work->overtime);
										}
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

            return $this->index($request);
        }elseif($request->get('vacation_regist') == 'vacation_regist'){

        	$work = work::where('user_id', \Auth::user()->id)
            	->where('date_at',Carbon::parse($request->get('date_at')))->first();

            if(count($work) === 0)
	        {
	        	$work = new Work(array('user_id' => \Auth::user()->id,'date_at'=>Carbon::parse($request->get('date_at'))));
	        }

	        $work->vacation_type = $request->get('vacation_type');
	        if($work->vacation_type === 0){ $work->vacation_type = null; }
	        $work->save();

        	return $this->index($request);
        }
    }
}

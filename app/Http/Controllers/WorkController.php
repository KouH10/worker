<?php

namespace App\Http\Controllers;

use App\Work;
use App\Affiliation;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Libs\HolidayDateTime;

class WorkController extends Controller
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
    public function index()
    {
        $now_d = Carbon::now('Asia/Tokyo')->format('Y/m/d');
        $now_t = Carbon::now('Asia/Tokyo')->format('H:i');
        /* 本日の打刻情報取得　*/
        $work = work::where('user_id', \Auth::user()->id)
            ->where('date_at',Carbon::today('Asia/Tokyo'))->first();
        if(count($work) === 0)
        {
            $work = new Work(array('date_at'=> Carbon::now('Asia/Tokyo')));
        }
        /* 同一グループユーザ取得 */
        $affiliation = Affiliation::where('user_id', \Auth::user()->id)
            ->where('applystart_at','<=',$now_d)->where('applyend_at','>=',$now_d)->first();
        /* グループの勤務状況取得 */
        if(!is_null($affiliation))
        {
            $datas = Affiliation::leftjoin('works', function ($join) {
                        $join->on('affiliations.user_id', '=', 'works.user_id')
                             ->where('works.date_at',Carbon::today('Asia/Tokyo'));
                        })->where('group_id',$affiliation->group_id)
                        ->where('applystart_at','<=',$now_d)->where('applyend_at','>=',$now_d)
                        ->leftJoin('users', 'affiliations.user_id', '=', 'users.id')
                        ->orderBy('affiliations.employee_no','asc')
                        ->get(['affiliations.user_id','users.name','works.attendance_at','works.leaving_at']);
        }else
        {
            $datas = ['user_id'=>'','name'=>'','attendance_at'=>'','leaving_at'=>''];
        }
        return view('work',compact('now_d','now_t','work','affiliation','datas'));
    }
    /**
     *
     * @param ContactRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $target = Carbon::today('Asia/Tokyo');
        $work = work::where('user_id', \Auth::user()->id)
            ->where('date_at',$target)->first();

        //グループ情報取得
        $affiliation = Affiliation::where('user_id', \Auth::user()->id)
                    ->where('applystart_at','<=',$target->format('Y/m/d'))
                    ->where('applyend_at','>=',$target->format('Y/m/d'))->first();

        if(count($work) === 0)
        {
            $work = new Work(array('user_id' => \Auth::user()->id,'date_at'=>$target));
        }

        // 出勤時間設定
        if ($request->get('attendance') == 'attendance' && is_null($work->attendance_at))
        {
            $work->attendance_at = wdate_start(Carbon::now('Asia/Tokyo'));
            $work->attendance_stamp_at = Carbon::now('Asia/Tokyo');
        }

        // 退勤時間設定
        if($request->get('leaving') == 'leaving' )
        {
            $work->leaving_at = wdate_end(Carbon::now('Asia/Tokyo'));
            $work->leaving_stamp_at = Carbon::now('Asia/Tokyo');
        }

        $work->worktime = 0;
        $work->predeterminedtime = 0;
        $work->overtime = 0;
        $work->nighttime = 0;
        $work->holidaytime = 0;
        $holiday = new HolidayDateTime($target);
        // 総時間計算
        if(!empty($work->attendance_at) and !empty($work->leaving_at))
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
        $work->save();

        return redirect()->to('/work');
    }
}

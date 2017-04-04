<?php

namespace App\Http\Controllers\Work;


use App\Http\Controllers\Controller;
use App\Work;
use App\WorkVacation;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use TCPDF;

class ConfirmationController extends Controller
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
        $now = Carbon::now('Asia/Tokyo');

        if( is_null($request->get('period')) ){
            $period = Carbon::now('Asia/Tokyo')->format('Y/m');
        }else{
            $period = $request->get('period');
        }

        $min =  Work::where('user_id', \Auth::user()->id)->min('date_at');
        if(is_null($min))
        {
            $min = Carbon::now('Asia/Tokyo')->startOfMonth();
        }else
        {
            $min = Carbon::parse($min)->startOfMonth();
        }

        $dates = array();
        while(1)
        {
            array_push($dates,$min->format('Y/m'));
            if( $min->diffInMonths(Carbon::now('Asia/Tokyo')->startOfMonth()->addDay(1)) == 0)
            {
                break;
            }else
            {
                $min->addMonths(1);
            }
        }

        $works = array();
        $max_worktime = 0;
        $now_start = 1;
        $now_end = Carbon::parse($period . '/1')->endOfMonth()->format('d');
        $keydate = Carbon::parse($period . '/1');
        for($i = $now_start; $i<= $now_end; $i++){
	        $work = work::where('works.user_id', \Auth::user()->id)
              ->leftJoin('work_vacations', function ($join) {
                $join->on('works.user_id', '=', 'work_vacations.user_id')->orOn('works.date_at', '=', 'work_vacations.date_at');
              })
	            ->where('works.date_at',$keydate)->first();
	        if(count($work) === 0)
	        {
	        	$work = new Work(array('date_at'=>Carbon::parse($keydate)));
	        }elseif( !is_null($work->worktime) and !empty($work->worktime))
          {
                $max_worktime = $max_worktime + $work->worktime;
          }
	        array_push( $works,$work);
          $keydate->addDay(1);
        }
       	return view('work/index',compact('dates','period','works','max_worktime'));
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if($request->get('search') == 'search')
        {
            return $this->index($request);
        }elseif($request->get('report') == 'report')
        {
            $now = Carbon::parse($request->get('dateFrom'));
            $now_start =  mb_substr($request->get('dateFrom'),8,2);
            $now_end = mb_substr($request->get('dateTo'),8,2);
            $now_from = $request->get('dateFrom');
            $now_to = $request->get('dateTo');
            $works = array();
            $max_worktime = 0;
            for($i = $now_start; $i<= $now_end; $i++){
                $work = work::where('user_id', \Auth::user()->id)
                    ->where('date_at',$now)->first();
                if(count($work) === 0)
                {
                    $work = new Work(array('date_at'=>Carbon::parse($now)));
                }elseif( !is_null($work->worktime) and !empty($work->worktime))
                {
                    $max_worktime = $max_worktime + $work->worktime;
                }
                array_push( $works,$work);
                $now->addDay(1);
            }
            //return $this->index($request);
             //PDF作成
            $pdf = new TCPDF();
            //フォント名,フォントスタイル（空文字でレギュラー）,フォントサイズ
            $pdf->SetFont('kozminproregular', '', 11);//
            //ページを追加
            $pdf->addPage();
            //viewから起こす
            $pdf->writeHTML(view('pdf/work_report',compact('works','max_worktime'))->render());
            //第一引数はファイル名、第二引数で挙動を指定（D=ダウンロード）
            $pdf->output('work_report' . '.pdf', 'D');
            //今回は適当にブラウザバック
            return Redirect::back();
        }
    }
}

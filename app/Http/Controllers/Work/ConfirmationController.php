<?php

namespace App\Http\Controllers\Work;


use App\Http\Controllers\Controller;
use App\Work;
use App\WorkVacation;
use App\Affiliation;
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

        if( is_null($request->get('period')) && Carbon::now('Asia/Tokyo')->format('d') <=20 ){
            $period = Carbon::now('Asia/Tokyo')->format('Y年m月');
        }else if( is_null($request->get('period')) ){
            $period = Carbon::now('Asia/Tokyo')->addMonths(1)->format('Y年m月');
        }
        else{
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
            array_push($dates,$min->format('Y年m月'));
            if( $min->diffInMonths(Carbon::now('Asia/Tokyo')->startOfMonth()->addDay(1)) == 0)
            {
                break;
            }else
            {
                $min->addMonths(1);
            }
        }
        array_push($dates,Carbon::now('Asia/Tokyo')->startOfMonth()->addMonths(1)->format('Y年m月'));
        $p = str_replace("年","/",$period);
        $p = str_replace("月","/",$p);
        $now = Carbon::parse($request->get('dateFrom'));
        $works = array();
        $gokei = ['worktime'=>0,'predeterminedtime'=>0,'overtime'=>0,'nighttime'=>0,'holidaytime'=>0];
        $now_start = 21;
        $now_end = Carbon::parse($p.'21');
        $keydate = Carbon::parse($p.'21')->subMonths(1);
        while(1){
          if($now_end->eq($keydate)){break;}
          $work = work::where('user_id', \Auth::user()->id)
              ->where('date_at',$keydate)->first();
          if(count($work) === 0)
          {
            $work = new Work(array('date_at'=>Carbon::parse($keydate)));
          }elseif( !is_null($work->worktime) and !empty($work->worktime))
          {
                $gokei['worktime'] = $gokei['worktime'] + $work->worktime;
                $gokei['predeterminedtime'] = $gokei['predeterminedtime'] + $work->predeterminedtime;
                $gokei['overtime'] = $gokei['overtime'] + $work->overtime;
                $gokei['nighttime'] = $gokei['nighttime'] + $work->nighttime;
                $gokei['holidaytime'] = $gokei['holidaytime'] + $work->holidaytime;
          }
          $data = [
            'date_at'=>$work->date_at,
            'attendance_at'=>$work->attendance_at,
            'leaving_at'=>$work->leaving_at,
            'attendance_stamp_at'=>$work->attendance_stamp_at,
            'leaving_stamp_at'=>$work->leaving_stamp_at,
            'worktime'=>$work->worktime,
            'content'=>$work->content,
            'predeterminedtime'=>$work->predeterminedtime,
            'overtime'=>$work->overtime,
            'nighttime'=>$work->nighttime,
            'holidaytime'=>$work->holidaytime,
          ];
          $workVacation = WorkVacation::where('user_id', \Auth::user()->id)
              ->where('date_at',$keydate)->first();
          if(count($workVacation) === 0)
          {
              $data += array('groupvacation_id'=>'');
          }else
          {
              $data += array('groupvacation_id'=>$workVacation->groupvacation_id);
          }
          array_push( $works,$data);
          $keydate->addDay(1);
        }

       	return view('work/index',compact('dates','period','works','gokei'));
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
            $period = $request->get('period');
            $p = str_replace("年","/",$period);
            $p = str_replace("月","/",$p);
            $now = Carbon::parse($request->get('dateFrom'));
            $works = array();
            $gokei = ['worktime'=>0,'predeterminedtime'=>0,'overtime'=>0,'nighttime'=>0,'holidaytime'=>0];
            $now_start = 21;
            $now_end = Carbon::parse($p . '21');
            $keydate = Carbon::parse($p . '21')->subMonths(1);
            while(1){
              if($now_end->eq($keydate)){break;}
              $work = work::where('user_id', \Auth::user()->id)
                  ->where('date_at',$keydate)->first();
    	        $work = work::where('user_id', \Auth::user()->id)
    	            ->where('date_at',$keydate)->first();
    	        if(count($work) === 0)
    	        {
    	        	$work = new Work(array('date_at'=>Carbon::parse($keydate)));
    	        }elseif( !is_null($work->worktime) and !empty($work->worktime))
              {
                    $gokei['worktime'] = $gokei['worktime'] + $work->worktime;
                    $gokei['predeterminedtime'] = $gokei['predeterminedtime'] + $work->predeterminedtime;
                    $gokei['overtime'] = $gokei['overtime'] + $work->overtime;
                    $gokei['nighttime'] = $gokei['nighttime'] + $work->nighttime;
                    $gokei['holidaytime'] = $gokei['holidaytime'] + $work->holidaytime;
              }
              $data = [
                'date_at'=>$work->date_at,
                'attendance_at'=>$work->attendance_at,
                'leaving_at'=>$work->leaving_at,
                'attendance_stamp_at'=>$work->attendance_stamp_at,
                'leaving_stamp_at'=>$work->leaving_stamp_at,
                'worktime'=>$work->worktime,
                'content'=>$work->content,
                'predeterminedtime'=>$work->predeterminedtime,
                'overtime'=>$work->overtime,
                'nighttime'=>$work->nighttime,
                'holidaytime'=>$work->holidaytime,
              ];
              $workVacation = WorkVacation::where('user_id', \Auth::user()->id)
                  ->where('date_at',$keydate)->first();
              if(count($workVacation) === 0)
    	        {
    	        	  $data += array('groupvacation_id'=>'');
    	        }else
              {
                  $data += array('groupvacation_id'=>$workVacation->groupvacation_id);
              }
    	        array_push( $works,$data);
              $keydate->addDay(1);
            }

            /* 同一グループユーザ取得 */
            $affiliation = Affiliation::where('user_id', \Auth::user()->id)
                ->where('applystart_at','<=',$now_end)->where('applyend_at','>=',$now_end)->first();

            //return $this->index($request);
             //PDF作成
            $pdf = new TCPDF();
            //ヘッダ線削除
            $pdf->setPrintHeader( false );
            //フォント名,フォントスタイル（空文字でレギュラー）,フォントサイズ
            $pdf->SetFont('kozminproregular', '', 11);//
            //余白設定
            $pdf->SetMargins(8,5,8,true);
            //自動改頁ページしない
            $pdf->SetAutoPageBreak(false);
            //ページを追加
            $pdf->addPage();
            //viewから起こす
            $pdf->writeHTML(view('pdf/work_report',compact('period','works','gokei','affiliation'))->render());
            //第一引数はファイル名、第二引数で挙動を指定（D=ダウンロード）
            $pdf->output(substr($p,0,4).substr($p,5,2).'kinmu.pdf', 'D');
            //今回は適当にブラウザバック
            return Redirect::back();
        }
    }
}

<?php

namespace App\Http\Controllers\Work;


use App\Http\Controllers\Controller;
use App\Work;
use App\WorkVacation;
use App\Affiliation;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use TCPDF;
use SplTempFileObject;

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

        $affiliation = Affiliation::where('user_id', \Auth::user()->id)
                    ->where('applystart_at','<=',$now->format('Y/m/d'))
                    ->where('applyend_at','>=',$now->format('Y/m/d'))->first();

        if( is_null($request->get('period')) &&
          Carbon::now('Asia/Tokyo')->format('d') < $affiliation->group->monthstart ){
            $period = Carbon::now('Asia/Tokyo')->format('Y年m月');
        }else if( is_null($request->get('period')) ){
            $period = Carbon::now('Asia/Tokyo')->addMonths(1)->format('Y年m月');
        }
        else{
            $period = $request->get('period');
        }

        $p = str_replace("年","/",$period);
        $p = str_replace("月","/",$p);
        $works = array();
        $gokei = ['date'=>0,'worktime'=>0,'predeterminedtime'=>0,'overtime'=>0,'nighttime'=>0,'holidaytime'=>0];
        $now_end = Carbon::parse($p.$affiliation->group->monthstart);
        $keydate = Carbon::parse($p.$affiliation->group->monthstart)->subMonths(1);
        while(1){
          if($now_end->eq($keydate)){break;}
            $work = work::where('user_id', \Auth::user()->id)
              ->where('date_at',$keydate)->first();
          if(count($work) === 0)
          {
            $work = new Work(array('date_at'=>Carbon::parse($keydate)));
          }elseif( !is_null($work->worktime) and !empty($work->worktime) and $work->worktime > 0)
          {
            $gokei['date'] = $gokei['date'] + 1;
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

        $period = $request->get('period');
        $p = str_replace("年","/",$period);
        $p = str_replace("月","/",$p);
        $now = Carbon::parse($request->get('dateFrom'));

        $affiliation = Affiliation::where('user_id', \Auth::user()->id)
                    ->where('applystart_at','<=',$now->format('Y/m/d'))
                    ->where('applyend_at','>=',$now->format('Y/m/d'))->first();

        $works = array();
        $gokei = ['worktime'=>0,'predeterminedtime'=>0,'overtime'=>0,'nighttime'=>0,'holidaytime'=>0];
        $now_end = Carbon::parse($p . $affiliation->group->monthstart);
        $keydate = Carbon::parse($p . $affiliation->group->monthstart)->subMonths(1);
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
        if($request->get('report') == 'report')
        {
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
        }elseif($request->get('csv') == 'csv')
        {
            $export_csv_title = array( "日付", "出勤", "退勤", "総時間", "所定内", "時間外",
              "深夜", "休日", "休暇","出勤打刻","退勤打刻","勤務内容" );
            $stream = fopen('php://temp', 'w');
            fputcsv($stream, $export_csv_title);

            foreach ($works as $wk) {
              $cw = [
                'date_at'=>date_formatA($wk['date_at'],"m/d") . date_week($wk['date_at']),
                'attendance_at'=>date_formatA($wk['attendance_at'],"G:i"),
                'leaving_at'=>wdate_nextDay($wk['date_at'],$wk['leaving_at']).date_formatA($wk['leaving_at'],"G:i"),
                'worktime'=>gethour($wk['worktime']),
                'predeterminedtime'=>gethour($wk['predeterminedtime']),
                'overtime'=>gethour($wk['overtime']),
                'nighttime'=>gethour($wk['nighttime']),
                'holidaytime'=>gethour($wk['holidaytime']),
                'groupvacation_id'=>getvacationname($wk['groupvacation_id']),
                'attendance_stamp_at'=>$wk['attendance_stamp_at'],
                'leaving_stamp_at'=>$wk['leaving_stamp_at'],
                'content'=>$wk['content']
              ];
              fputcsv($stream, $cw);
            }
            $headers = array(
              'Content-Type' => 'text/csv',
              'Content-Disposition' => "attachment; filename=".substr($p,0,4).substr($p,5,2) ."kinmu.csv");
            rewind($stream);
            $csv = mb_convert_encoding(str_replace(PHP_EOL, "\r\n",
              stream_get_contents($stream)), 'SJIS', 'UTF-8');
            return \Response::make($csv, 200, $headers);
        }else
        {
            return $this->index($request);
        }
    }
}

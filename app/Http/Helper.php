<?php
// app/Http/helper.php
use \Carbon\Carbon;
use App\Libs\HolidayDateTime;

	/**
     * 日時がnull及び空以外の場合のみ日付変換を実施して返却します
     *
     * @param  string  $dt
     * @param  string  $format
     * @return string
     */
  function date_formatA($dt,$fromat) {

	 	if( is_null($dt) or empty ($dt))
	 	{
	 		return '';
	 	}elseif(!strtotime($dt))
	 	{
	 		return $dt;
	 	}else
	 	{
	 		return date($fromat, strtotime($dt));
	 	}
	}

	function date_week($dt)
	{
		if( is_null($dt) or empty ($dt))
	 	{
	 		return '';
	 	}
		$wk = date('N', strtotime($dt));
 		switch ($wk) {
 			case 1:
 				$wk = "月";
 				break;
 			case 2:
 				$wk = "火";
 				break;
 			case 3:
 				$wk = "水";
 				break;
 			case 4:
 				$wk = "木";
 				break;
 			case 5:
 				$wk = "金";
 				break;
 			case 6:
 				$wk = "土";
 				break;
 			case 7:
 				$wk = "日";
 				break;
 		}
 		return $wk;
	}

	/**
     * 15分単位変換メソッド
     *
     * @param  string  $dt
     * @param  int     $flg
     * @return string
     */
	function minute_conversion($dt,$flg)
	{
		$cdt = Carbon::parse($dt);
        $cdt->second = 0;
		// 15分切り上げ
		if($flg == 0)
		{
			if( $cdt->minute > 45)
			{
				$cdt->addHour(1);
				$cdt->minute = 0;
			}else if($cdt->minute > 30)
			{
				$cdt->minute = 45;
			}else if($cdt->minute > 15)
			{
				$cdt->minute = 30;
			}else if($cdt->minute > 0)
			{
				$cdt->minute = 15;
			}else
			{
				$cdt->minute = 0;
			}
		}else
		{
			if( $cdt->minute >= 45)
			{
				$cdt->minute = 45;
			}else if($cdt->minute >= 30)
			{
				$cdt->minute = 30;
			}else if($cdt->minute >= 15)
			{
				$cdt->minute = 15;
			}else if($cdt->minute >= 0)
			{
				$cdt->minute = 0;
			}
		}
		return $cdt;
	}

	/**
     * 勤務開始時間
     *
     * @param  string  $dt
     * @return string
     */
	function wdate_start($dt) {
		$cdt = Carbon::parse($dt);
		return minute_conversion($cdt,0);
	}

	/**
     * 勤務終了時間
     *
     * @param  string  $dt
     * @return string
     */
	function wdate_end($dt) {
		return minute_conversion($dt,1);
	}

	/**
     * 勤務時間(分)
     *
     * @param  Corbon  $sdt
     * @param  Corbon  $edt
     * @return int
     */
	function wdate_time($sdt,$edt) {
		$csdt = Carbon::parse($sdt);
		$cedt = Carbon::parse($edt);

		if(is_null($sdt) or is_null($edt))
		{
			return 0;
		}elseif($csdt->lt($cedt))
		{
			$m = $csdt->diffInMinutes($cedt);
			return $m;
		}else
		{
			return 0;
		}

	}

	/**
     * 翌日チェック
     *
     * @param  Corbon  $sdt
     * @param  Corbon  $edt
     * @return string
     */
	function wdate_nextDay($sdt,$edt) {

		$csdt = Carbon::parse($sdt);
		$cedt = Carbon::parse($edt);

		if(is_null($sdt) or is_null($edt))
		{
			return '';
		}elseif($csdt->diffInDays($cedt) === 1)
		{
			return '翌';
		}else
		{
			return '';
		}

	}
	/*
	 * 日時フォーマットを取得
	*/
  function getDateformat($sdt,$pam)
	{
		if(is_null($sdt))
 	  {
 			return '';
 	  }else {
 	  	$csdt = Carbon::parse($sdt);
			$csdt->setToStringFormat($pam);
			return $csdt;
 	  }

	}
	function getDateformatN($sdt,$edt,$pam)
	{
		if(is_null($sdt) or is_null($edt))
 	  {
 			return '';
 	  }else {
 	  	$csdt = Carbon::parse($sdt);
			$csdt->setToStringFormat($pam);
			return wdate_nextDay($edt,$sdt) .$csdt;
 	  }
	}
	/**
     * 休日の場合、赤色を返却する
     *
     * @param  Corbon  $dt
     * @return string
     */
	function holiday_color($dt) {

		$holiday = new HolidayDateTime($dt);

		if($holiday->holiday())
		{
			return "red";
		}

		switch( $holiday->format('w'))
		{
			case 0:
			case 6:
				return "red";
				break;
		}

		return "";

	}

	/**
     * 分を時間に変換する
     *
     * @param  integer $m
     * @return
     */
	function gethour($m) {

		if(is_null($m) or is_null($m))
		{
			return '';
		}elseif($m == 0)
		{
			return '0';
		}else
		{
			return round($m /60,2);
		}
	}

	/**
     * 深夜勤務間算出
     *
     * @param  integer $m
     * @return
     */
	function getnighttime($ws,$we,$ns,$ne,$now) {

		$sws = ( Carbon::parse($ws)->hour * 60 ) + Carbon::parse($ws)->minute;
		$swe = ( Carbon::parse($we)->hour * 60 ) + Carbon::parse($we)->minute;
		$sns = ( Carbon::parse(Carbon::parse($now)->format('Y-m-d').$ns)->hour * 60 )
			+ Carbon::parse(Carbon::parse($now)->format('Y-m-d').$ns)->minute;
		$sne = ( Carbon::parse(Carbon::parse($now)->format('Y-m-d').$ne)->hour * 60 )
			+ Carbon::parse(Carbon::parse($now)->format('Y-m-d').$ne)->minute;

		$cns = 0;
		$cne = 1440;
		$cys = 0;
		$cye = 0;

		if(Carbon::parse($now)->diffInDays(Carbon::parse($ws)) >= 1 )
			$cys = $sws;
		else
			$cns = max($sns,$sws);

		if(Carbon::parse($now)->diffInDays(Carbon::parse($we)) >= 1 )
			$cye = min(300,$swe);
		elseif(Carbon::parse(Carbon::parse($now)->format('Y-m-d').$ns)->lt(Carbon::parse($we)))
			$cne = $swe;
		else
			$cne = 0;

		return max(0,( $cne - $cns )) + max(0,($cye - $cys));
	}

	/**
     * 休暇IDの名称を返却する
     *
     * @param  integer $id
     * @return
     */
	function getvacationname($id) {
		switch($id){
			case 1: return "有給" ;
			case 2: return "午前有給" ;
			case 3: return "午後有給" ;
			case 4: return "代休" ;
			case 5: return "欠勤" ;
			case 6: return "夏季休暇" ;
			case 7: return "冬季休暇" ;
			case 8: return "慶弔休暇" ;
			case 9: return "産休" ;
			case 10: return "生理休暇" ;
			case 11: return "育児休暇" ;
			case 12: return "子の看護休暇" ;
			case 13: return "裁判員休暇" ;
			case 14: return "母性健康管理" ;
			case 15: return "介護休業" ;
			case 16: return "介護休暇" ;
		}
			return '';
	}

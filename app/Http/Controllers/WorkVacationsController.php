<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\WorkVacations;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class WorkVacationsontroller extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	//レコードを検索
        $WorkVacations = WorkVacations::where('user_id', \Auth::user()->id)->orderBy('date_at','asc')->get();
        return view('WorkVacations/index',);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	//グループID
    	$groupid = $id;
        //レコードを検索
        $groupHolidays = GroupHoliday::where('group_id', $groupid)->orderBy('date_at','asc')->get();
        //検索結果をビューに渡す
        return view('groupHolidays/index',compact('groupHolidays','groupid'));
    }

	/**
     * グループ休日新規登録
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

		$rules = [
			'date_at'=>'required|unique:group_Holidays,date_at,NULL,id,group_id,'.$request->groupid,
	        'name'=>'required',
	    ];
	    //入力エラーメッセージ
	    $messages = [
	    	'date_at.required'=>'日付は必須です。',
	    	'date_at.unique'=>'既に日付は登録されています。',
	        'name.required'=>'名前は必須です。',
	    ];

	    //入力チェック対象
	    $inputs = $request->all();
	    $validation = \Validator::make($inputs,$rules,$messages);
	    //エラー次の処理
	    if($validation->fails())
	    {
	        return redirect()->back()->withErrors($validation->errors())->withInput();
	    }

	    $groupHoliday = new groupHoliday();
	    $groupHoliday->group_id = $request->groupid; 
	    $groupHoliday->date_at = $request->date_at;
	    $groupHoliday->name = $request->name;

	    $groupHoliday->save();
	    //一覧にリダイレクト
	    return redirect()->to('/groupholidays/'.$request->groupid);
        
    }
}
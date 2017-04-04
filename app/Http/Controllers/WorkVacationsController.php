<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\WorkVacation;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class WorkVacationsController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	//レコードを検索
        $workVacations = WorkVacation::where('user_id', \Auth::user()->id)->orderBy('date_at','asc')->get();
        return view('workvacations/index',compact('workVacations'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('workvacations/create');
    }

    /**
     * 休暇申請編集
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      //レコードを検索
      $workVacation = WorkVacation::find($id);
      return view('workvacations/edit',compact('workVacation'));
    }

    /**
     * 休暇申請削除
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      //レコードを検索
      $workVacation = WorkVacation::find($id);
      $workVacation->delete();
      return redirect()->to('/workvacations');
    }
	/**
     * グループ休日新規登録
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //入力ルール
      $rules = [
          'date_at'=>'required|unique:work_Vacations,date_at,NULL,id,user_id,'.\Auth::user()->id,
          'change_at'=>'date_format:"Y/m/d"',
          'start_at'=>'date_format:"G:i"',
          'end_at'=>'date_format:"G:i"',
      ];
      //入力エラーメッセージ
      $messages = [
        'date_at.required'=>'日付は必須です。',
        'date_at.unique'=>'既に日付は登録されています。',
        'change_at.date_format'=>'振替日付はYYYY/MM/DD形式です。',
        'start_at.date_format'=>'休暇開始時間はHH:MI形式です。',
        'end_at.date_format'=>'休暇終了始時間はHH:MI形式です。',
      ];
      //入力チェック対象
      $inputs = $request->all();
      $validation = \Validator::make($inputs,$rules,$messages);
      //エラー次の処理
      if($validation->fails())
      {
          return redirect()->back()->withErrors($validation->errors())->withInput();
      }

      $wv = new WorkVacation();

      $wv->user_id = \Auth::user()->id;
      $wv->date_at = $request->date_at;
      $wv->groupvacation_id = $request->groupvacation_id;
      if($wv->groupvacation_id == 4)
        $wv->change_at = $request->change_at;
      elseif($wv->groupvacation_id == 9)
      {
        $wv->start_at = $request->start_at;
        $wv->end_at = $request->end_at;
      }
      $wv->save();
      //一覧にリダイレクト
      return redirect()->to('/workvacations');
    }

    /**
       * グループ休日申請更新
       *
       * @return \Illuminate\Http\Response
       */
      public function update(Request $request, $id)
      {
        //入力ルール
        $rules = [
            'change_at'=>'date_format:"Y/m/d"',
            'start_at'=>'date_format:"G:i"',
            'end_at'=>'date_format:"G:i"',
        ];
        //入力エラーメッセージ
        $messages = [
          'date_at.unique'=>'既に日付は登録されています。',
          'change_at.date_format'=>'振替日付はYYYY/MM/DD形式です。',
          'start_at.date_format'=>'休暇開始時間はHH:MI形式です。',
          'end_at.date_format'=>'休暇終了始時間はHH:MI形式です。',
        ];
        //入力チェック対象
        $inputs = $request->all();
        $validation = \Validator::make($inputs,$rules,$messages);
        //エラー次の処理
        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }

        $wv = WorkVacation::find($id);

        $wv->groupvacation_id = $request->groupvacation_id;
        if($wv->groupvacation_id == 4)
          $wv->change_at = $request->change_at;
        elseif($wv->groupvacation_id == 9)
        {
          $wv->start_at = $request->start_at;
          $wv->end_at = $request->end_at;
        }
        $wv->save();
        //一覧にリダイレクト
        return redirect()->to('/workvacations');
      }
}

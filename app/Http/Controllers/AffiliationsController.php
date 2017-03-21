<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Affiliation;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class AffiliationsController extends Controller
{

    //入力ルール
    public $rules = [
        'user_id'=>'required',
        'group_id'=>'required',
        'applystart_at'=>'required|date_format:"Y/m/d"',
        'applyend_at'=>'required|date_format:"Y/m/d"',
        'entry_at'=>'required|date_format:"Y/m/d"',
        'admin'=>'required',
        'employee_no'=>'required',
    ];
    //入力エラーメッセージ
    public $messages = [
        'user_id.required'=>'ユーザは必須です。',
        'group_id.required'=>'グループは必須です。',
        'applystart_at.required'=>'適用開始日は必須です。',
        'applystart_at.date_format'=>'適用開始日は年月日(YYYY/MM/DD)形式の入力です。',
        'applyend_at.required'=>'適用終了日は必須です。',
        'applyend_at.date_format'=>'適用終了日は年月日(YYYY/MM/DD)形式の入力です。',
        'entry_at.required'=>'入社日は必須です。',
        'entry_at.date_format'=>'入社日は年月日(YYYY/MM/DD)形式の入力です。',
        'admin.required'=>'権限は必須です。',
        'employee_no.required'=>'社員番号は必須です。',
    ];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	//所属情報全て
    	$affiliations = Affiliation::all();

        //検索結果をビューに渡す
        return view('affiliations/index',compact('affiliations'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //グループ情報全て
        $groups = Group::all();
		//ユーザ情報全て
		$users = User::all();

        //検索結果をビューに渡す
        return view('affiliations/create',compact('groups','users'));
    }

	/**
     * 所属情報新規登録
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		//入力チェック対象
        $inputs = $request->all();
        $validation = \Validator::make($inputs,$this->rules,$this->messages);
        //エラー次の処理
        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }

        $affiliation = new Affiliation();

        $affiliation->user_id = $request->user_id;
        $affiliation->group_id = $request->group_id;
        $affiliation->applystart_at = $request->applystart_at;
        $affiliation->applyend_at = $request->applyend_at;
        $affiliation->entry_at = $request->entry_at;
        $affiliation->admin = $request->admin;
        $affiliation->employee_no = $request->employee_no;

        $affiliation->save();
        //一覧にリダイレクト
        return redirect()->to('/affiliations');
    }
}
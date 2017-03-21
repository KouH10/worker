<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Group;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class GroupsController extends Controller
{
    
    //入力ルール
    public $rules = [
        'name'=>'required|unique:groups',
        'workingstart_st'=>'required|date_format:"G:i"',
        'workingend_st'=>'required|date_format:"G:i"',
        'reststart_st'=>'required|date_format:"G:i"',
        'restend_st'=>'required|date_format:"G:i"',
        'nightstart_st'=>'required|date_format:"G:i"',
        'nightend_st'=>'required|date_format:"G:i"',
        'legalholiday'=>'required',
        'notlegalholiday'=>'required',
        'weekstart'=>'required',
        'monthstart'=>'required'
    ];
    //入力エラーメッセージ
    public $messages = [
        'name.required'=>'名前は必須です。',
        'name.unique'=>'このグループ名は既に登録されています。',
        'name.required'=>'グループ名は必須です。',
        'workingstart_st.required'=>'所定労働時間の開始は必須です。',
        'workingstart_st.date_format'=>'所定労働時間の開始は時分(HH:MI)形式の入力です。',
        'workingend_st.required'=>'所定労働時間の終了は必須です。',
        'workingend_st.date_format'=>'所定労働時間の終了は時分(HH:MI)形式の入力です。',
        'reststart_st.required'=>'休憩時間の開始は必須です。',
        'reststart_st.date_format'=>'休憩時間の開始は時分(HH:MI)形式の入力です。',
        'restend_st.required'=>'休憩時間の終了は必須です。',
        'restend_st.date_format'=>'休憩時間の終了は時分(HH:MI)形式の入力です。',
        'nightstart_st.required'=>'深夜時間の開始は必須です。',
        'nightstart_st.date_format'=>'深夜時間の開始は時分(HH:MI)形式の入力です。',
        'nightend_st.required'=>'深夜時間の終了は必須です。',
        'nightend_st.date_format'=>'深夜時間の終了は時分(HH:MI)形式の入力です。',
        'legalholiday.required'=>'法定休日は必須です。',
        'notlegalholiday.required'=>'法定外休日は必須です。',
        'weekstart.required'=>'週開始は必須です。',
        'monthstart.required'=>'月開始日は必須です。', 
    ];

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
        $query = Group::query();
        // ページネーション
        $groups = $query->orderBy('id','asc')->paginate(10);
        return view('groups/index',compact('groups'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups/create');
    }
    
    /**
     * グループ新規登録
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

        $group = new Group();

        $group->name = $request->name;
        $group->workingstart_st = $request->workingstart_st;
        $group->workingend_st = $request->workingend_st;
        $group->reststart_st = $request->reststart_st;
        $group->restend_st = $request->restend_st;
        $group->nightstart_st = $request->nightstart_st;
        $group->nightend_st = $request->nightend_st;
        $group->legalholiday =  $request->legalholiday;
        $group->notlegalholiday = $request->notlegalholiday;
        $group->weekstart = $request->weekstart;
        $group->monthstart = $request->monthstart;

        $group->save();
        //一覧にリダイレクト
        return redirect()->to('/groups');
        
    }

    /**
     * グループ編集表示
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //レコードを検索
        $group = Group::find($id);
        //検索結果をビューに渡す
        return view('groups/edit',compact('group'));
    }
    
    /**
     * グループ編集表示
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 入力チェックを更新用に変更
        $rulesUpdate = array_replace($this->rules ,
            array('name'=>'required|unique:groups,name,'.$id));

        //入力チェック対象
        $inputs = $request->all();
        $validation = \Validator::make($inputs,$rulesUpdate,$this->messages);
        //エラー次の処理
        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }

        //レコードを検索
        $group = Group::find($id);
        //値を代入
        $group->name = $request->name;
        $group->workingstart_st = $request->workingstart_st;
        $group->workingend_st = $request->workingend_st;
        $group->reststart_st = $request->reststart_st;
        $group->restend_st = $request->restend_st;
        $group->nightstart_st = $request->nightstart_st;
        $group->nightend_st = $request->nightend_st;
        $group->legalholiday =  $request->legalholiday;
        $group->notlegalholiday = $request->notlegalholiday;
        $group->weekstart = $request->weekstart;
        $group->monthstart = $request->monthstart;
        //保存
        $group->save();
        //一覧にリダイレクト
        return redirect()->to('/groups');
 
    }
}

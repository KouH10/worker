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
        return view('WorkVacations/index',compact('workVacations'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

	/**
     * グループ休日新規登録
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }
}

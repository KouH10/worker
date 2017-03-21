<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
     /**
     * createメソッド実行時に、入力を禁止するカラムの指定
     *
     * @var array
     */
     protected $guarded = array('id');

    //belongsTo設定
    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    //belongsTo設定
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

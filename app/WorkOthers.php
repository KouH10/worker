<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkOthers extends Model
{
  /**
  * createメソッド実行時に、入力を禁止するカラムの指定
  *
  * @var array
  */
  protected $guarded = array('id');
}

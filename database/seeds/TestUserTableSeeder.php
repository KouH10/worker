<?php

use Illuminate\Database\Seeder;
use App\Group;
use App\User;
use App\Affiliation;

class TestUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //faker
        $faker = Faker\Factory::create('ja_JP');

        $user = new User();
        $user->name = "原口 康太郎";
        $user->email = "haraguchi.kotaro@mtgrp.co.jp";
        $user->password = Hash::make('okinawa');
        $user->save();

        $group = new Group();
        $group->name = "(株)エム・テー・デー";
        $group->workingstart_st = "09:00";
        $group->workingend_st = "18:00";
        $group->reststart_st = "12:00";
        $group->restend_st = "13:00";
        $group->nightstart_st = "22:00";
        $group->nightend_st = "5:00";
        $group->legalholiday = "0";
        $group->notlegalholiday = "6";
        $group->weekstart = "0";
        $group->monthstart = "1";
        $group->save();

        $affiliation = new Affiliation();
        $affiliation->user_id = $user->id;
        $affiliation->group_id = $group->id;
        $affiliation->applystart_at  = "2017-04-01";
        $affiliation->applyend_at  = "2099-12-31";
        $affiliation->entry_at  = "2010-04-01";
        $affiliation->admin = "1";
        $affiliation->employee_no = "33";
        $affiliation->save();

    }
}

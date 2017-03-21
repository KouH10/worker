<?php

use Illuminate\Database\Seeder;
use App\Group;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //delete all data.
        Group::truncate();

        //faker
        $faker = Faker\Factory::create('ja_JP');

        //insert
        for($i=0;$i<25;$i++)
        {
            $group = new Group();
            $group->name = $faker->name();
            $group->workingstart_st = $faker->time($format = 'H:i:s', $max = 'now');
            $group->workingend_st = $faker->time($format = 'H:i:s', $max = 'now');
            $group->reststart_st = "12:00";
            $group->restend_st = "13:00";
            $group->nightstart_st = "22:00";
            $group->nightend_st = "5:00";
            $group->legalholiday = $faker->numberBetween(1, 7);
            $group->notlegalholiday = $faker->numberBetween(1, 7);
            $group->weekstart = $faker->numberBetween(1, 7);
            $group->monthstart = $faker->numberBetween(1, 31);
            $group->save();

        }
    }
}
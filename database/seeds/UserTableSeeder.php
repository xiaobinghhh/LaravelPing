<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //写数据
        $faker = \Faker\Factory::create('zh_CN');
        //生成一条数据
        $data[] = [
            'no' => $faker->regexify('\d{8}'),
            'password' => bcrypt('password'),
            'gender' => rand(1, 2),
            'age'=>$faker->numberBetween(8, 80),
            'mobile' => $faker->phoneNumber,
            'email' => $faker->email,
            'avatar' => '/statics/avatar.jpg',
            'type' => rand(1, 2),
        ];
        //写入数据表
        \Illuminate\Support\Facades\DB::table('users')->insert($data);
    }
}

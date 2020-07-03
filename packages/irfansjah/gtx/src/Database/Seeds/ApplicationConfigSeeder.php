<?php

use Illuminate\Database\Seeder;
use Irfansjah\Cube\Models\SystemConfig;
use Irfansjah\Gtx\Facade\Gtx as Gtx;

class ApplicationConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config_items = [
            ['id'=>1, 'name'=> 'SITE_TITLE', 'value'=>'--YOUR SITE TITLE--', 'group'=> 'SITE_INFO'],
            ['id'=>2, 'name'=> 'SITE_NAME', 'value'=>'--YOUR SITE NAME--', 'group'=> 'SITE_INFO'],
            ['id'=>3, 'name'=> 'SITE_CONTACT_EMAIL', 'value'=>'your_contact@mail.test', 'group'=> 'SITE_INFO'],
            ['id'=>4, 'name'=> 'SITE_CONTACT_PHONE', 'value'=>'-- YOUR CONTACT PHONE --', 'group'=> 'SITE_INFO'],
            ['id'=>5, 'name'=> 'SITE_COPYRIGHT_TEXT', 'value'=>'2020 &copy; your copyright text', 'group'=> 'SITE_INFO'],

            ['id'=>6, 'name'=> 'COMPANY_NAME', 'value'=>'-- YOUR COMPANY NAME --', 'group'=> 'COMPANY_INFO'],
            ['id'=>7, 'name'=> 'COMPANY_ADDRESS_1', 'value'=>'-- YOUR COMPANY ADDRESS #1 --', 'group'=> 'COMPANY_INFO'],
            ['id'=>8, 'name'=> 'COMPANY_ADDRESS_2', 'value'=>'-- YOUR COMPANY ADDRESS #2 --', 'group'=> 'COMPANY_INFO'],
            ['id'=>9, 'name'=> 'COMPANY_PHONE', 'value'=>'-- YOUR COMPANY PHONE --', 'group'=> 'COMPANY_INFO'],
            ['id'=>10, 'name'=> 'COMPANY_FAX', 'value'=>'-- YOUR COMPANY FAX --', 'group'=> 'COMPANY_INFO'],
            ['id'=>11, 'name'=> 'COMPANY_EMAIL', 'value'=>'-- YOUR COMPANY EMAIL --', 'group'=> 'COMPANY_INFO'],
            ['id'=>12, 'name'=> 'COMPANY_LONGLAT', 'value'=>'longitude;latitude', 'group'=> 'COMPANY_INFO'],

            ['id'=>13, 'name'=> 'ADMIN_PATH', 'value'=>'admin', 'group'=> 'SITE_SETTING'],
            ['id'=>14, 'name'=> 'ADMIN_THEME', 'value'=>'green', 'group'=> 'SITE_SETTING'],

        ];
        foreach($config_items as $items)
        {
            Gtx::CreateSetting($items['group'],$items['name'], $items['value']);
        }



    }
}

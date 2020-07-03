<?php

use Illuminate\Database\Seeder;
use Irfansjah\Gtx\Models\Province;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data Propinsi berdasarkan Departemen Dalam Negeri dan Departemen Pendidikan
        $province_list = [
            ['province_name'=>'DI Aceh',             'province_code'=>'11','province_alt_code'=>'060000'],
            ['province_name'=>'Sumatera Utara',      'province_code'=>'12','province_alt_code'=>'070000'],
            ['province_name'=>'Sumatera Barat',      'province_code'=>'13','province_alt_code'=>'080000'],
            ['province_name'=>'Riau',                'province_code'=>'14','province_alt_code'=>'090000'],
            ['province_name'=>'Jambi',               'province_code'=>'15','province_alt_code'=>'100000'],
            ['province_name'=>'Sumatera Selatan',    'province_code'=>'16','province_alt_code'=>'110000'],
            ['province_name'=>'Bengkulu',            'province_code'=>'17','province_alt_code'=>'260000'],
            ['province_name'=>'Lampung',             'province_code'=>'18','province_alt_code'=>'120000'],
            ['province_name'=>'Kep. Bangka Belitung','province_code'=>'19','province_alt_code'=>'290000'],
            ['province_name'=>'Kep. Riau',           'province_code'=>'21','province_alt_code'=>'310000'],
            ['province_name'=>'DKI Jakarta',         'province_code'=>'31','province_alt_code'=>'010000'],
            ['province_name'=>'Jawa Barat',          'province_code'=>'32','province_alt_code'=>'020000'],
            ['province_name'=>'Jawa Tengah',         'province_code'=>'33','province_alt_code'=>'030000'],
            ['province_name'=>'DI Yogyakarta',       'province_code'=>'34','province_alt_code'=>'040000'],
            ['province_name'=>'Jawa Timur',          'province_code'=>'35','province_alt_code'=>'050000'],
            ['province_name'=>'Banten',              'province_code'=>'36','province_alt_code'=>'280000'],
            ['province_name'=>'Bali',                'province_code'=>'51','province_alt_code'=>'220000'],
            ['province_name'=>'Nusa Tenggara Barat', 'province_code'=>'52','province_alt_code'=>'230000'],
            ['province_name'=>'Nusa Tenggara Timur', 'province_code'=>'53','province_alt_code'=>'240000'],
            ['province_name'=>'Kalimantan Barat',    'province_code'=>'61','province_alt_code'=>'130000'],
            ['province_name'=>'Kalimantan Tengah',   'province_code'=>'62','province_alt_code'=>'140000'],
            ['province_name'=>'Kalimantan Selatan',  'province_code'=>'63','province_alt_code'=>'150000'],
            ['province_name'=>'Kalimantan Timur',    'province_code'=>'64','province_alt_code'=>'160000'],
            ['province_name'=>'Kalimantan Utara',    'province_code'=>'65','province_alt_code'=>'340000'],
            ['province_name'=>'Sulawesi Utara',      'province_code'=>'71','province_alt_code'=>'170000'],
            ['province_name'=>'Sulawesi Tengah',     'province_code'=>'72','province_alt_code'=>'180000'],
            ['province_name'=>'Sulawesi Selatan',    'province_code'=>'73','province_alt_code'=>'190000'],
            ['province_name'=>'Sulawesi Tenggara',   'province_code'=>'74','province_alt_code'=>'200000'],
            ['province_name'=>'Gorontalo',           'province_code'=>'75','province_alt_code'=>'300000'],
            ['province_name'=>'Sulawesi Barat',      'province_code'=>'76','province_alt_code'=>'330000'],
            ['province_name'=>'Maluku',              'province_code'=>'81','province_alt_code'=>'210000'],
            ['province_name'=>'Maluku Utara',        'province_code'=>'82','province_alt_code'=>'270000'],
            ['province_name'=>'Papua Barat',         'province_code'=>'92','province_alt_code'=>'320000'],
            ['province_name'=>'Papua',               'province_code'=>'91','province_alt_code'=>'250000'],
            ['province_name'=>'Luar Negeri',         'province_code'=>'',  'province_alt_code'=>'350000']
        ];

        foreach($province_list as $province) {
            //\Irfansjah\Gtx\Models\Province::create($province);
            Province::create($province);
        }
    }
}

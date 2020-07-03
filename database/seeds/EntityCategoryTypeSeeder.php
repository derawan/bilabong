<?php

use Illuminate\Database\Seeder;
use Irfansjah\Gtx\Models\EntityCategoryType;
use Irfansjah\Gtx\Facade\Gtx;

class EntityCategoryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = [
            ["type"=>"ACCOUNT", "multilevel"=>false, "system"=>true],
            ["type"=>"CHANNEL", "multilevel"=>false, "system"=>true],

            ["type"=>"BLOG", "multilevel"=>true, "system"=>true],
            ["type"=>"NEWS", "multilevel"=>true, "system"=>true],
            ["type"=>"PRODUCT", "multilevel"=>true, "system"=>false],
            ["type"=>"EVENT","multilevel"=>false, "system"=>true],
            ["type"=>"MEDIA","multilevel"=>false, "system"=>true],

            ["type"=>"JENIS_KELAMIN","multilevel"=>false, "system"=>true],
            ["type"=>"STRATA_PENDIDIKAN","multilevel"=>false, "system"=>true],
            ["type"=>"JENJANG_PENDIDIKAN","multilevel"=>false, "system"=>true],
            ["type"=>"STATUS_PERKAWINAN","multilevel"=>false, "system"=>false],
            ["type"=>"KEWARGANEGARAAN","multilevel"=>false, "system"=>false],
            ["type"=>"AGAMA","multilevel"=>false, "system"=>false],
            ["type"=>"JENIS_PEKERJAAN","multilevel"=>false, "system"=>false],
            ["type"=>"AKREDITASI","multilevel"=>false, "system"=>false],

            ["type"=>"STATUS_ANAK","multilevel"=>false, "system"=>false],
            ["type"=>"STATUS_KEPEGAWAIAN","multilevel"=>false, "system"=>false],
            ["type"=>"GOLONGAN","multilevel"=>false, "system"=>false],
            ["type"=>"JABATAN_FUNGSIONAL","multilevel"=>false, "system"=>false],
            ["type"=>"JABATAN_TUGAS_PTK","multilevel"=>false, "system"=>false],
            ["type"=>"JENIS_PRASARANA","multilevel"=>false, "system"=>false],

            ["type"=>"JENIS_PENDAFTARAN","multilevel"=>false, "system"=>false],
            ["type"=>"JALUR_PPDB","multilevel"=>false, "system"=>false],

            ["type"=>"JENIS_SEMESTER","multilevel"=>false, "system"=>false],

            ["type"=>"JENIS_DOKUMEN","multilevel"=>false, "system"=>false],

            ["type"=>"KELAS","multilevel"=>false, "system"=>false],
        ];
        foreach($type as $items)
        {
            EntityCategoryType::create($items);

        }



    }
}

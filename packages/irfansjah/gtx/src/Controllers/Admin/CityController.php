<?php

namespace Irfansjah\Gtx\Controllers\Admin;

use Illuminate\Http\Request;
use Irfansjah\Gtx\Controller\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Irfansjah\Gtx\Models\Country;
use Irfansjah\Gtx\Models\Province;

class CityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Form Column Definition
    public function create_column_def(array $param = [])  {
        return  [
            [
                "data" => "id", "type"=>"autonumber", "label" => __("id"),"placeholder"=> "Masukkan ID",
            ],
            [
                "data" => "city_name", "type"=>"string", "label" => __("nama kota/kabupaten"),"placeholder"=> "Masukkan Nama Kota / Kabupaten",
                "rules" => ["string", "required", "unique:cities". (array_key_exists("id",$param)?",city_name,".$param["id"]:"")]
            ],
            [
                "data" => "city_code", "type"=>"string", "label" => __("kode kota"),"placeholder"=> "Masukkan Kode Kota/Kabupaten",
                "rules" => ["string", "required", "unique:cities". (array_key_exists("id",$param)?",city_code,".$param["id"]:""), "max:8"]
            ],
            [
                "data" => "city_alt_code", "type"=>"string", "label" => __("kode kabupaten/kota #2"), "placeholder"=> "Masukkan Kode Kota/Kabupaten #2",
                "rules" => ["string", "required", "unique:cities". (array_key_exists("id",$param)?",city_alt_code,".$param["id"]:""), "max:6"]
            ],
            [
                "data" => "province_id", "type"=>"select", "label" => __("propinsi"), "placeholder"=> "Pilih Propinsi",
                "source" => (array_key_exists("id",$param)?collect(\Irfansjah\Gtx\Models\City::where('id',$param["id"])->select("province_id")->get()->pluck('province_id'))->all():[]),
                'options'=>[
                    "type" =>"combo",
                    "items" => collect(\Irfansjah\Gtx\Models\Province::all())->map(function($value){
                        return ["id"=>$value->id, "text"=>$value->province_name];
                    })
                ]
                //"rules" => ["string", "required", "unique:cities". (array_key_exists("id",$param)?",city_alt_code,".$param["id"]:""), "max:10"]
            ]
        ];
    }


    public function build_edit_form($data) {
        $columns_def = $this->create_column_def($data);

        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\City","city"],
            "title" => __("perubahan data kabupaten/kota"),
            "icon" => "fa fa-clone",
            "description" => __("perubahan data kabupaten/kota "),
            "name_column" => "city_name",
            "column_def" => $columns_def,
            "route" => [
                "update" => 'admin.cities.update',
                "browse"=> 'admin.cities'
            ],
            "views" => [
                "edit" =>"gtx::Admin.partials.edit"
            ],
            "message" => [
                "entity not found" => __("data  kabupaten/kota tidak ditemukan"),
                "entity added" => __(" kabupaten/kota di tambahkan"),
                "entity modified" => __(" kabupaten/kota berhasil di perbaharui"),
                "entity deleted" => __(" kabupaten/kota berhasil di hapus dari database"),
            ]
        ];

        return $form_def;
    }

    public function build_create_form() {
        $columns_def = $this->create_column_def();
        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\City","city"],
            "title" => __("pendaftaran kabupaten/kota baru"),
            "icon" => "fa fa-clone",
            "description" => __("pendaftaran data kabupaten/kota baru"),
            "table_id" => "table_kota",
            "column_def" => $columns_def,
            "route" => [
                "store" => 'admin.cities.store',
                "browse"=> 'admin.cities'
            ],
            "views" => [
                "create" =>"gtx::Admin.partials.create"
            ],
            "message" => [
                "entity added" => __("data kabupaten/kota berhasil didaftarkan")
            ]
        ];

        return $form_def;
    }


    public function index(Request $request)
    {
        $columns_def = [
            [
                "data" => "id", "type"=>"string", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("id"),
                'fields' => 'cities.id'
            ],
            [
                "data" => "city_code", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("kode kota/kabupaten"),
                'fields' => 'cities.city_code'
            ],
            [
                "data" => "city_name", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("nama kota/kabupaten"),
                'fields' => 'cities.city_name'
            ],
            [
                "data" => "city_alt_code", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("kode kota/kabupaten #2"),
                'fields' => 'cities.city_alt_code'
            ],
            [
                "data" => "province_name", "type"=>"select", "searchable" => true, "orderable" => false, "visible" => true, "label" => __("nama propinsi"),
                'fields' => 'provinces.province_name',
                'options'=>[
                    "type" =>"simple",
                    "items" => collect(\Irfansjah\Gtx\Models\Province::all())->map(function($value){
                        return ["id"=>$value->id, "text"=>$value->province_name];
                    })
                ]
            ],

        ];

        $cmd = [];
        $route = [];
        if (Auth::user()->can('add_city'))
        {
            $cmd[] = "add";
            $route["create"] = route("admin.cities.create");
        }

        if (Auth::user()->can('edit_city'))
        {
            $cmd[] = "edit";
            $route["edit"] = route("admin.cities.edit");
        }
        if (Auth::user()->can('delete_city'))
        {
            $cmd[] = "delete";
            $route["delete"] = route("admin.cities.destroy");
        }
        if (Auth::user()->can('browse_city'))
        {
            $cmd[] = "fetch";
            $route["fetch"] = route("admin.cities");
        }

        $table_def = [
            "data_source" => "cities",
            "title" => __("Kota / Kabupaten"),
            "icon" => "fa fa-clone",
            "description" => __("tampilkan data kota/kabupaten yang terdaftar dalam database"),
            "table_id" => "table_kota",
            "column_def" => $columns_def,
            "template" => [
                "confirm_template" => "
                '<div class=\"box\" style=\"border:1px solid #f39c12\">' +
                '<div class=\"box-footer no-padding\">' +
                    '<ul class=\"nav nav-stacked\">' +
                        '<li style=\"text-align:left\"><a href=\"#\">Name : :city_name</a></li>' +
                    '</ul>' +
                '</div>' +
                '</div>'
                ",
            ],
            "route" => $route,
            "crud_label" => [
                "delete" =>[
                    "success" => __("data kota/kabupaten berhasil dihapus"),
                    "failed"  => __("data kota/kabupaten tidak berhasil dihapus")
                ]
            ],
            "commands" => $cmd,
            "additional_query" =>  function($query) {
                $query->join('provinces','provinces.id','cities.province_id');
                return $query;
            }
        ];

        if ($request->wantsJson())
        {
            $cols = [];
            foreach($table_def["column_def"] as $column) {
                $cols[] = $column["fields"];
            }

            $qry = DB::table($table_def["data_source"])->select($cols);
            $users = $table_def["additional_query"]($qry);

            $datatable = Datatables::of($users);
            $datatable->addColumn('xid', function ($user) {
                $xid = Crypt::encrypt($user->id);
                return $xid;
            });

            $search =$request->get('columns')[4]['search']['value'];
            if ($search != "") {
                $users->where('provinces.province_name', 'like', "%$search%");
                $datatable->filterColumn('province_name', function($query,$key){
                    $query->whereRaw("provinces.province_name like ?",["%".$key."%"]);
                });
            }
            //->editColumn('id', '#{{$id}}');

                return $datatable

                ->make(true);
        }
        else {
            return view('gtx::Admin.partials.manage_browse', compact("table_def"));
        }


    }


}

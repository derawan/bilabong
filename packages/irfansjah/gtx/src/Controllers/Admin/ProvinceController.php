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

class ProvinceController extends Controller
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
                "data" => "province_code", "type"=>"string", "label" => __("kode propinsi"),"placeholder"=> "Masukkan Kode Propinsi",
                "rules" => ["string", "required", "unique:provinces". (array_key_exists("id",$param)?",province_code,".$param["id"]:""), "max:2", "min:2"]
            ],
            [
                "data" => "province_name", "type"=>"string", "label" => __("nama propinsi"),"placeholder"=> "Masukkan Nama Propinsi",
                "rules" => ["string", "required", "unique:provinces". (array_key_exists("id",$param)?",province_name,".$param["id"]:"")]
            ],
            [
                "data" => "province_alt_code", "type"=>"string", "label" => __("kode propinsi #2"), "placeholder"=> "Masukkan Kode Propinsi #2",
                "rules" => ["string", "required", "unique:provinces". (array_key_exists("id",$param)?",province_alt_code,".$param["id"]:""), "max:6"]
            ]
        ];
    }


    public function build_edit_form($data) {
        $columns_def = $this->create_column_def($data);

        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\Province","province"],
            "title" => __("perubahan data propinsi"),
            "icon" => "fa fa-clone",
            "description" => __("perubahan data propinsi "),
            "name_column" => "province_name",
            "column_def" => $columns_def,
            "route" => [
                "update" => 'admin.provinces.update',
                "browse"=> 'admin.provinces'
            ],
            "views" => [
                "edit" =>"gtx::Admin.partials.edit"
            ],
            "message" => [
                "entity not found" => __("data propinsi tidak ditemukan"),
                "entity added" => __("propinsi berhasil di tambahkan"),
                "entity modified" => __("propinsi berhasil di perbaharui"),
                "entity deleted" => __("propinsi berhasil di hapus dari database"),
            ]
        ];

        return $form_def;
    }

    public function build_create_form() {
        $columns_def = $this->create_column_def();
        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\Province","province"],
            "title" => __("pendaftaran propinsi baru"),
            "icon" => "fa fa-clone",
            "description" => __("pendaftaran data propinsi baru"),
            "table_id" => "table_propinsi",
            "column_def" => $columns_def,
            "route" => [
                "store" => 'admin.provinces.store',
                "browse"=> 'admin.provinces'
            ],
            "views" => [
                "create" =>"gtx::Admin.partials.create"
            ],
            "message" => [
                "entity added" => __("data propinsi berhasil didaftarkan")
            ]
        ];

        return $form_def;
    }


    public function index(Request $request)
    {
        $columns_def = [
            [
                "data" => "id", "type"=>"string", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("id"),
                'fields' => 'provinces.id'
            ],
            [
                "data" => "province_code", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("kode propinsi"),
                'fields' => 'provinces.province_code'
            ],
            [
                "data" => "province_name", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("nama propinsi"),
                'fields' => 'provinces.province_name'
            ],
            [
                "data" => "province_alt_code", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("kode propinsi #2"),
                'fields' => 'provinces.province_alt_code'
            ],
            [
                "data" => "city_count", "type"=>"string", "searchable" => false, "orderable" => false, "visible" => true, "label" => __("jumlah kota terdaftar"),
                'fields' => DB::raw('(SELECT count(*) FROM cities WHERE cities.province_id = provinces.id) as city_count'),
                "renderer" => " data + ' kota/kabupaten'"
            ],

        ];

        $cmd = [];
        $route = [];
        if (Auth::user()->can('add_province'))
        {
            $cmd[] = "add";
            $route["create"] = route("admin.provinces.create");
        }

        if (Auth::user()->can('edit_province'))
        {
            $cmd[] = "edit";
            $route["edit"] = route("admin.provinces.edit");
        }
        if (Auth::user()->can('delete_province'))
        {
            $cmd[] = "delete";
            $route["delete"] = route("admin.provinces.destroy");
        }
        if (Auth::user()->can('browse_province'))
        {
            $cmd[] = "fetch";
            $route["fetch"] = route("admin.provinces");
        }

        $table_def = [
            "data_source" => "provinces",
            "title" => __("Propinsi"),
            "icon" => "fa fa-clone",
            "description" => __("tampilkan daftar propinsi yang terdaftar dalam database"),
            "table_id" => "table_propinsi",
            "column_def" => $columns_def,
            "template" => [
                "confirm_template" => "
                '<div class=\"box\" style=\"border:1px solid #f39c12\">' +
                '<div class=\"box-footer no-padding\">' +
                    '<ul class=\"nav nav-stacked\">' +
                        '<li style=\"text-align:left\"><a href=\"#\">Name : :province_name</a></li>' +
                    '</ul>' +
                '</div>' +
                '</div>'
                ",
            ],
            "route" => $route,
            "crud_label" => [
                "delete" =>[
                    "success" => __("data propinsi berhasil dihapus"),
                    "failed"  => __("data propinsi tidak berhasil dihapus")
                ]
            ],
            "commands" => $cmd,
            "additional_query" =>  function($query) {
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
            //->editColumn('id', '#{{$id}}');

                return $datatable

                ->make(true);
        }
        else {
            return view('gtx::Admin.partials.manage_browse', compact("table_def"));
        }


    }


}

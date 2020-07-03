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

class CountryController extends Controller
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

    // Form Column Definition - Create / Edit
    // Modified as you wish
    public function create_column_def(array $param = [])  {
        $def = [
            [
                "data" => "id", "type"=>"autonumber", "label" => __("id"),"placeholder"=> "Masukkan ID",
            ],
            [
                "data" => "country_code", "type"=>"string", "label" => __("kode negara"),"placeholder"=> "Masukkan Kode Negara",
                "rules" => ["string", "required", "unique:countries" . (array_key_exists("id",$param)?",country_code,".$param["id"]:""), "max:2", "min:2"]
            ],
            [
                "data" => "country_name", "type"=>"string", "label" => __("nama negara"),"placeholder"=> "Masukkan Nama Negara",
                "rules" => ["string", "required", "unique:countries" . (array_key_exists("id",$param)?",country_name,".$param["id"]:"")]
            ]
        ];
        return  $def;
    }

    // Build Edit Form Config
    // Modified as you wish
    public function build_edit_form($data) {
        $columns_def = $this->create_column_def($data);

        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\Country","country"],
            "title" => __("perubahan data negara"),
            "icon" => "fa fa-clone",
            "description" => __("perubahan data negara "),
            "name_column" => "country_name",
            "column_def" => $columns_def,
            "route" => [
                "update" => 'admin.countries.update',
                "browse"=> 'admin.countries'
            ],
            "views" => [
                "edit" =>"gtx::Admin.partials.edit"
            ],
            "message" => [
                "entity not found" => __("data negara tidak ditemukan"),
                "entity added" => __("negara berhasil di tambahkan"),
                "entity modified" => __("negara berhasil di perbaharui"),
                "entity deleted" => __("negara berhasil di hapus dari database"),
            ]
        ];
        return $form_def;
    }

    // Build Create Form Config
    // Modified as you wish
    public function build_create_form() {
        $columns_def = $this->create_column_def();
        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\Country","country"],
            "title" => __("pendaftaran negara baru"),
            "icon" => "fa fa-clone",
            "description" => __("pendaftaran data negara baru"),
            "table_id" => "table_negara",
            "column_def" => $columns_def,
            "route" => [
                "store" => 'admin.countries.store',
                "browse"=> 'admin.countries'
            ],
            "views" => [
                "create" =>"gtx::Admin.partials.create"
            ],
            "message" => [
                "entity added" => __("data negara berhasil didaftarkan")
            ]
        ];

        return $form_def;
    }



    public function index(Request $request)
    {
        $columns_def = [
            [
                "data" => "id", "type"=>"string", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("id"),
                'fields' => 'countries.id'
            ],
            [
                "data" => "country_code", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("country_code"),
                'fields' => 'countries.country_code'
            ],
            [
                "data" => "country_name", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("country name"),
                'fields' => 'countries.country_name'
            ]
        ];

        $cmd = [];
        $route = [];
        if (Auth::user()->can('add_country'))
        {
            $cmd[] = "add";
            $route["create"] = route("admin.countries.create");
        }

        if (Auth::user()->can('edit_country'))
        {
            $cmd[] = "edit";
            $route["edit"] = route("admin.countries.edit");
        }
        if (Auth::user()->can('delete_country'))
        {
            $cmd[] = "delete";
            $route["delete"] = route("admin.countries.destroy");
        }
        if (Auth::user()->can('browse_country'))
        {
            $cmd[] = "fetch";
            $route["fetch"] = route("admin.countries");
        }

        $table_def = [
            "data_source" => "countries",
            "title" => __("countries"),
            "icon" => "fa fa-clone",
            "description" => __("show registered country list"),
            "table_id" => "table_country",
            "column_def" => $columns_def,
            "template" => [
                "confirm_template" => "
                '<div class=\"box\" style=\"border:1px solid #f39c12\">' +
                '<div class=\"box-footer no-padding\">' +
                    '<ul class=\"nav nav-stacked\">' +
                        '<li style=\"text-align:left\"><a href=\"#\">Name : :country_name</a></li>' +
                    '</ul>' +
                '</div>' +
                '</div>'
                ",
            ],
            "route" => $route,
            "crud_label" => [
                "delete" =>[
                    "success" => __("Data Negara Berhasil Dihapus"),
                    "failed"  => __("Data Negara Tidak Berhasil Dihapus")
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

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
use Irfansjah\Gtx\Models\EntityCategoryType;

class EntityTypeController extends Controller
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
                "data" => "type", "type"=>"string", "label" => __("tipe"),"placeholder"=> "Masukkan Tipe",
                "rules" => ["string", "required", "unique:entity_category_types" . (array_key_exists("id",$param)?",type,".$param["id"]:"")]
            ],
            [
                "data" => "multilevel", "type"=>"select", "label" => __("multilevel category"),"placeholder"=> "Multi Level Category",
                "rules" => ["required"],
                "source" => (array_key_exists("id",$param)?collect(\Irfansjah\Gtx\Models\EntityCategoryType::where('id',$param["id"])->select("multilevel")->get()->pluck('multilevel'))->all():[]),
                'options'=>[
                    "type" => "simple",
                    "items" => [
                        ["id" => 1, "text"=> __("Ya"), "default"=>true],
                        ["id" => 0, "text"=> __("Tidak"), "default"=>false],
                    ]
                ]

            ],
            [
                "data" => "system", "type"=>"select", "label" => __("system"),"placeholder"=> "System",
                "rules" => ["required"],
                "source" => (array_key_exists("id",$param)?collect(\Irfansjah\Gtx\Models\EntityCategoryType::where('id',$param["id"])->select("system")->get()->pluck('system'))->all():[]),
                'options'=>[
                    "type" => "simple",
                    "items" => [
                        ["id" => 1, "text"=> __("Ya"), "default"=>false],
                        ["id" => 0, "text"=> __("Tidak"), "default"=>true],
                    ]
                ]
            ]
        ];
        return  $def;
    }

    // Build Edit Form Config
    // Modified as you wish
    public function build_edit_form($data) {
        $columns_def = $this->create_column_def($data);

        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\EntityCategoryType","entitycategorytype"],
            "title" => __("perubahan tipe kategori"),
            "icon" => "fa fa-clone",
            "description" => __("perubahan data tipe katergori "),
            "name_column" => "country_name",
            "column_def" => $columns_def,
            "route" => [
                "update" => 'admin.types.update',
                "browse"=> 'admin.types'
            ],
            "views" => [
                "edit" =>"gtx::Admin.partials.edit"
            ],
            "message" => [
                "entity not found" => __("data tipe kategori tidak ditemukan"),
                "entity added" => __("tipe kategori berhasil di tambahkan"),
                "entity modified" => __("tipe kategori berhasil di perbaharui"),
                "entity deleted" => __("tipe kategori berhasil di hapus dari database"),
            ]
        ];
        return $form_def;
    }

    // Build Create Form Config
    // Modified as you wish
    public function build_create_form() {
        $columns_def = $this->create_column_def();
        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\EntityCategoryType","entitycategorytype"],
            "title" => __("pendaftaran tipe kategori baru"),
            "icon" => "fa fa-clone",
            "description" => __("pendaftaran data tipe kategori baru"),
            "table_id" => "table_tipe kategori",
            "column_def" => $columns_def,
            "route" => [
                "store" => 'admin.types.store',
                "browse"=> 'admin.types'
            ],
            "views" => [
                "create" =>"gtx::Admin.partials.create"
            ],
            "message" => [
                "entity added" => __("data tipe kategori berhasil didaftarkan")
            ]
        ];

        return $form_def;
    }



    public function index(Request $request)
    {
        $columns_def = [
            [
                "data" => "id", "type"=>"string", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("id"),
                'fields' => 'entity_category_types.id'
            ],
            [
                "data" => "type", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("tipe"),
                'fields' => 'entity_category_types.type'
            ],
            [
                "data" => "multilevel", "type"=>"boolean", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("multilevel"),
                'fields' => 'entity_category_types.multilevel',
                'renderer'=>' (data==1)?"<i class=\'fa  fa-check-square-o\'></i>":"<i class=\'fa  fa-square-o\'></i>"',
                'options'=>[
                    "items" => [
                        ["id" => 1, "text"=> __("Ya"), "default"=>true],
                        ["id" => 0, "text"=> __("Tidak"), "default"=>false],
                    ]
                ]


            ],
            [
                "data" => "system", "type"=>"boolean", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("system"),
                'fields' => 'entity_category_types.system',
                'renderer'=>' (data==1)?"<i class=\'fa  fa-check-square-o\'></i>":"<i class=\'fa  fa-square-o\'></i>"',
                'options'=>[
                    "items" => [
                        ["id" => 1, "text"=> __("Ya"), "default"=>true],
                        ["id" => 0, "text"=> __("Tidak"), "default"=>false],
                    ]
                ]

            ]
        ];

        $cmd = [];
        $route = [];
        if (Auth::user()->can('add_type'))
        {
            $cmd[] = "add";
            $route["create"] = route("admin.types.create");
        }

        if (Auth::user()->can('edit_type'))
        {
            $cmd[] = "edit";
            $route["edit"] = route("admin.types.edit");
        }
        if (Auth::user()->can('delete_type'))
        {
            $cmd[] = "delete";
            $route["delete"] = route("admin.types.destroy");
        }
        if (Auth::user()->can('browse_type'))
        {
            $cmd[] = "fetch";
            $route["fetch"] = route("admin.types");
        }

        $table_def = [
            "data_source" => "entity_category_types",
            "title" => __("Tipe Kategori"),
            "icon" => "fa fa-clone",
            "description" => __("daftar tipe kategori"),
            "table_id" => "table_tipe_kategori",
            "column_def" => $columns_def,
            "template" => [
                "confirm_template" => "
                '<div class=\"box\" style=\"border:1px solid #f39c12\">' +
                '<div class=\"box-footer no-padding\">' +
                    '<ul class=\"nav nav-stacked\">' +
                        '<li style=\"text-align:left\"><a href=\"#\">Name : :type</a></li>' +
                    '</ul>' +
                '</div>' +
                '</div>'
                ",
            ],
            "route" => $route,
            "crud_label" => [
                "delete" =>[
                    "success" => __("Data Tipe Kategori Berhasil Dihapus"),
                    "failed"  => __("Data Tipe Kategori Tidak Berhasil Dihapus")
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

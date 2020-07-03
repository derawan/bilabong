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


class PermissionManagementController extends Controller
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
        //$v = ;
        $def = [
            [
                "data" => "id", "type"=>"autonumber", "label" => __("id"),"placeholder"=> "Masukkan ID",
            ],
            [
                "data" => "name", "type"=>"string", "label" => __("permission"),"placeholder"=> "Permission",
                "rules" => ["string", "required", "unique:permissions" . (array_key_exists("id",$param)?",name,".$param["id"]:"")]
            ],
            [
                "data" => "guard_name", "type"=>"select", "label" => __("system"),"placeholder"=> "System",
                "rules" => ["required"],
                "source" => (array_key_exists("id",$param)?collect(\Spatie\Permission\Models\Permission::where('id',$param["id"])->select("guard_name")->get()->pluck('guard_name'))->all():[]),
                'options'=>[
                    "type"=> "simple",
                    "items" => [
                        ["id" => "web", "text"=> __("web"), "default"=>true],
                        ["id" => "admin", "text"=> __("admin"), "default"=>true],
                        ["id" => "api", "text"=> __("api"), "default"=>false],
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
            "data_source" => ["Model","Spatie\Permission\Models\Permission","permission"],
            "title" => __("perubahan data permission"),
            "icon" => "fa fa-clone",
            "description" => __("perubahan data permission "),
            "name_column" => "name",
            "column_def" => $columns_def,
            "route" => [
                "update" => 'admin.permissions.update',
                "browse"=> 'admin.permissions'
            ],
            "views" => [
                "edit" =>"gtx::Admin.partials.edit"
            ],
            "message" => [
                "entity not found" => __("data permissions tidak ditemukan"),
                "entity added" => __("permissions di tambahkan"),
                "entity modified" => __("permissions di perbaharui"),
                "entity deleted" => __("permissions berhasil di hapus dari database"),
            ]
        ];
        return $form_def;
    }

    // Build Create Form Config
    // Modified as you wish
    public function build_create_form() {
        $columns_def = $this->create_column_def();
        $form_def = [
            "data_source" => ["Model","Spatie\Permission\Models\Permission","permission"],
            "title" => __("pendaftaran permissions baru"),
            "icon" => "fa fa-clone",
            "description" => __("pendaftaran data permissions baru"),
            "table_id" => "table_permissions",
            "column_def" => $columns_def,
            "route" => [
                "store" => 'admin.permissions.store',
                "browse"=> 'admin.permissions'
            ],
            "views" => [
                "create" =>"gtx::Admin.partials.create"
            ],
            "message" => [
                "entity added" => __("data permissions berhasil didaftarkan")
            ]
        ];

        return $form_def;
    }

    public function assign_new_data($form_def, $model, $data)
    {
        // assign your model here
        foreach($form_def["column_def"] as $column) {
            if ($column["type"]!= "autonumber") {
                $col= $column["data"];
                $model->$col = $data[$col];
            }
        }
        $model->guard_name = $data['guard_name_xx'];
        $model->save();
    }




    public function index(Request $request)
    {
        $columns_def = [
            [
                "data" => "id", "type"=>"string", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("id"),
                'fields' => 'permissions.id'
            ],
            [
                "data" => "name", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("permission"),
                'fields' => 'permissions.name'
            ],
            [
                "data" => "guard_name", "type"=>"select", "searchable" => true, "orderable" => false, "visible" => true, "label" => __("guard"),
                'fields' => 'permissions.guard_name',
                "source" => [],
                'options'=>[
                    "type" =>"simple",
                    "items" => [
                        ["id"=>"web", "text"=>"web"],
                        ["id"=>"admin", "text"=>"admin"],
                        ["id"=>"api", "text"=>"api"],
                    ]
                ]
            ],
        ];

        $cmd = [];
        $route = [];
        if (Auth::user()->can('add_permission'))
        {
            $cmd[] = "add";
            $route["create"] = route("admin.permissions.create");
        }

        if (Auth::user()->can('edit_permission'))
        {
            $cmd[] = "edit";
            $route["edit"] = route("admin.permissions.edit");
        }
        if (Auth::user()->can('delete_permission'))
        {
            $cmd[] = "delete";
            $route["delete"] = route("admin.permissions.destroy");
        }
        //if (Auth::user()->can('browse_permission'))
        {
            $cmd[] = "fetch";
            $route["fetch"] = route("admin.permissions");
        }

        $table_def = [
            "data_source" => "permissions",
            "title" => __("permission"),
            "icon" => "fa fa-clone",
            "description" => __("show registered permission list"),
            "table_id" => "table_permission",
            "column_def" => $columns_def,
            "route" => $route,
            "crud_label" => [
                "delete" =>[
                    "success" => __("Data Permission Berhasil Dihapus"),
                    "failed"  => __("Data Permission Tidak Berhasil Dihapus")
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

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


class RoleManagementController extends Controller
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
                "data" => "name", "type"=>"string", "label" => __("role"),"placeholder"=> "Role",
                "rules" => ["string", "required", "unique:roles" . (array_key_exists("id",$param)?",name,".$param["id"]:"")]
            ],
            [
                "data" => "guard_name", "type"=>"select", "label" => __("guard"),"placeholder"=> "guard",
                "rules" => ["required"],
                "source"=> (array_key_exists("id",$param)?
                    collect(\Spatie\Permission\Models\Role::where('id',$param["id"])->select("guard_name")->get()->pluck('guard_name'))->all():[]),
                'options'=>[
                    "type" => "simple",
                    "items" => [
                        ["id" => "web", "text"=> __("web"), "default"=>true],
                        ["id" => "admin", "text"=> __("admin"), "default"=>false],
                        ["id" => "api", "text"=> __("api"), "default"=>false],
                    ]
                ]
            ],
            [
                "data" => "permission", "type"=>"multiple", "label" => __("permission"),"placeholder"=> "permission",
                // "rules" => [],
                "source" => (array_key_exists("id",$param)?collect(\Spatie\Permission\Models\Role::find($param["id"])->permissions->pluck('name'))->all():[]),
                'options'=>[
                    "type" => "combo",
                    "filterby"=>"guard_name",
                    "items" => collect(\Spatie\Permission\Models\Permission::all())->map(function($value){
                        return ["id"=>$value->name, "text"=>"[".$value->guard_name."] " . $value->name, "filter" =>$value->guard_name];
                    })
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
            "data_source" => ["Model","Spatie\Permission\Models\Role","role"],
            "title" => __("perubahan data role"),
            "icon" => "fa fa-clone",
            "description" => __("perubahan data role "),
            "name_column" => "name",
            "column_def" => $columns_def,
            "route" => [
                "update" => 'admin.roles.update',
                "browse"=> 'admin.roles'
            ],
            "views" => [
                "edit" =>"gtx::Admin.partials.edit"
            ],
            "message" => [
                "entity not found" => __("data roles tidak ditemukan"),
                "entity added" => __("roles di tambahkan"),
                "entity modified" => __("roles di perbaharui"),
                "entity deleted" => __("roles berhasil di hapus dari database"),
            ]
        ];
        return $form_def;
    }

    // Build Create Form Config
    // Modified as you wish
    public function build_create_form() {
        $columns_def = $this->create_column_def();
        $form_def = [
            "data_source" => ["Model","Spatie\Permission\Models\Role","roles"],
            "title" => __("pendaftaran roles baru"),
            "icon" => "fa fa-clone",
            "description" => __("pendaftaran data roles baru"),
            "table_id" => "table_roles",
            "column_def" => $columns_def,
            "route" => [
                "store" => 'admin.roles.store',
                "browse"=> 'admin.roles'
            ],
            "views" => [
                "create" =>"gtx::Admin.partials.create"
            ],
            "message" => [
                "entity added" => __("data roles berhasil didaftarkan")
            ]
        ];

        return $form_def;
    }



    public function index(Request $request)
    {
        $columns_def = [
            [
                "data" => "id", "type"=>"string", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("id"),
                'fields' => 'roles.id'
            ],
            [
                "data" => "name", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("role"),
                'fields' => 'roles.name'
            ],
            [
                "data" => "guard_name", "type"=>"select", "searchable" => true, "orderable" => false, "visible" => true, "label" => __("guard"),
                'fields' => 'roles.guard_name',
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
        if (Auth::user()->can('add_role'))
        {
            $cmd[] = "add";
            $route["create"] = route("admin.roles.create");
        }

        if (Auth::user()->can('edit_role'))
        {
            $cmd[] = "edit";
            $route["edit"] = route("admin.roles.edit");
        }
        if (Auth::user()->can('delete_roles'))
        {
            $cmd[] = "delete";
            $route["delete"] = route("admin.roles.destroy");
        }
        //if (Auth::user()->can('browse_role'))
        {
            $cmd[] = "fetch";
            $route["fetch"] = route("admin.roles");
        }


        $table_def = [
            "data_source" => "roles",
            "title" => __("role"),
            "icon" => "fa fa-clone",
            "description" => __("show registered role list"),
            "table_id" => "table_role",
            "column_def" => $columns_def,
            // "template" => [
            //     "confirm_template" => "
            //     '<div class=\"box\" style=\"border:1px solid #f39c12\">' +
            //     '<div class=\"box-footer no-padding\">' +
            //         '<ul class=\"nav nav-stacked\">' +
            //             '<li style=\"text-align:left\"><a href=\"#\">Name : :country_name</a></li>' +
            //         '</ul>' +
            //     '</div>' +
            //     '</div>'
            //     ",
            // ],
            "route" => $route,
            "crud_label" => [
                "delete" =>[
                    "success" => __("Data Role Berhasil Dihapus"),
                    "failed"  => __("Data Role Tidak Berhasil Dihapus")
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


    public function assign_new_data($form_def, $model, $data)
    {
        $model->name = $data['name'];
        $model->guard_name = $data['guard_name'];
        $model->save();
        $model->syncPermissions([$data['permission']]);

    }

    public function assign_update_data($form_def, $model, $data)
    {
        $model->name = $data['name'];
        $model->guard_name = $data['guard_name'];
        $model->save();
        $model->syncPermissions([$data['permission']]);
    }




}

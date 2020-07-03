<?php

namespace Irfansjah\Gtx\Controllers\Admin;

use Illuminate\Http\Request;
use Irfansjah\Gtx\Controller\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
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
                "data" => "name", "type"=>"string", "label" => __("nama kategori"),"placeholder"=> "Masukkan Nama Kategori",
                "rules" => ["string", "required"]
            ],
            [
                "data" => "type_id", "type"=>"select", "label" => __("tipe"), "placeholder"=> "Pilih Tipe",
                "source" => (array_key_exists("id",$param)?collect(\Irfansjah\Gtx\Models\Category::where('id',$param["id"])->select("type_id")->get()->pluck('type_id'))->all():[]),
                'options'=>[
                    "type" =>"combo",
                    "items" => collect(\Irfansjah\Gtx\Models\EntityCategoryType::where("multilevel",false)->get())->map(function($value){
                        return ["id"=>$value->id, "text"=>$value->type];
                    })
                ]
                //"rules" => ["string", "required", "unique:cities". (array_key_exists("id",$param)?",city_alt_code,".$param["id"]:""), "max:10"]
            ]
        ];
    }


    public function build_edit_form($data) {
        $columns_def = $this->create_column_def($data);

        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\Category","category"],
            "title" => __("perubahan data kategori"),
            "icon" => "fa fa-clone",
            "description" => __("perubahan data kategori "),
            "name_column" => "name",
            "column_def" => $columns_def,
            "route" => [
                "update" => 'admin.categories.update',
                "browse"=> 'admin.categories'
            ],
            "views" => [
                "edit" =>"gtx::Admin.partials.edit"
            ],
            "message" => [
                "entity not found" => __("data  kategori tidak ditemukan"),
                "entity added" => __(" kategori di tambahkan"),
                "entity modified" => __(" kategori berhasil di perbaharui"),
                "entity deleted" => __(" kategori berhasil di hapus dari database"),
            ]
        ];

        return $form_def;
    }

    public function build_create_form() {
        $columns_def = $this->create_column_def();
        $form_def = [
            "data_source" => ["Model","Irfansjah\Gtx\Models\Category","category"],
            "title" => __("pendaftaran kategori baru"),
            "icon" => "fa fa-clone",
            "description" => __("pendaftaran data kategori baru"),
            "table_id" => "table_categori",
            "column_def" => $columns_def,
            "route" => [
                "store" => 'admin.categories.store',
                "browse"=> 'admin.categories'
            ],
            "views" => [
                "create" =>"gtx::Admin.partials.create"
            ],
            "message" => [
                "entity added" => __("data kategori berhasil didaftarkan")
            ]
        ];

        return $form_def;
    }

    public function create_show_column_def(array $param = [])
    {
        $columns_def = [
            [
                "data" => "id", "type"=>"string", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("id"),
                'fields' => 'categories.id'
            ],
            [
                "data" => "name", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("nama kategori"),
                'fields' => 'categories.name'
            ],
            [
                "data" => "type", "type"=>"select", "searchable" => true, "orderable" => false, "visible" => true, "label" => __("tipe kategori"),
                'fields' => 'entity_category_types.type',
                'options'=>[
                    "type" =>"simple",
                    "items" => collect(\Irfansjah\Gtx\Models\EntityCategoryType::where("multilevel",false)->get())->map(function($value){
                        return ["id"=>$value->id, "text"=>$value->type];
                    })
                ]
            ]
        ];
        return $columns_def;
    }

    public function build_browse_form(Request $request, $data)
    {
        $columns_def = $this->create_show_column_def();

        $cmd = [];
        $route = [];
        if (Auth::user()->can('master_data'))
        {
            $cmd[] = "add";
            $route["create"] = route("admin.categories.create");
        }

        if (Auth::user()->can('master_data'))
        {
            $cmd[] = "edit";
            $route["edit"] = route("admin.categories.edit");
        }
        if (Auth::user()->can('master_data'))
        {
            $cmd[] = "delete";
            $route["delete"] = route("admin.categories.destroy");
        }
        if (Auth::user()->can('master_data'))
        {
            $cmd[] = "fetch";
            $route["fetch"] = route("admin.categories");
        }

        $table_def = [
            "data_source" => "categories",
            "title" => __("Kategori"),
            "icon" => "fa fa-clone",
            "description" => __("tampilkan data kategori yang terdaftar dalam database"),
            "table_id" => "table_category",
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
                    "success" => __("data kategori berhasil dihapus"),
                    "failed"  => __("data kategori tidak berhasil dihapus")
                ]
            ],
            "commands" => $cmd,
            "additional_query" =>  function($query) {
                $query->where('entity_category_types.multilevel',false);
                $query->join('entity_category_types','entity_category_types.id','categories.type_id');
                return $query;
            }
        ];

        return $table_def;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $table_def = $this->build_browse_form($request,$data);

        if ($request->wantsJson())
        {
            $cols = [];
            foreach($table_def["column_def"] as $column) {
                $cols[] = $column["fields"];
            }

            $qry = DB::table($table_def["data_source"])->select($cols);
            $result_set = $qry;
            if (array_key_exists("additional_query", $table_def))
                $result_set = $table_def["additional_query"]($result_set);

            $datatable = Datatables::of($result_set);
            $datatable->addColumn('xid', function ($result) {
                $xid = Crypt::encrypt($result->id);
                return $xid;
            });
            if (array_key_exists("additional_column", $table_def)) {
                foreach($table_def["additional_column"] as $key => $item) {
                    $datatable->addColumn($key,$item);
                }
            }

            $search =$request->get('columns')[2]['search']['value'];
            if ($search != "") {
                $result_set->where('entity_category_types.type', 'like', "%$search%");
                $datatable->filterColumn('type', function($query,$key){
                    $query->whereRaw("entity_category_types.type like ?",["%".$key."%"]);
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

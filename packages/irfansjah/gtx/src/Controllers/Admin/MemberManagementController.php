<?php

namespace Irfansjah\Gtx\Controllers\Admin;

use Irfansjah\Gtx\Events\UserModified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;
use Irfansjah\Gtx\Events\UserDeleted;
use Irfansjah\Gtx\Events\UserPasswordChanged;

class MemberManagementController extends Controller
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

    public function index(Request $request)
    {
        $columns_def = [
            [
                "data" => "id", "type"=>"string", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("id"),
                "fields" => "members.id"
            ],
            [
                "data" => "name", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("name"),
                "fields" => "members.name"
            ],
            [
                "data" => "email", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("email"),
                "fields" => "members.email"
            ],
            [
                "data" => "phone", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("phone"),
                "fields" => "members.phone"
            ],
            [
                "data" => "model_role", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("model_role"),
                "fields" => DB::raw("GROUP_CONCAT(roles.name) model_role")
            ],
            [
                "data" => "updated_at", "type"=>"datetime", "searchable" => false, "orderable" => true, "visible" => true, "label" => __("last update"),
                "fields" => "members.updated_at"
            ],
            [
                "data" => "user_status", "type"=>"string", "searchable" => true, "orderable" => true, "visible" => true, "label" => __("status"),
                "fields" => "members.user_status"
            ],
            [
                "data" => "media_id", "type"=>"image", "searchable" => false, "orderable" => false, "visible" => false, "label" => __("avatar"),
                "renderer" => "'<img src=\"". url("/"). "/' + data+'\" style=\"width:100px\">'",
                "fields" => DB::raw("case when (SELECT concat(media.id,'/',media.file_name) FROM media WHERE media.model_type='App\\\Models\\\Member' and media.model_id=members.id limit 1) is null then 'images/noimage.png' else (SELECT concat('storage/avatar/',media.id,'/conversions/',media.name,'-thumb', replace(media.file_name, media.name,'')) FROM media WHERE media.model_type='App\\\Models\\\Member' and media.model_id=members.id limit 1) end as media_id")
            ]
        ];

        $cmd = [];
        $route = [];
        //if (Auth::user()->can('add_user'))
        {
            $cmd[] = "add";
            $route["create"] = route("admin.members.create");
        }
        //if (Auth::user()->can('edit_user'))
        {
            $cmd[] = "edit";
            $route["edit"] = route("admin.members.edit");
            $cmd[] = "show";
            $route["show"] = route("admin.members.show");
        }
        //if (Auth::user()->can('delete_user'))
        {
            $cmd[] = "delete";
            $route["delete"] = route("admin.members.destroy");
        }
        //if (Auth::user()->can('browse_user'))
        {
            $cmd[] = "fetch";
            $route["fetch"] = route("admin.members");
        }


        $table_def = [
            "data_source" => "members",
            "title" => __("members"),
            "icon" => "fa fa-members",
            "description" => __("show registered member list"),
            "table_id" => "table_member",
            "column_def" => $columns_def,
            "template" => [
                "confirm_template" => "
                '<div class=\"box box-widget widget-user-2\" style=\"border:1px solid #f39c12\">' +
                                  '<div class=\"widget-user-header bg-yellow\">' +
                                    '<div class=\"widget-user-image\">' +
                                        '<img class=\"img-circle\" src=\"".url("/")."/:media_id\" alt=\"User Avatar\">' +
                                        '</div>' +
                                        '<h3 class=\"widget-user-username\"  style=\"text-align:left\">:name</h3>' +
                                        '<h5 class=\"widget-user-desc\"  style=\"text-align:left\">:model_role</h5>' +
                                        '</div>'+
                                        '<div class=\"box-footer no-padding\">' +
                                            '<ul class=\"nav nav-stacked\">' +
                                                '<li style=\"text-align:left\"><a href=\"#\">Email : :email</a></li>' +
                                                '<li style=\"text-align:left\"><a href=\"#\">Phone : :phone</a></li>' +
                                                '<li style=\"text-align:left\"><a href=\"#\">Status: :status</a></li>' +
                                            '</ul>' +
                                        '</div>' +
                                    '</div>'
                ",
            ],
            "route" => $route,
            // [
            //     "create"=> route("admin.members.create"),
            //     "edit"=> route("admin.members.edit"),
            //     "show"=> route("admin.members.show"),
            //     "fetch" => route("admin.members"),
            //     "delete"=> route("admin.members.destroy"),
            //     "approve"=> route("admin.members.edit"),
            //     "reject"=> route("admin.members.edit"),
            // ],
            "crud_label" => [
                "delete" =>[
                    "success" => __("Data Member Berhasil Dihapus"),
                    "failed"  => __("Data Member Tidak Berhasil Dihapus")
                ]
            ],
            "commands" =>  $cmd,
            // [
            //     "add",
            //     "edit",
            //     "delete",
            //     // "approve",
            //     // "reject",
            //     // "upload",
            //     // "download",
            //     "show"
            // ],
            "additional_query" =>  function($query) {
                $query->leftJoin('model_has_roles', function($join){
                    $join->on('model_has_roles.model_id','=','members.id');
                    $join->on('model_has_roles.model_type','=',DB::raw("'App\\\Models\\\Member'"));
                })->leftJoin('roles', function($join){
                    $join->on('roles.id','=','model_has_roles.role_id');
                })->groupBy(
                    'members.id',
                    'members.name',
                    'members.email',
                    'members.phone',
                    'members.user_status','members.updated_at'
                    );
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
            $members = $table_def["additional_query"]($qry);

            $datatable = Datatables::of($members);
            $datatable->addColumn('xid', function ($user) {
                $xid = Crypt::encrypt($user->id);
                return $xid;
            });
            //->editColumn('id', '#{{$id}}');
            $searchModelRole =$request->get('columns')[4]['search']['value'];
            if ($searchModelRole != "") {
                $members->where('roles.name', 'like', "%$searchModelRole%");
                $datatable->filterColumn('model_role', function($query,$key){
                    $query->whereRaw("roles.name like ?",["%".$key."%"]);
                });
            }
                return $datatable

                ->make(true);
        }
        else {
            return view('gtx::Admin.partials.manage_browse', compact("table_def"));
        }

    }

    public function destroy(Request $request) {
        $data = $request->all('q');
        $user_id = Crypt::decrypt($data['q']);
        if ($user_id == Auth::user()->id) {
            return response()->json(["success"=>false, "message"=>"Anda tidak dapat menghapus data anda sendiri"]);
        }
        $user = User::find($user_id);
        if ($user)
        {
            DB::beginTransaction();
            try {
                $recent = $user->getMedia('avatars')->last();
                if ($recent) {
                    File::deleteDirectory(pathinfo($recent->getPath())["dirname"]);
                    $recent->delete();
                }
                $user->delete();
                DB::commit();

                event(new UserDeleted($user));
                return response()->json(["success"=>true]);
            }
            catch (Exception $exception) {
                DB::rollBack();
                return response()->json(["success"=>false]);
            }
        }
        else {
            return response()->json(["success"=>false, "message"=>__("Data member tidak ditemukan")]);
        }
    }

    public function show(Request $request) {
        $data = $request->all('id');
        try {
            $user_id = Crypt::decrypt($data['id']);
            $user = Member::find($user_id);

            if ($user) {
                $xid = Crypt::encrypt($user->id);
                $route = ["uppass" => route("admin.members.updatepassword"),
                "upav" => route("admin.members.updateavatar"),
                "upback" => route("admin.members")];
                return view("gtx::Admin.auth.profile", compact('user','xid','route'));
            } else {
                return redirect()->route('admin.members')->with('status', "error")->with('message',__("Data member tidak ditemukan"));
            }
        } catch (Exception $exception) {
            return redirect()->route('admin.members')->with('status', "error")->with('message',$exception->getMessage());
        }
    }

    public function updatepassword(Request $request)
    {
        $data = $request->all();
        $user_id = Crypt::decrypt($data['uid']);
        $user = Member::find($user_id);
        if($user)
        {
            $previous = $user->password;
            $validator = Validator::make($request->all(),[
                "password" => "required|confirmed"
            ]);
            $validator->after(function($validator) use($request){
            });
            if ($validator->fails())
            {
                //throw new ValidationException($validator);
                return redirect(route("admin.members.show")."?id=". $data["uid"])
                            ->withErrors($validator)
                            ->withInput();
            }
            $data = $request->all();
            $user->password = Hash::make($data["password"]);
            $user->save();
            // trigger UserPasswordChanged
            event(new UserPasswordChanged($user, $previous));
            return redirect(route("admin.members.show")."?id=". $data["uid"])
                            ->with('status','success')
                            ->with('message',__("Password Modified"));
        }
        else {
            return redirect()->route('admin.members')->with('status', "error")->with('message',__("Data user tidak ditemukan"));
        }
    }

    public function setavatar(Request $request)
    {
        $data = $request->all();
        $user_id = Crypt::decrypt($data['uid']);
        $user = Member::find($user_id);
        if($user)
        {

            $data = $request->all();
            if (isset($data['avatar'])) {
                $user->setAvatarFromRequest('avatar'); //addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }
            return redirect(route("admin.members.show")."?id=". $data["uid"])
                            ->with('status','success')
                            ->with('message',__("Avatar Changed"));
        }
        else {
            return redirect()->route('admin.members')->with('status', "error")->with('message',__("Data user tidak ditemukan"));
        }
    }

    public function edit(Request $request) {
        $data = $request->all('id');
        try {
            $user_id = Crypt::decrypt($data['id']);
            $user = Member::find($user_id);

            if ($user) {
                $token = Crypt::encrypt($user->id);
                $roles = Role::all();
                return view("gtx::Admin.pages.member.edit", compact('user','token','roles'));
            } else {
                return redirect()->route('admin.members')
                ->with('status', "error")->with('status', "error")->with('message',__("Data user tidak ditemukan"));
            }
        } catch (Exception $exception) {
            return redirect()->route('admin.members')->with('status', "error")->with('message',$exception->getMessage());
        }
    }

    public function update(Request $request) {
        $data = $request->all();
        $user_id = Crypt::decrypt($data['token']);
        $validator = Validator::make($data,[
            "name" => "required",
            "phone"=>"required|unique:members,phone,".$user_id,
            "email" => "required|unique:members,email,".$user_id
        ]);
        $validator->after(function($validator) use($request){

        });
        if ($validator->fails())
        {
            throw new ValidationException($validator);
        }

        try {

            $user = Member::find($user_id);
            $previous = $user->replicate();
            if ($user) {
                DB::beginTransaction();
                try {
                    $user->name = $data['name'];
                    $user->phone = $data['phone'];
                    $user->email = $data['email'];
                    $user->save();
                    if (isset($data['avatar'])) {
                        $user->setAvatarFromRequest('avatar');
                    }
                    // $user->syncRoles([$data['roles']]);

                    DB::commit();
                    // trigger UserModified
                    event(new UserModified($user, $previous));
                    return redirect()->route('admin.members')
                        ->with('status', 'success')
                        ->with('message',__("User berhasil diperbaharui"));
                } catch (Exception $ext) {
                    DB::rollBack();
                    throw $ext;
                }
            } else {
                return redirect()->route('admin.members')->with('status', "error")->with('message', __("Data user tidak ditemukan"));
            }
        } catch (Exception $exception) {
            return redirect()->route('admin.members')->with('status', "error")->with('message',  $exception->getMessage());
        }

    }

    public function create() {
        $roles = Role::all();
        return view("gtx::Admin.pages.member.create", compact("roles"));
    }

    public function store(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data,[
            "name" => "required",
            "phone"=>"required|unique:members,phone",
            "email" => "required|unique:members,email"
        ]);
        $validator->after(function($validator) use($request){

        });
        if ($validator->fails())
        {
            throw new ValidationException($validator);
        }

        try {

                DB::beginTransaction();
                try {
                    $user = new Member();
                    $user->name = $data['name'];
                    $user->phone = $data['phone'];
                    $user->email = $data['email'];
                    $user->user_status = 'ACTIVE';
                    $user->password = Hash::make($data['email']);
                    $user->save();
                    if (isset($data['avatar'])) {
                        $user->setAvatarFromRequest('avatar');
                    }
                    // $user->syncRoles([$data['roles']]);
                    DB::commit();
                    // trigger registered event
                    event(new Registered($user));
                    return redirect()->route('admin.members')
                    ->with('status', 'success')
                    ->with('message',__("Member berhasil ditambahkan"));
                } catch (Exception $ext) {
                    DB::rollBack();
                    throw $ext;
                }
        } catch (Exception $exception) {
            return redirect()->route('admin.members')->with('status', "error")->with('message', $exception->getMessage());
        }

    }
}

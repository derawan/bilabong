<?php

namespace Irfansjah\Gtx\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class Controller extends BaseController
{

    // Form Column Definition - Create / Edit
    // Modified as you wish
    public function create_column_def(array $param = [])  {
        $def = [];
        return  $def;
    }

    // Build Edit Form Config
    // Modified as you wish
    public function build_edit_form($data) {
       $form_def = [];
       return $form_def;
    }

    // Build Create Form Config
    // Modified as you wish
    public function build_create_form() {
        $form_def = [];
        return $form_def;
    }

    // Build Delete Form Config
    // Modified as you wish
    // public function build_delete_form($data) {
    //     $form_def = [];
    //     return $form_def;
    //  }

    public function validate_on_create($form_def,$data, Request $request)
    {
        $validation_rules = [];
        foreach($form_def["column_def"] as $column)
        {
            if (array_key_exists("rules",$column))
                $validation_rules[$column["data"]] = $column["rules"];
        }

        $validator = Validator::make($data,$validation_rules);
        $validator->after(function($validator) use($request){
            // add anything here for customizing your validation rule
        });
        if ($validator->fails())
        {
            throw new ValidationException($validator);
        }

        return $validator;
    }

    public function validate_on_edit($form_def,$data, Request $request)
    {
        $validation_rules = [];
        foreach($form_def["column_def"] as $column)
        {
            if (array_key_exists("rules",$column))
                $validation_rules[$column["data"]] = $column["rules"];
        }

        $validator = Validator::make($data,$validation_rules);
        $validator->after(function($validator) use($request){
            // add anything here for customizing your validation rule
        });
        if ($validator->fails())
        {
            throw new ValidationException($validator);
        }

        return $validator;
    }

    // public function validate_on_delete($form_def,$data, Request $request)
    // {
    //     if ($form_def == null) return;
    //     if (count($form_def)==0) return;
    //     $validation_rules = [];
    //     $validator = null;
    //     foreach($form_def["column_def"] as $column)
    //     {
    //         if (array_key_exists("rules",$column))
    //             $validation_rules[$column["data"]] = $column["rules"];
    //     }
    //     if (count($validation_rules)>0)
    //     {
    //         $validator = Validator::make($data,$validation_rules);
    //         $validator->after(function($validator) use($request){
    //             // add anything here for customizing your validation rule
    //         });
    //         if ($validator->fails())
    //         {
    //             throw new ValidationException($validator);
    //         }
    //     }


    //     return $validator;
    // }

    public function assign_new_data($form_def, $model, $data)
    {
        // assign your model here
        foreach($form_def["column_def"] as $column) {
            if ($column["type"]!= "autonumber") {
                $col= $column["data"];
                $model->$col = $data[$col];
            }
        }
        $model->save();
    }

    public function assign_update_data($form_def, $model, $data)
    {
        // assign your model here
        foreach($form_def["column_def"] as $column) {
            if ($column["type"]!= "autonumber") {
                $col= $column["data"];
                $model->$col = $data[$col];
            }
        }
        $model->save();
    }

    public function assign_delete_data($form_def, $model, $model_id)
    {
        // assign your model here
        $model->delete();
    }

    public function show_create($form_def)
    {
        return view($form_def["views"]["create"], compact("form_def"));
    }

    public function show_edit($model_id, $form_def)
    {
        try {
            if (!array_key_exists("data_source",$form_def)) { throw new Exception(__("missing form definition : 'data_source'")); }
            $model = null;
            if($form_def["data_source"][0] == "Model") {

               $model = $form_def["data_source"][1]::find($model_id);
            }
            if ($model) {
                $token = Crypt::encrypt($model_id);
                return view($form_def["views"]["edit"], compact('form_def','model','token'));
            } else {
                return redirect()->route($form_def["route"]["browse"])->with('status', "error")->with('message', $form_def["message"]["entity not found"]);
            }
        } catch (Exception $exception) {
            return redirect()->route($form_def["route"]["browse"])->with('status', "error")->with('message', $exception->getMessage());
        }
    }

    public function store_new_data($form_def, $data)
    {
        try {
            if (!array_key_exists("data_source",$form_def)) { throw new Exception(__("missing form definition : 'data_source'")); }
            DB::beginTransaction();
            try
            {
                if($form_def["data_source"][0] == "Model") {
                    $class = $form_def["data_source"][1];
                    $model = new $class();
                    $this->assign_new_data($form_def, $model, $data);
                }
                DB::commit();
                return redirect()->route($form_def["route"]["browse"])
                ->with('status', 'success')
                ->with('message',__($form_def["message"]["entity added"]));
            }
            catch(Exception $error)
            {
                DB::rollBack();
                throw $error;
            }
        }
        catch (Exception $exception) {
            return redirect()->route($form_def["route"]["browse"])->with('status', "error")->with('message', $exception->getMessage());
        }
    }

    public function modify_existing_data($form_def, $data, $model_id)
    {
        try {
            if (!array_key_exists("data_source",$form_def)) { throw new Exception(__("missing form definition : 'data_source'")); }
            if($form_def["data_source"][0] == "Model") {
                $class = $form_def["data_source"][1];
                $model = $model = $class::find($model_id);
                if ($model)
                {
                    DB::beginTransaction();
                    try
                    {
                        $this->assign_update_data($form_def, $model, $data);
                        DB::commit();
                        return redirect()->route($form_def["route"]["browse"])
                        ->with('status', 'success')
                        ->with('message',__($form_def["message"]["entity modified"]));

                    }
                    catch(Exception $error)
                    {
                        DB::rollBack();
                        throw $error;
                    }
                }
                else {
                    return redirect()->route($form_def["route"]["browse"])
                    ->with('status', "error")
                    ->with('message',__($form_def["message"]["entity not found"]));
                }

            }
        }
        catch (Exception $exception) {
            return redirect()->route($form_def["route"]["browse"])->with('status', "error")->with('message', $exception->getMessage());
        }
    }

    public function delete_existing_data($form_def, $model_id)
    {
        try {
            if (!array_key_exists("data_source",$form_def)) { throw new Exception(__("missing form definition : 'data_source'")); }
            if($form_def["data_source"][0] == "Model") {
                $class = $form_def["data_source"][1];
                $model = $class::find($model_id);
                if ($model)
                {
                    DB::beginTransaction();
                    try
                    {
                        $this->assign_delete_data($form_def,$model, $model_id);

                        DB::commit();
                        return response()->json(["success"=>true]);

                    }
                    catch(Exception $error)
                    {

                        DB::rollBack();
                        return response()->json(["success"=>false]);
                    }
                }
                else {
                    return redirect()->route($form_def["route"]["browse"])
                    ->with('status', "error")
                    ->with('message',__($form_def["message"]["entity not found"]));
                }

            }
        }
        catch (Exception $exception) {

            return redirect()->route($form_def["route"]["browse"])->with('status', "error")->with('message', $exception->getMessage());
        }
    }


    public function destroy(Request $request) {
        $data = $request->all('q');
        $model_id = Crypt::decrypt($data['q']);
        $form_def = $this->build_edit_form(["id"=>$model_id]);
        //$this->validate_on_delete($form_def, $data, $request);
        return $this->delete_existing_data($form_def, $model_id);
    }

    public function edit(Request $request) {
        $data = $request->all();
        $model_id = Crypt::decrypt($data["id"]);
        $form_def = $this->build_edit_form(["id"=>$model_id]);
        return $this->show_edit($model_id,$form_def);
    }

    public function update(Request $request) {
        $data = $request->all();
        $model_id = Crypt::decrypt($data["token"]);
        $form_def = $this->build_edit_form(["id"=>$model_id]);
        $this->validate_on_edit($form_def, $data, $request);
        return $this->modify_existing_data($form_def, $data, $model_id);
    }

    public function create() {
        $form_def = $this->build_create_form();
        return $this->show_create($form_def);
    }

    public function store(Request $request) {
        $form_def = $this->build_create_form();
        $data = $request->all();
        $this->validate_on_create($form_def, $data, $request);
        return $this->store_new_data($form_def, $data);
    }

}

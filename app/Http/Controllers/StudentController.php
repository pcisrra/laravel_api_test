<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\StudentResponse;
use App\Exceptions\Handler;

class StudentController extends Controller
{
    public function create(Request $request){
        $result = false;
        $studentResp = new StudentResponse();
        $student = new Student();
        try{
            $student->first_name = $request->input('first_name');
            $student->last_name = $request->input('last_name');
            $student->email = $request->input('email');
            $student->password = $request->input('password');

            $result = $student->save();
            if($result){
                $studentResp->code = "200";
                $studentResp->message = "OK. Se guardaron los datos exitosamente en la base.";
                $studentResp->operation = date('Ymd');
            }
            else{
                $studentResp->code = "500";
                $studentResp->message = "Error. Ocurrió un error en la creación del registro.";
                $studentResp->operation = date('Ymd');
            }
            return response()->json($studentResp);
        }
        catch(Exception $exs){
            $studentResp->code = "400";
            $studentResp->message = "Error. Ocurrió un error en la creación del registro: ".$exs->getMesage();
            $studentResp->operation = date('Ymd');
            return response()->json($studentResp);
        }
    }

    public function showData(){
        $student = Student::all();
        return response()->json($student);
    }

    public function showRecord($id){
        $student = Student::find($id);
        $studentResp = new StudentResponse();
        if(is_null($student)){
            $studentResp->code = "404";
            $studentResp->message = "No se encontro el registro en la base de datos";
            $studentResp->operation = date('Ymd');
            return response()->json($studentResp);
        }
        else{
            return response()->json($student);
        }
    }

    public function updateData($id, Request $request){
        $student = Student::find($id);
        $studentResp = new StudentResponse();

        if(is_null($student)){
            $studentResp->code = "400";
            $studentResp->message = "Error. el id no se encuentra registrado en la base de datos.";
            $studentResp->operation = date('Ymd');
        }
        else{
            $student->first_name = $request->input('first_name');
            $student->last_name = $request->input('last_name');
            $student->email = $request->input('email');
            $student->password = $request->input('password');
            if(is_null($student->first_name) || is_null($student->last_name) || is_null($student->password)){
                $studentResp->code = "200";
                $studentResp->message = "Error. No se pudo actualizar el registro, uno de los campos no fue debidamente llenado.";
                $studentResp->operation = date('Ymd');
            }
            else{
                $studentResp->code = "500";
                $studentResp->message = "OK. El registro se actualizo exitosamente en la base de datos.";
                $studentResp->operation = date('Ymd');

                $student->save();
            }            
        }
        return response()->json($studentResp);
    }
}
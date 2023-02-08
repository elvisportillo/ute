<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformesGestionController extends Controller
{

    public function buscarFuncionario($buscar){
        $funcionario = DB::table("usuarios")
        ->where(DB::raw("concat(RTRIM(nombres), ' ', RTRIM(apellidos))"), "LIKE", '%' .$buscar. '%')
        ->get();
        
        return $funcionario;
    }

    public function buscarJudicatura($buscar){
        $judicatura = DB::table("judicatura")
        ->where("nombre_judicatura", "LIKE" ,'%'.$buscar.'%')
        ->get();

        return $judicatura;
    }


    public function guardarInformeGestion(Request $request){

        $request->validate([
            "tribunal" => 'required',
            "funcionario" => 'required',
            "nombramiento" => 'required',
            "fecha_recepcion" => 'required',
            "mes_evaluado" => 'required',
            "inicio_mes" => 'required|numeric|min:0',
            "final_mes" => 'required|numeric|min:0'
        ],
        [
            "tribunal.required" => "Debe ingresar un tribunal",
            "funcionario.required" => "Debe ingresar un funcionario",
            "nombramiento.required" => "Debe seleccionar el nombramiento del funcionario",
            "fecha_recepcion.required" => "Debe colocar la fecha de ingreso",
            "mes_evaluado.required" => "Debe seleccionar el mes evaluado",
            "inicio_mes.required" => "Debe indicar el número de trámites a inicio de mes",
            "inicio_mes.numeric" => "El campo de trámite a inicio de mes debe ser numérico",
            "inicio_mes.min" => "El campo de trámite a inicio de mes debe ser igual o mayor a 0",
            "final_mes.required" => "Debe indicar el número de trámites a final de mes",
            "final_mes.numeric" => "El campo de trámite a fin de mes debe ser numérico",
            "final_mes.min" => "El campo de trámite a fin de mes debe ser igual o mayor a 0",
        ]);
        $en_tiempo = isset($request->en_tiempo) ? $request->en_tiempo : "FUERA DE TIEMPO";
        $informe = DB::table("informes_gestion")
        ->insert([
            "id_tribunal" => $request->tribunal,
            "id_funcionario" => $request->funcionario,
            "nombramiento" => $request->nombramiento,
            "fecha_recepcion" => $request->fecha_recepcion,
            "mes_evaluado" => $request->mes_evaluado,
            "tramite_inicio" => $request->inicio_mes,
            "tramite_fin" => $request->final_mes,
            "cumplio" => $en_tiempo
        ]);

        return back();
    }

    public function mostrarInformes(){
        $informes = DB::table("informes_gestion")
        ->join("judicatura", "judicatura.id_judicatura", "=", "informes_gestion.id_tribunal")
        ->join("usuarios", "usuarios.id_usuario", "=", "informes_gestion.id_funcionario")
        ->get();

        return view("informes_gestion.informes_gestion", compact("informes"));
    }
}

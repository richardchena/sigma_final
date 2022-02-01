<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Actividades extends Controller
{
    public $usuarios_permitidos;
    public $lista_users;
    public $desktop;

    public function __construct() {
        $this -> desktop = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        $this -> usuarios_permitidos = array('CEL' => '192.168.0.2', 
        'PCHOME' => 'LAPTOP-LKA9OM1ES',
        'PCHOMEs' => 'DESKTOP-LSR5IV4');

        $this -> lista_users = array();

        foreach ($this -> usuarios_permitidos  as $username => $host){
            array_push($this -> lista_users, $username);
        }
    }

    public function get_list_users(){
        return $this -> lista_users;
    }

    //Funcion que retorna la lista de todos los registros (Exclusivo para el jefe)
    //Pendientes
    public function listar_actual(){
        /*return $dato = DB::table('TABLA_REPORTES')
            ->where('ESTADO', '<>', 'FINALIZADO')
            ->Where('ESTADO', '<>', 'CANCELADO')
            ->orderBy('id')
            ->get();*/

        return DB::select("SELECT ID, FORMAT (FECHA, 'dd/MM/yyyy HH:mm') as FECHA, ASIGNADO, ASUNTO, ESTADO, WORKLINE, DIFICULTAD, DEADLINE, ENTREGA, USUARIO_CREACION, COMMENT
            FROM TABLA_REPORTES
            WHERE ESTADO NOT IN ('FINALIZADO', 'CANCELADO')");
    }

    //Finalizados
    public function listar_historia(){
        return DB::select("SELECT ID, FORMAT (FECHA, 'dd/MM/yyyy HH:mm') as FECHA, ASIGNADO, ASUNTO, ESTADO, WORKLINE, DIFICULTAD, FORMAT (DEADLINE, 'dd/MM/yyyy') as DEADLINE, 
            FORMAT (ENTREGA, 'dd/MM/yyyy HH:mm') as ENTREGA, USUARIO_CREACION, COMMENT
            FROM TABLA_REPORTES
            WHERE ESTADO IN ('FINALIZADO', 'CANCELADO')
            ORDER BY ID");
    }

    //Funcion que retorna la lista de todos los registros de un usuario determinado
    //Pendientes
    public function listar_actual_user(Request $request){
        $usuario = $request -> usuario;

        return DB::select("SELECT ID, FORMAT (FECHA, 'dd/MM/yyyy HH:mm') as FECHA, ASIGNADO, ASUNTO, ESTADO, WORKLINE, DIFICULTAD, DEADLINE, ENTREGA, USUARIO_CREACION, COMMENT
                           FROM TABLA_REPORTES
                           WHERE ASIGNADO = ? AND ESTADO NOT IN ('FINALIZADO', 'CANCELADO')
                           ORDER BY ID", [$usuario]);
    }

    //Finalizados
    public function listar_historia_user(Request $request){
        $usuario = $request -> usuario;

        /*return $dato = DB::table('TABLA_REPORTES')
            ->where('USUARIO_CREACION', '=', $usuario)
            ->where('ESTADO', '=', 'FINALIZADO')
            ->orWhere('ESTADO', '=', 'CANCELADO')
            ->orderBy('id')
            ->get();*/

        return DB::select("SELECT ID, FORMAT (FECHA, 'dd/MM/yyyy HH:mm') as FECHA, ASIGNADO, ASUNTO, ESTADO, WORKLINE, DIFICULTAD, FORMAT (DEADLINE, 'dd/MM/yyyy') as DEADLINE, 
									FORMAT (ENTREGA, 'dd/MM/yyyy HH:mm') as ENTREGA, USUARIO_CREACION, COMMENT
                           FROM TABLA_REPORTES
                           WHERE ASIGNADO = ? AND ESTADO IN ('FINALIZADO', 'CANCELADO')
                           ORDER BY ID", [$usuario]);
    }

    //Usuario Actual
    public function usuario_conectado(){
        //$id_equipo = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        foreach ($this -> usuarios_permitidos  as $username => $host){
            if ($host == $this -> desktop){
                //USUARIOS ADMIN
                if($username == 'RICHARDs' || $username == 'PCHOME')
                    return array(true, $username, $host, true);
                else
                    return array(true, $username, $host, false);
                break;
            }
        }

        return array(false, '', $this -> desktop, false);
    }

    //Crear datos
    public function crear(Request $request){
        $ASUNTO = $request -> ASUNTO;
        $WORKLINE = $request -> WORKLINE;
        $DIFICULTAD = $request -> DIFICULTAD;
        $DEADLINE = $request -> DEADLINE;
        $USUARIO_CREACION = $request -> USUARIO_CREACION;
        $ASIGNADO = $request -> ASIGNADO;
        
        return DB::insert('INSERT INTO TABLA_REPORTES (ASIGNADO, ASUNTO, WORKLINE, DIFICULTAD, DEADLINE, USUARIO_CREACION) 
                    VALUES (?, ?, ?, ?, ?, ?)', [$ASIGNADO, $ASUNTO, $WORKLINE, $DIFICULTAD, $DEADLINE, $USUARIO_CREACION]);
    }

    //Actualizar datos
    public function actualizar(Request $request){
        $ID = $request -> ID;
        $ASUNTO = $request -> ASUNTO;
        $WORKLINE = $request -> WORKLINE;
        $ESTADO = $request -> ESTADO;
        $DIFICULTAD = $request -> DIFICULTAD;
        $DEADLINE = $request -> DEADLINE;
        $COMMENT = $request -> COMMENT;

        if($ESTADO == 'FINALIZADO'){
            return DB::update(
                'UPDATE TABLA_REPORTES SET ASUNTO = ?, WORKLINE = ?, ESTADO = ?, DIFICULTAD = ?, DEADLINE = ?, COMMENT = ?, ENTREGA = GETDATE() WHERE ID = ?',
                [$ASUNTO, $WORKLINE, $ESTADO, $DIFICULTAD, $DEADLINE, $COMMENT, $ID]);
        }else{
            return DB::update(
                'UPDATE TABLA_REPORTES SET ASUNTO = ?, WORKLINE = ?, ESTADO = ?, DIFICULTAD = ?, DEADLINE = ?, COMMENT = ? WHERE ID = ?',
                [$ASUNTO, $WORKLINE, $ESTADO, $DIFICULTAD, $DEADLINE, $COMMENT, $ID]);
        }
    }

    //Eliminar datos
    public function eliminar(Request $request){
        $ID = $request -> ID;
        return DB::delete('DELETE FROM TABLA_REPORTES WHERE ID = ?', [$ID]);
    }
}

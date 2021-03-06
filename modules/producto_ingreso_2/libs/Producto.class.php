<?php
  /* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | Elastix version 2.4.0-15                                               |
  | http://www.elastix.org                                               |
  +----------------------------------------------------------------------+
  | Copyright (c) 2006 Palosanto Solutions S. A.                         |
  +----------------------------------------------------------------------+
  | Cdla. Nueva Kennedy Calle E 222 y 9na. Este                          |
  | Telfs. 2283-268, 2294-440, 2284-356                                  |
  | Guayaquil - Ecuador                                                  |
  | http://www.palosanto.com                                             |
  +----------------------------------------------------------------------+
  | The contents of this file are subject to the General Public License  |
  | (GPL) Version 2 (the "License"); you may not use this file except in |
  | compliance with the License. You may obtain a copy of the License at |
  | http://www.opensource.org/licenses/gpl-license.php                   |
  |                                                                      |
  | Software distributed under the License is distributed on an "AS IS"  |
  | basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See  |
  | the License for the specific language governing rights and           |
  | limitations under the License.                                       |
  +----------------------------------------------------------------------+
  | The Original Code is: Elastix Open Source.                           |
  | The Initial Developer of the Original Code is PaloSanto Solutions    |
  +----------------------------------------------------------------------+
  $Id: Producto.class.php,v 1.1 2014-11-01 02:09:19 Juan Pablo Romero juan.romero@aplisoft.com.ec Exp $ */
class Producto{
    var $_DB;
    var $errMsg;

    function Producto(&$pDB)
    {
        // Se recibe como parámetro una referencia a una conexión paloDB
        if (is_object($pDB)) {
            $this->_DB =& $pDB;
            $this->errMsg = $this->_DB->errMsg;
        } else {
            $dsn = (string)$pDB;
            $this->_DB = new paloDB($dsn);

            if (!$this->_DB->connStatus) {
                $this->errMsg = $this->_DB->errMsg;
                // debo llenar alguna variable de error
            } else {
                // debo llenar alguna variable de error
            }
        }
    }

    /*HERE YOUR FUNCTIONS*/
    function getNumproducto_ingreso($filter_field, $filter_value)
    {
        $where    = "";
        $arrParam = null;
        if(isset($filter_field) & $filter_field !=""){
            $where    = "where $filter_field like ?";
            $arrParam = array("$filter_value%");
        }

        $query   = "SELECT COUNT(*) FROM table $where";

        $result=$this->_DB->getFirstRowQuery($query, false, $arrParam);

        if($result==FALSE){
            $this->errMsg = $this->_DB->errMsg;
            return 0;
        }
        return $result[0];
    }

    function getproducto_ingreso($limit, $offset, $filter_field, $filter_value)
    {}

    function getproducto_ingresoById($id)
    {}

    function saveNew()
    {
        $d = $_POST;
        if( trim($d['codigo']) == '') return false;

        $query = "INSERT INTO producto (codigo, nombre, descripcion) VALUES (?, ?, ?)";
        $result = $this->_DB->genQuery($query, array($d['codigo'], $d['nombre'], $d['descripcion']));        

        if($this->_DB->errMsg <> '')
            return false;
        else return true;

    }

    function consultar_disponibilidad($campo, $valor)
    {
        $respuesta = array();
        $query = "SELECT codigo, nombre FROM producto WHERE $campo = '$valor'";
        $result = $this->_DB->getFirstRowQuery($query, true);                
        
        if(!isset($result['codigo'])){
          if($campo == 'codigo') $campo = 'código';
          $respuesta = array( 'disponible'  => 1,
                              'mensaje'     => "El $campo $valor está disponible.");
        }else{
          if($campo == 'codigo') $campo = 'código';          
          $respuesta = array( 'disponible'  => 0,
                              'mensaje'     => "Ya existe un producto con el $campo $valor.");
        }

        // writeLOG("webinar.log", print_r($respuesta, 1));

        return $respuesta;
    }
}

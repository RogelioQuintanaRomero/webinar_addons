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
  $Id: index.php,v 1.1 2014-09-09 02:09:19 Juan Pablo Romero juan.romero@aplisoft.com.ec Exp $ */
//include elastix framework
include_once "libs/paloSantoGrid.class.php";
include_once "libs/paloSantoForm.class.php";
include_once "libs/JSON.php";

function _moduleContent(&$smarty, $module_name)
{
    //include module files
    include_once "modules/$module_name/configs/default.conf.php";
    include_once "modules/$module_name/libs/Producto.class.php";

    //include file language agree to elastix configuration
    //if file language not exists, then include language by default (en)
    $lang=get_language();
    $base_dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $lang_file="modules/$module_name/lang/$lang.lang";
    if (file_exists("$base_dir/$lang_file")) include_once "$lang_file";
    else include_once "modules/$module_name/lang/en.lang";

    //global variables
    global $arrConf;
    global $arrConfModule;
    global $arrLang;
    global $arrLangModule;
    $arrConf = array_merge($arrConf, $arrConfModule);
    $arrLang = array_merge($arrLang, $arrLangModule);

    //folder path for custom templates
    $templates_dir=(isset($arrConf['templates_dir']))?$arrConf['templates_dir']:'themes';
    $local_templates_dir="$base_dir/modules/$module_name/".$templates_dir.'/'.$arrConf['theme'];

    //conexion resource
    $pDB = new paloDB($arrConf['dsn_conn_database']);

    //actions
    $action  = getAction();
    $content = "";    

    switch($action){
        case 'consultar_disponibilidad':
            // Variables que vienen por POST
            $campo = getParameter("campo");
            $valor = getParameter("valor");            
            return consultar_disponibilidad($pDB, $campo, $valor);
            break;

        case 'save_new':
            $content = saveNewproducto_ingreso($smarty, $module_name, $local_templates_dir, $pDB, $arrConf);
            break;
        default: // view_form
            $content = viewFormproducto_ingreso($smarty, $module_name, $local_templates_dir, $pDB, $arrConf);
            break;
    }
    return $content;
}

function consultar_disponibilidad($pDB, $campo, $valor)
{
    $json = new Services_JSON();
    $oProducto = new Producto($pDB);
    $respuesta = $oProducto->consultar_disponibilidad($campo, $valor);    
    
    header('Content-Type: application/json');
    return $json->encode($respuesta);        
}

function viewFormproducto_ingreso($smarty, $module_name, $local_templates_dir, &$pDB, $arrConf)
{
    $oProducto = new Producto($pDB);
    $arrFormproducto_ingreso = createFieldForm();
    $oForm = new paloForm($smarty,$arrFormproducto_ingreso);

    //begin, Form data persistence to errors and other events.
    $_DATA  = $_POST;
    $action = getParameter("action");
    $id     = getParameter("id");
    $smarty->assign("ID", $id); //persistence id with input hidden in tpl

    if($action=="view")
        $oForm->setViewMode();
    else if($action=="view_edit" || getParameter("save_edit"))
        $oForm->setEditMode();
    //end, Form data persistence to errors and other events.

    if($action=="view" || $action=="view_edit"){ // the action is to view or view_edit.
        $dataproducto_ingreso = $oProducto->getproducto_ingresoById($id);
        if(is_array($dataproducto_ingreso) & count($dataproducto_ingreso)>0)
            $_DATA = $dataproducto_ingreso;
        else{
            $smarty->assign("mb_title", _tr("Error get Data"));
            $smarty->assign("mb_message", $oProducto->errMsg);
        }
    }

    $smarty->assign("SAVE", _tr("Save"));
    $smarty->assign("EDIT", _tr("Edit"));
    $smarty->assign("CANCEL", _tr("Cancel"));
    $smarty->assign("REQUIRED_FIELD", _tr("Required field"));
    $smarty->assign("icon", "images/list.png");

    $htmlForm = $oForm->fetchForm("$local_templates_dir/form.tpl",_tr("Products"), $_DATA);
    $content = "<form  method='POST' style='margin-bottom:0;' action='?menu=$module_name'>".$htmlForm."</form>";

    return $content;
}

function saveNewproducto_ingreso($smarty, $module_name, $local_templates_dir, &$pDB, $arrConf)
{
    $oProducto = new Producto($pDB);
    $arrFormproducto_ingreso = createFieldForm();
    $oForm = new paloForm($smarty,$arrFormproducto_ingreso);

    if(!$oForm->validateForm($_POST)){
        // Validation basic, not empty and VALIDATION_TYPE 
        $smarty->assign("mb_title", _tr("Validation Error"));
        $arrErrores = $oForm->arrErroresValidacion;
        $strErrorMsg = "<b>"._tr("The following fields contain errors").":</b><br/>";
        if(is_array($arrErrores) && count($arrErrores) > 0){
            foreach($arrErrores as $k=>$v)
                $strErrorMsg .= "$k, ";
        }
        $smarty->assign("mb_message", $strErrorMsg);
    
    }else{                
        $bExito = $oProducto->saveNew();
        if($bExito){
            $smarty->assign("mb_title", _tr("Message"));
            $smarty->assign("mb_message", _tr("Product was saved correctly."));
        }else{
            $smarty->assign("mb_title", _tr("Message"));
            $smarty->assign("mb_message", _tr("Product could not be saved."));
        }

    }
    $content = viewFormproducto_ingreso($smarty, $module_name, $local_templates_dir, $pDB, $arrConf);
    return $content;
}

function createFieldForm()
{

    $arrFields = array(
            "codigo"   => array(      "LABEL"                        => _tr("Product code"),
                                            "REQUIRED"               => "no",
                                            "INPUT_TYPE"             => "TEXT",
                                            "INPUT_EXTRA_PARAM"      => "",
                                            "VALIDATION_TYPE"        => "text",
                                            "VALIDATION_EXTRA_PARAM" => ""
                                            ),
            "nombre"   => array(      "LABEL"                        => _tr("Name"),
                                            "REQUIRED"               => "no",
                                            "INPUT_TYPE"             => "TEXT",
                                            "INPUT_EXTRA_PARAM"      => "",
                                            "VALIDATION_TYPE"        => "text",
                                            "VALIDATION_EXTRA_PARAM" => ""
                                            ),
            "descripcion"   => array(      "LABEL"                   => _tr("Description"),
                                            "REQUIRED"               => "no",
                                            "INPUT_TYPE"             => "TEXT",
                                            "INPUT_EXTRA_PARAM"      => array("size" => "40"),
                                            "VALIDATION_TYPE"        => "text",
                                            "VALIDATION_EXTRA_PARAM" => ""
                                            ),

            );
    return $arrFields;
}

function getAction()
{
    if(getParameter('save_new')) //Get parameter by POST (submit)
        return 'save_new';
    elseif(getParameter('save_edit'))
        return 'save_edit';
    elseif(getParameter('delete')) 
        return 'delete';
    elseif(getParameter('new_open')) 
        return 'view_form';
    elseif(getParameter('action') == 'view')      //Get parameter by GET (command pattern, links)
        return 'view_form';
    elseif(getParameter('action') == 'view_edit')
        return 'view_form';
    elseif(getParameter('action') == 'consultar_disponibilidad')
        return 'consultar_disponibilidad';
    elseif(isset($_POST['save_new']))
        return "save_new";
    else
        return "report"; //cancel
}

function _pre($a)
{
    echo "<pre><font color=#ffffff>";
    print_r($a);
    echo "</font></pre>";
}
?>
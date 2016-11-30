<?php

/**
 * Description of New_pageResponseHandler
 *
 * @author Eduardo Latorre Jarque <eduardo.latorre@gmail.com>
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/New_pageResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';
require_once(DOKU_PLUGIN.'ajaxcommand/requestparams/PageKeys.php');
require_once(DOKU_PLUGIN.'ajaxcommand/requestparams/ResponseParameterKeys.php');
require_once(tpl_incdir().'conf/cfgIdConstants.php');


class New_shortcuts_pageResponseHandler extends New_pageResponseHandler
{

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
        parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);

        $containerClass = "ioc/gui/ContentTabNsTreeListFromPage";
        $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=page";
        $urlTree = "lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/";

        $params = array(
            "id" => cfgIdConstants::TB_SHORTCUTS,
            "title" =>  $responseData['title'],
            "standbyId" => cfgIdConstants::MAIN_CONTENT,
            "urlBase" => $urlBase,
            "data" => $responseData["content"],
            "treeDataSource" => $urlTree,
            'typeDictionary' => array (
                                    'p' => 
                                    array (
                                      'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=project\'',
                                      'params' => 
                                      array (
                                        0 => 'projectType',
                                      ),
                                    ),
                                  ),                
        );
        $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO,
                            $params,
                            ResponseParameterKeys::FIRST_POSITION,
                            TRUE,
                            $containerClass);
    }

}

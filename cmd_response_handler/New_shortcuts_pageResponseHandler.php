<?php
/**
 * Description of New_shortcuts_pageResponseHandler
 * @author Eduardo Latorre Jarque <eduardo.latorre@gmail.com>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_COMMAND')) define('DOKU_COMMAND', DOKU_INC . "lib/plugins/ajaxcommand/");
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', tpl_incdir());

require_once(DOKU_TPL_INCDIR.'cmd_response_handler/New_pageResponseHandler.php');
require_once(DOKU_TPL_INCDIR.'conf/cfgIdConstants.php');
require_once(DOKU_COMMAND.'defkeys/ResponseParameterKeys.php');

class New_shortcuts_pageResponseHandler extends New_pageResponseHandler {

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);

        $containerClass = "ioc/gui/ContentTabNsTreeListFromPage";
        $urlBase = "ajax.php?call=page";
        $urlTree = "ajaxrest.php/ns_tree_rest/";

        $params = array(
            "id" => cfgIdConstants::TB_SHORTCUTS,
            "title" =>  $responseData['title'],
            "standbyId" => cfgIdConstants::BODY_CONTENT,
            "urlBase" => $urlBase,
            "data" => $responseData["content"],
            "treeDataSource" => $urlTree,
            'typeDictionary' => array (
                                    'p' => array (
                                              'urlBase' => '\'ajax.php?call=project\'',
                                              'params' => array (0 => 'projectType')
                                           ),
                                )
        );
        $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO,
                            $params,
                            ResponseParameterKeys::FIRST_POSITION,
                            TRUE,
                            $containerClass);
    }

}

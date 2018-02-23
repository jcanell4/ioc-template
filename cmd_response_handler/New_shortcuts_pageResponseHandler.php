<?php
/**
 * Description of New_shortcuts_pageResponseHandler
 * @author Eduardo Latorre Jarque <eduardo.latorre@gmail.com>
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR.'cmd_response_handler/New_pageResponseHandler.php');
require_once(DOKU_TPL_INCDIR.'conf/cfgIdConstants.php');

class New_shortcuts_pageResponseHandler extends New_pageResponseHandler {

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);

        $containerClass = "ioc/gui/ContentTabNsTreeListFromPage";
        $urlBase = "lib/exe/ioc_ajax.php?call=page";
        $urlTree = "lib/exe/ioc_ajaxrest.php/ns_tree_rest/";

        $params = array(
            "id" => cfgIdConstants::TB_SHORTCUTS,
            "title" =>  $responseData['title'],
            "standbyId" => cfgIdConstants::BODY_CONTENT,
            "urlBase" => $urlBase,
            "data" => $responseData["content"],
            "treeDataSource" => $urlTree,
            'typeDictionary' => array ('p' => array (
                                              'urlBase' => "'lib/exe/ioc_ajax.php?call=project'",
                                              'params' => array (0 => ResponseHandlerKeys::PROJECT_TYPE)
                                           ),
                                )
        );
        $ajaxCmdResponseGenerator->addAddTab(cfgIdConstants::ZONA_NAVEGACIO,
                            $params,
                            ResponseHandlerKeys::FIRST_POSITION,
                            TRUE,
                            $containerClass);
    }

}

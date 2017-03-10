<?php
/**
 * Description of page_response_handler
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class RecentResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct("recents");
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {
        $ajaxCmdResponseGenerator->addRecents(
                $responseData["id"], 
                $responseData["title"], 
                $responseData["content"]['list'],
                array(
                        'urlBase' => "lib/plugins/ajaxcommand/ajax.php?call=recent",
                        'formId' => $responseData["formId"],
                ),
                array(
                    'callAtt' => 'call',
                    'urlBase' => "lib/plugins/ajaxcommand/ajax.php",
                )
        );
        $ajaxCmdResponseGenerator->addMetadata(
            $responseData["id"], 
            array(array(
                "id" => "meta".$responseData["id"],
                "title" => WikiIocLangManager::getLang("recent_controls"),
                "content" => $responseData['content']['form_controls']
            ))
        );
        
        $ajaxCmdResponseGenerator->addInfoDta($ajaxCmdResponseGenerator->generateInfo(
                'info',
                WikiIocLangManager::getLang("recent_list_loaded"),
                'recent_list'
        ));
    }

}

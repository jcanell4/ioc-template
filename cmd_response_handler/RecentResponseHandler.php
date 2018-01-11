<?php
/**
 * RecentResponseHandler
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', WikiGlobalConfig::tplIncDir());
require_once(DOKU_TPL_INCDIR . 'cmd_response_handler/WikiIocResponseHandler.php');

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
                    'urlBase' => "lib/exe/ioc_ajax.php?call=recent",
                    'formId' => $responseData["formId"],
                ),
                array(
                    'callAtt' => 'call',
                    'urlBase' => "lib/exe/ioc_ajax.php",
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

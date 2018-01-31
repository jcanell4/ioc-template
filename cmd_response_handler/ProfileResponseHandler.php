<?php
/**
 * Description ProfileResponseHandler
 * @irresponsable Rafael
 */
if (!defined("DOKU_INC")) die();
require_once(DOKU_INC . 'lib/plugins/ajaxcommand/defkeys/ResponseHandlerKeys.php');
require_once(DOKU_TPL_INCDIR.'cmd_response_handler/WikiIocResponseHandler.php');

class ProfileResponseHandler extends WikiIocResponseHandler {

    function __construct() {
        parent::__construct(ResponseHandlerKeys::PROFILE);
    }

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator) {

        if ($requestParams['page']) {
            // afegeix la pestanya al panell central
            $ajaxCmdResponseGenerator->addUserProfile($responseData['id'],
                                                      $responseData['ns'],
                                                      $responseData['title'],
                                                      $responseData['content']
                                                     );
            // missatge a mostrar al panell inferior
            $ajaxCmdResponseGenerator->addInfoDta($responseData['info']);

            // Obté els Selectors CSS dels forms del plugin USERMANAGER
            $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                                                $responseData['id'],
                                                true,
                                                "ioc/dokuwiki/processUserProfile",
                                                array(
                                                   'urlBase' => "lib/exe/ioc_ajax.php?call=profile",
                                                   'formsSelector' => "#dw__register",
                                                   'user' => WikiIocInfoManager::getInfo('client')
                                                )
                                        );
        }
    }

}

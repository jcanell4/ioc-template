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
require_once(tpl_incdir().'conf/cfgIdConstants.php');


class New_shortcuts_pageResponseHandler extends New_pageResponseHandler
{

    protected function response($requestParams, $responseData, &$ajaxCmdResponseGenerator)
    {
        parent::response($requestParams, $responseData, $ajaxCmdResponseGenerator);

        $user_id = WikiIocInfoManager::getInfo('client');

        $dades = $this->getModelWrapper()->getShortcutsTaskList($user_id);
        $urlBase = "lib/plugins/ajaxcommand/ajax.php?call=page";

        $ajaxCmdResponseGenerator->addShortcutsTab(cfgIdConstants::ZONA_NAVEGACIO,
            cfgIdConstants::TB_SHORTCUTS,
            $dades['title'],
            $dades['content'],
            $urlBase);

    }

}

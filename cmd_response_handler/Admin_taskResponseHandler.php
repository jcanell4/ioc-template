<?php
/**
 * Description of admin_task_response_handler
 *
 * @author Eduard Latorre
 */

if (!defined("DOKU_INC")) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(tpl_incdir().'cmd_response_handler/WikiIocResponseHandler.php');
//require_once(tpl_incdir().'cmd_response_handler/PageResponseHandler.php');
require_once DOKU_PLUGIN.'ajaxcommand/JsonGenerator.php';

class Admin_taskResponseHandler extends WikiIocResponseHandler {
    function __construct() {
        parent::__construct(WikiIocResponseHandler::ADMIN_TASK);
    }
    protected function response($requestParams,
                                $responseData,
                                &$ajaxCmdResponseGenerator) {
        //TODO La informació ha de venir de DokuModelAdapter. Cal fer el canvi
        $responseData["info"] = "ADMIN TASK ";
        //error_log("AAdmin_taskResponseHandler\n", 3, "/var/www/php.log");
        if($requestParams['page']){
          switch($requestParams['page']) {
            case "acl":
            //error_log(print_r($info,true), 3, "/var/www/php.log");
              // resposta de tipus admin_task
            // Aquests contindrà la mateixa estructura que la reposta html
            // que ja has generat, però el seu tipus serà admin_task,
            // en comptes de html.


            $ajaxCmdResponseGenerator->addAdminTask($responseData['id'],
                                                   $responseData['ns'],
                                                   $responseData['title'],
                                                   $responseData['content']);

            // missatge a mostrar al panell inferior
            $info=Array();
            $info["documentId"] = $responseData['id'];
            $info["info"] = $responseData["info"];
            $info["message"] = $responseData["info"];
            $info["timestamp"] = date('d-m-Y H:i:s');
            $info['type']= 'success';
            $ajaxCmdResponseGenerator->addInfoDta($info);

            /*
            //Finalment hauràs de enviar una darrera resposta de tipus
            command.
            Estracta del command anomenat addProcessDomFromFunction.
            Es tracta defer executar codi javascript per tal de fer algun
            tractament a un node dom.concretament
            el del contingut que enviïs a la primera resposta.
            Necessitaràs passar-li com a paràmetre l'identificador del node
            dom atractar ($responseData['id']),
            un booleà amb valor true indicant que la funció
            es carregarà via amd,
            el complert de la funció a executar
            (hauras decrear-la) i una array amb paràmetres
            extres que es necessitin. Crec que només serà necessari
            passar un únic paràmetre extra contenint un adreça html
            que podem anomenar urlBase i serà la urlBase de la comanda
            (nova) que caldrà executar per guardar els canvis de la tasca.
            De moment envia'l acommandReport.
            */

            $ajaxCmdResponseGenerator->addProcessDomFromFunction(
                $responseData['id'],
                true,
                "ioc/dokuwiki/processAclTask",
                array(
                  "urlBaseSelecciona" => "lib/plugins/ajaxcommand/ajax.php?call=commandReport",
                  "urlBaseActualiza" => "lib/plugins/ajaxcommand/ajax.php?call=commandReport"
                )
            );
            error_log("After processAclTask\n", 3, "/var/www/php.log");

            break;
          }
      }
    }
}

?>

<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SEND_MESSAGE_TO_ROLS_BUTTON
                                          ,"title" => "Enviar missatge"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "call" => "'send_message_to_rols'"
                                          ,"parent" => "'selected_projects'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSendMessageToRols'"
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php'"
                                          ,"urlListRols" => "'lib/exe/ioc_ajaxrest.php/list_rols_rest/'"
                                          ,"dialogTitle" => "'Enviar un missatge'"
                                          ,"labelRols" => "'Selecciona els rols dels destinataris'"
                                          ,"placeholderRols" => "'Rols destinataris'"
                                          ,"labelLlista" => "'Llista de rols seleccionats'"
                                          ,"labelMissatge" => "'Missatge pels destinataris'"
                                          ,"labelButtonAcceptar" => "'Enviar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                          ,"standbyId" => cfgIdConstants::BODY_CONTENT
                                          ,'disableOnSend' => true
                                        )
                            )
              );

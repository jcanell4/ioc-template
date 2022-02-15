<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SEND_MESSAGE_BUTTON
                                          ,"title" => "Enviar missatge"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSendMessage'"
                                          ,"query" => "'do=send_message'"
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php'"
                                          ,"urlListRols" => "'lib/exe/ioc_ajaxrest.php/list_rols_rest/'"
                                          ,"dialogTitle" => "'Enviar un missatge'"
                                          ,"RolsDestinatarislabel" => "'Selecciona els rols dels destinataris'"
                                          ,"RolsDestinatarisplaceHolder" => "'Rols destinataris'"
                                          ,"labelButtonAcceptar" => "'Enviar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                        )
                            )
              );

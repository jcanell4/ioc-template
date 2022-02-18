<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SEND_LIST_TO_USERS_BUTTON
                                          ,"title" => "Enviar missatge"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "call" => "'send_list_to_users'"
                                          ,"parent" => "'selected_projects'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSendListToUsers'"
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php'"
                                          ,"urlListUsuaris" => "'lib/exe/ioc_ajaxrest.php/list_users_rest/'"
                                          ,"dialogTitle" => "'Enviar un missatge'"
                                          ,"labelUsuaris" => "'Selecciona els destinataris'"
                                          ,"placeholderUsuaris" => "'Destinataris'"
                                          ,"labelLlista" => "'Llista d usuaris seleccionats'"
                                          ,"labelMissatge" => "'Missatge pels destinataris'"
                                          ,"placeholderMissatge" => "'Escriu un missatge'"
                                          ,"labelButtonAcceptar" => "'Enviar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                          ,"standbyId" => cfgIdConstants::BODY_CONTENT
                                        )
                            )
              );

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
                                          ,"dialogTitle" => "'Enviar un missatge'"
                                          ,"labelLlistaUsuaris" => "'Llista d\'usuaris seleccionats'"
                                          ,"labelMissatge" => "'Missatge pels destinataris'"
                                          ,"placeholderMissatge" => "'Escriu un missatge'"
                                          ,"widgetClass" => "'IocFilteredList'"
                                          ,"widgetLabel" => "'Destinatari'"
                                          ,"widgetSearchDataUrl" => "'lib/exe/ioc_ajax.php?call=user_list'"
                                          ,"widgetDialogTitle" => "'Cerca usuaris per afegir'"
                                          ,"widgetButtonLabel" => "'Cercar'"
                                          ,"widgetDialogButtonLabel" => "'Afegir'"
                                          ,"widgetFieldName" => "'to'"
                                          ,"widgetFieldId" => "'username'"
                                          ,"widgetDefaultEntryField" => "'name'"
                                          ,"labelButtonAcceptar" => "'Enviar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                        )
                            )
              );

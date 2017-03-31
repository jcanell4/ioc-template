<?php
$arrParcial = array(
             "class" => "WikiIocNotifierButton"
            ,"parms" => array(
                           "DOM" => array(
                     						 "id" => cfgIdConstants::NOTIFIER_BUTTON_INBOX
                      						,"label" => "(0)"
                      						,"title" => "Notificacions rebudes"
                      						,"class" => "iocDisplayBlock"
                      					 )
                          ,"DJO" => array(
                                            "mailbox" => "'inbox'",
                                            "counter" => true,
                      						 "autoSize" => false
                      						,"visible" => true
                                                                ,'iconClass' => "'iocIconInactiveAlarm'"                              
                                                                ,'activeIconClass' => "'iocIconActiveAlarm'"
                      						,"displayBlock" => false
                                    )
                        )
          );


<?php
$arrParcial = array(
             "class" => "WikiIocNotifierButton"
            ,"parms" => array(
                           "DOM" => array(
                     						 "id" => cfgIdConstants::NOTIFIER_BUTTON_OUTBOX
                      						,"label" => "(0)"
                      						,"title" => "Notificacions"
                      						,"class" => "iocDisplayBlock"
                      					 )
                          ,"DJO" => array(
                                            "mailbox" => "'outbox'",
                      						 "autoSize" => false
                      						,"visible" => true
            //TODO[Xavi] Canviar per una iconoa més apropiada per missatges enviats
                                                                ,'iconClass' => "'iocIconInactiveAlarm'"
                                                                ,'activeIconClass' => "'iocIconActiveAlarm'"
                      						,"displayBlock" => false
                                    ),
                        "PRP" => [
                            "mailbox" => "outbox",
                        ]
    ),

          );


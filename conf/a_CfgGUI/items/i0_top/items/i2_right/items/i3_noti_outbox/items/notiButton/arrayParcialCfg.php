<?php
$arrParcial = array(
             "class" => "WikiIocNotifierButton"
            ,"parms" => array(
                           "DOM" => array(
                     						 "id" => cfgIdConstants::NOTIFIER_BUTTON_OUTBOX
                      						/*,"label" => cfgIdConstants::NOTIFIER_BUTTON_OUTBOX_LABEL*/
                      						,"title" => "Notificacions enviades"
                      						,"class" => "iocDisplayBlock"
                      					 )
                          ,"DJO" => array(

                                            "mailbox" => "'outbox'",
                      						 "autoSize" => false
                      						,"visible" => true
            //TODO[Xavi] Canviar per una iconoa més apropiada per missatges enviats
                                                                ,'iconClass' => "'iocIconOutbox'"
//                                                                ,'activeIconClass' => "'iocIconActiveAlarm'"
                      						,"displayBlock" => false
                                    ),
                        "PRP" => [
                            "mailbox" => "outbox",
                        ]
    ),

          );


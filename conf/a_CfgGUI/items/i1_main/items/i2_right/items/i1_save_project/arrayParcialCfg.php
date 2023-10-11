<?php
$arrParcial = array(
                 "class" => "WikiRequestEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SAVE_PROJECT_BUTTON
                                          ,"title" => "Desar el Formulari del Projecte"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSave'"
                                          ,"query" => "'do=save'"
                                          ,'disableOnSend' => true
                                        )
                            )
              );

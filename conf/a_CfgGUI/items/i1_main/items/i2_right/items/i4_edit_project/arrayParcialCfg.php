<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::EDIT_PROJECT_BUTTON
                                          ,"title" => "Edició"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=edit'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconEdit'"
                                          ,"standbyId" => cfgIdConstants::BODY_CONTENT
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php?call=project'"
                                          ,'disableOnSend' => true
                                        )
                            )
              );

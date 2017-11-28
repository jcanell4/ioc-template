<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::PRINT_BUTTON
                                          ,"title" => "Versió per imprimir"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconPreviewPrint'"
                                          ,"urlBase" => "'ajax.php'"
                                          ,"method" => "'post'"
                                          ,"standbyId" => cfgIdConstants::BODY_CONTENT
                                        )
                            )
              );

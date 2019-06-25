<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::FTPSEND_BUTTON
                                          ,"title" => "Envia fitxers per FTP"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=ftpsend'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,"iconClass" => "'iocIconUpload'"
                                          ,"standbyId" => cfgIdConstants::TOP_BLOC
//                                          ,"standbyId" => cfgIdConstants::BODY_CONTENT
                                          ,"hasTimer" => true
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php?call=ftpsend'"
                                        )
                            )
              );

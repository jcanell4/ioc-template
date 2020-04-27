<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::FTP_PROJECT_BUTTON
                                          ,"title" => "Envia fitxers de projecte via FTP"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=ftp_project'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,"iconClass" => "'iocIconUpload'"
                                          ,"standbyId" => cfgIdConstants::BODY_CONTENT
                                          ,"hasTimer" => true
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php?call=project'"
                                        )
                            )
              );

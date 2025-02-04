<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::DUPLICATE_FOLDER_BUTTON
                                          ,"title" => "Duplicate Folder"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=new'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconDuplicateFolder'"
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php'"
                                          ,"dialogTitle" => "'Duplicar una carpeta de materials'"
                                          ,"DirectoriOrigenlabel" => "'Carpeta origen'"
                                          ,"DirectoriOrigenplaceHolder" => "'Selecciona la carpeta a l\'arbre'"
                                          ,"DirectoriDestilabel" => "'Carpeta destí'"
                                          ,"DirectoriDestiplaceHolder" => "'Carpeta destí'"
                                          ,"labelButtonAcceptar" => "'Acceptar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                          ,'disableOnSend' => true
                                        )
                            )
              );

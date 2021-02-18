<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::RENAME_FOLDER_BUTTON
                                          ,"title" => "Rename Folder"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=new'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconRenameFolder'"
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php'"
                                          ,"dialogTitle" => "'Canviar de nom o moure una carpeta'"
                                          ,"NomOrigenlabel" => "'Carpeta origen'"
                                          ,"NomOrigenplaceHolder" => "'Carpeta origen'"
                                          ,"NomCarpetalabel" => "'Nou nom de la caprpeta'"
                                          ,"NomCarpetaplaceHolder" => "'Nou nom de la carpeta'"
                                          ,"labelButtonAcceptar" => "'Acceptar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                        )
                            )
              );

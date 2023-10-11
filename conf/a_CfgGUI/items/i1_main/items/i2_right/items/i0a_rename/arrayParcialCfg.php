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
                                          ,"DirectoriOrigenlabel" => "'Carpeta origen'"
                                          ,"DirectoriOrigenplaceHolder" => "'Selecciona la carpeta a l\'arbre'"
                                          ,"DirectoriDestilabel" => "'Carpeta destí (no és obligatori)'"
                                          ,"DirectoriDestiplaceHolder" => "'Carpeta destí'"
                                          ,"NouNomCarpetalabel" => "'Nou nom de la caprpeta'"
                                          ,"NouNomCarpetaplaceHolder" => "'Nou nom de la carpeta'"
                                          ,"labelButtonAcceptar" => "'Acceptar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                          ,'disableOnSend' => true
                                        )
                            )
              );

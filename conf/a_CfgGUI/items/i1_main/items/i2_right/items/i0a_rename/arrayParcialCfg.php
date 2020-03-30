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
                                          ,"dialogTitle" => "'Rebatejar directori'"
                                          ,"EspaideNomslabel" => "'Espai de Noms'"
                                          ,"EspaideNomsplaceHolder" => "'Espai de Noms'"
                                          ,"NouNomlabel" => "'Nou nom del directori'"
                                          ,"NouNomplaceHolder" => "'Nou nom del directori'"
                                          ,"labelButtonAcceptar" => "'Acceptar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                        )
                            )
              );

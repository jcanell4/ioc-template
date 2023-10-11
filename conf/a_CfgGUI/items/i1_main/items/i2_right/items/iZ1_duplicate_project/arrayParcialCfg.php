<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::DUPLICATE_PROJECT_BUTTON
                                          ,"title" => "Duplicar projecte"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconDuplicateProject'"

                                          ,"query" => "'do=duplicate_project'"
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php'"
                                          ,"dialogTitle" => "'Duplicar un projecte'"
                                          ,"EspaideNomslabel" => "'Escriu el nou Espai de Noms'"
                                          ,"EspaideNomsplaceHolder" => "'Espai de Noms'"
                                          ,"NouProjectlabel" => "'Escriu el nom del nou Projecte'"
                                          ,"NouProjectplaceHolder" => "'Nom del nou Projecte'"
                                          ,"SeleccioAcciolabel" => "'Marca si vols moure'"
                                          ,"SeleccioAcciolabel2" => "'(si vols duplicar no el marquis)'"
                                          ,"labelButtonAcceptar" => "'Crear'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                          ,'disableOnSend' => true
                                        )
                            )
              );

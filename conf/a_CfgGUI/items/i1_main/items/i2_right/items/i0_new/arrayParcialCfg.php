<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::NEW_BUTTON
                                          ,"title" => "Nou"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'do=new'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconNew'"
                                          ,"urlBase" => "'lib/exe/ioc_ajax.php'"
                                          ,"urlListProjects" => "'lib/exe/ioc_ajaxrest.php/list_projects_rest/'"
                                          ,"urlListTemplates" => "'lib/exe/ioc_ajaxrest.php/list_templates_rest/'"
                                          ,"dialogTitle" => "'Nou Document'"
                                          ,"EspaideNomslabel" => "'Espai de Noms'"
                                          ,"EspaideNomsplaceHolder" => "'Espai de Noms'"
                                          ,"Projecteslabel" => "'Selecció del tipus de projecte'"
                                          ,"ProjectesplaceHolder" => "'Selecció del tipus de projecte'"
                                          ,"NouProjectelabel" => "'Nom del nou Projecte'"
                                          ,"NouProjecteplaceHolder" => "'Nom del nou Projecte'"
                                          ,"Templateslabel" => "'Selecció de la plantilla'"
                                          ,"TemplatesplaceHolder" => "'Selecció de la plantilla'"
                                          ,"NouDocumentlabel" => "'Nom del nou Document'"
                                          ,"NouDocumentplaceHolder" => "'Nom del nou Document'"
                                          ,"NumUnitatslabel" => "'Indica el nombre d\'unitats'"
                                          ,"NumUnitatsplaceHolder" => "'nombre d\'unitats'"
                                          ,"labelButtonNumUnitats" => "'mostra taula'"
                                          ,"labelButtonAcceptar" => "'Acceptar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                        )
                            )
              );

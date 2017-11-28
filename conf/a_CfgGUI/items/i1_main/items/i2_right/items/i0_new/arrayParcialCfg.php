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
                                          ,"urlBase" => "'ajax.php'"
                                          ,"urlListProjects" => "'ajaxrest.php/list_projects_rest/'"
                                          ,"urlListTemplates" => "'ajaxrest.php/list_templates_rest/'"
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
                                          ,"labelButtonAcceptar" => "'Acceptar'"
                                          ,"labelButtonCancellar" => "'Cancel·lar'"
                                        )
                            )
              );

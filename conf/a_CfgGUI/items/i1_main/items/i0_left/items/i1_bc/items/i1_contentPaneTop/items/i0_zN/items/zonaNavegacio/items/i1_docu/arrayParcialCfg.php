<?php
$arrParcial = array(
                 "class" => "WikiIocContainerFromPage"
                ,"parms" => array(
                               "DOM" => array(
                                           "id"=> cfgIdConstants::TB_DOCU
                                          ,"label" => "documentació"
                                        )
                              ,"DJO" => array(
                                           "urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=page'"
                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
                                        )
                              ,"PRP" => array(
                                           "page" => ":wiki:navigation"
                                        )
                            )
              );

<?php
$arrParcial = array(
                 "class" => "WikiIocContainerFromPage"
                ,"parms" => array(
                               "DOM" => array(
                                           "id"=> cfgIdConstants::TB_DOCU
                                          ,"label" => "documentació"
                                        )
                              ,"DJO" => array(
                                           "urlBase" => "'lib/plugins/ajaxcommand/ajax.php?'"
//                                          ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
                                          ,"defaultCall" => "'call=print'"
                                        )
                              ,"PRP" => array(
                                           "page" => ":wiki:navigation"
                                        )
                            )
              );


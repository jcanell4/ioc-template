<?php
$arrParcial = array(
                 "class" => "WikiIocTreeContainer"
                ,"parms" => array(
                               "DOM" => array(
                                           "id"=> cfgIdConstants::TB_INDEX
                                          ,"label" => "Índex"
                                        )
                              ,"DJO" => array(
                                           "treeDataSource" => "'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/'"
                                          ,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=page'"
                                          ,"standbyId" => "'bodyContent'"        
                                        )
                            )
              );


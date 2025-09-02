<?php
$arrParcial = array(
             "class" => "WikiIocMenuItem"
            ,"parms" => array(
                           "DOM" => array(
                      						 "id" => cfgIdConstants::LOGOFF_MENU_ITEM
                      						,"label" => "Desconnectar"
                      					)
                          ,"DJO" => array(
                      						 "query" => "'do=logoff'"
                      						,"autoSize" => true
                      						,"disabled" => false
                      						,"urlBase" => "'lib/exe/ioc_ajax.php?call=login'"
                                    )
                        )
          );


<?php
$arrParcial = array(
             "class" => "WikiIocMenuItemButton"
            ,"parms" => array(
                           "DOM" => array(
                      						 "id" => cfgIdConstants::LOGOFF_MENU_ITEM
                      						,"label" => "Desconnectar"
                      					)
                          ,"DJO" => array(
                      						 "query" => "'do=logoff'"
                      						,"autoSize" => true
                      						,"disabled" => false
                      						,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=login'"
                                    )
                        )
          );


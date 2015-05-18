<?php
$arrParcial = array(
             "class" => "WikiIocMenuItem"
            ,"parms" => array(
                           "DOM" => array(
                      						 "id" => cfgIdConstants::USER_MENU_ITEM
                      						,"label" => "La meva pàgina"
                      					)
                          ,"DJO" => array(
                      						 "query" => "'id=user'"
                      						,"autoSize" => true
                      						,"disabled" => false
                      						,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=new_page'"
                      						,"standbyId" => "'bodyContent'"
                                    )
                        )
          );


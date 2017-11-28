<?php
$arrParcial = array(
             "class" => "WikiIocMenuItem"
            ,"parms" => array(
                           "DOM" => array(
                      						 "id" => cfgIdConstants::SHORTCUTS_MENU_ITEM
                      						,"label" => "Les meves dreceres"
                      					)
                          ,"DJO" => array(
//                      						 "query" => "'id=user'"
                      						"autoSize" => true
                      						,"disabled" => false
                      						,"urlBase" => "'ajax.php?call=page'"
                      						,"standbyId" => "'bodyContent'"
                                    )
                        )
          );


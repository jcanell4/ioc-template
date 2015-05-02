<?php
$arrParcial = array(
             "class" => "WikiIocMenuItemButton"
            ,"parms" => array(
                           "DOM" => array(
                      						 "id" => cfgIdConstants::TALK_USER_MENU_ITEM
                      						,"label" => "Discussió"
                      					)
                          ,"DJO" => array(
                      						 "query" => "'id=talkUser'"
                      						,"autoSize" => true
                      						,"disabled" => false
                      						,"urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=page'"
                      						,"standbyId" => "'bodyContent'"
                                    )
                        )
          );


<?php
$arrParcial = array(
             "class" => "WikiIocMenuItem"
            ,"parms" => array(
                           "DOM" => array(
                      						 "id" => cfgIdConstants::TALK_USER_MENU_ITEM
                      						,"label" => "Discussió"
                      					)
                          ,"DJO" => array(
                      						 "query" => "'id=talkUser'"
                      						,"autoSize" => true
                      						,"disabled" => false
                      						,"urlBase" => "'ajax.php?call=page'"
                      						,"standbyId" => "'bodyContent'"
                                    )
                        )
            ,"hidden" => true
          );


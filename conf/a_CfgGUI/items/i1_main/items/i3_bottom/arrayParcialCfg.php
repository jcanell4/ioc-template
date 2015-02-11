<?php
$arrParcial = array(
             "class" => "WikiIocItemsPanel"
            ,"parms" => array(
                     "DOM" => array(
                         "id" => cfgIdConstants::BOTTOM_PANEL
                         ,"region" => "bottom"
                         ,"doLayout" => "false"
                         ,"splitter" => "true"
                     )
                    ,"CSS" => array(
                        "height" => "60px"
                     )
                  )
            ,"items" => array(
                      array(
                          "class" => "WikiIocTextContentPane"
                         ,"parms" => array(
                             "DOM" => array(
                                "id" => cfgIdConstants::ZONA_MISSATGES
                             )
                            ,"PRP" => array(
                                "missatge" => "estoy aquí"
                             )
                          )
                      )
                  )
          );


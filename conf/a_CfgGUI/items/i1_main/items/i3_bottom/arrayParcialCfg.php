<?php
$arrParcial = array(
             "class" => "WikiIocItemsPanel"
            ,"parms" => array(
                     "DOM" => array(
                         "id" => "id_BottomPanel"
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
                                "id" => "zonaMissatges"
                             )
                            ,"PRP" => array(
                                "missatge" => "estoy aquí"
                             )
                          )
                      )
                  )
          );


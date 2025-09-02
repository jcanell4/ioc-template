<?php
$arrParcial = array(
             "class" => "WikiIocItemsPanel"
            ,"parms" => array(
                     "DOM" => array(
                         "id" => cfgIdConstants::BOTTOM_PANEL
                     )
                    ,"CSS" => array(
                        "height" => "60px"
                     )
                    ,"DJO" => array(
                          "region" => "'bottom'"
                         ,"doLayout" => "false"
                         ,"splitter" => "true"
                         //,"toggleSplitterCollapsedSize" => "20px"
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


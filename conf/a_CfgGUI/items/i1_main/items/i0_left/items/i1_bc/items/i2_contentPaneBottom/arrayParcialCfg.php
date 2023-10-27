<?php
$arrParcial = array(
                 "class" => "WikiIocItemsPanel"
                ,"parms" => array(
                               "DOM" => array(  // TODO [Josep]: cal canviar les propietats region, doLayout splitter i closable, assignades al DOM i passar-les a DJO
                                           "id" => cfgIdConstants::LEFT_BOTTOM
                                        )
                              ,"CSS" => array(
                                          "height" => "40%"
                                        )
                              ,"DJO" => array(
                                           "region" => "'bottom'"
                                          ,"doLayout" => "true"
                                          ,"splitter" => "true"
                                        )
                            )
              );


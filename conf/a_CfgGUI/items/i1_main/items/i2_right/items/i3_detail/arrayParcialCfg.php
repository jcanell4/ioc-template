<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::MEDIA_DETAIL_BUTTON
                                          ,"label" => "Detall"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "query" => "'lib/plugins/ajaxcommand/ajax.php?call=media'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                        )
                            )
              );

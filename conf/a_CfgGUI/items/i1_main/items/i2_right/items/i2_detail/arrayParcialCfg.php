<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::MEDIA_DETAIL_BUTTON
                                          ,"title" => "Detall"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=mediadetails'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconMediaDetail'"
                                        )
                            )
              );

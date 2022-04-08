<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::MEDIA_TORNAR_BUTTON
                                          ,"title" => "Tornar"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "urlBase" => "'lib/exe/ioc_ajax.php?call=mediadetails'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconExit'"
                                          ,'disableOnSend' => true
                                        )
                            )
              );

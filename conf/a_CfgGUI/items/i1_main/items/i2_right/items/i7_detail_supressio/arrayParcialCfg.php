<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::DETAIL_SUPRESSIO_BUTTON
                                          ,"title" => "Suprimeix"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "urlBase" => "'lib/exe/ioc_ajax.php?call=mediadetails'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconTrash'"
                                          ,'disableOnSend' => true
                                        )
                            )
              );

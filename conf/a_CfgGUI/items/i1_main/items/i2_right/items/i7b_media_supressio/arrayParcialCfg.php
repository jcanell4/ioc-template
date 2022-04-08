<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::MEDIA_SUPRESSIO_BUTTON
                                          ,"title" => "Suprimeix"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "urlBase" => "'lib/exe/ioc_ajax.php?'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconTrash'"
                                          ,'disableOnSend' => true
                                        )
                            )
              );

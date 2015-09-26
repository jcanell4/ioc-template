<?php
$arrParcial = array(
                 "class" => "WikiIocButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::MEDIA_UPLOAD_BUTTON
                                          ,"label" => "Upload"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                           "urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=mediadetails'"
                                          ,"autoSize" => true
                                          ,"visible" => false
                                        )
                            )
              );

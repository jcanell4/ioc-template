<?php
$arrParcial = array(
                "class" => "WikiIocHiddenDialog",
                "parms" => array(
                            "DOM" => array(
                      			"id" => cfgIdConstants::LOGIN_DIALOG
                                    ),
                            "DJO" => array(
                      			"urlBase" => "'lib/exe/ioc_ajax.php?call=login'",
                                        "standbyId" => "'loginDialog_hidden_container'"
                                    )
                          )
            );


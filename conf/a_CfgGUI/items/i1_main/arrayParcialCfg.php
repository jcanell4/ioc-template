<?php
$arrParcial = array(
                "class" => "WikiIocBorderContainer"
                ,"parms" => array(
                                "DOM" => array(
                                            "id" => cfgIdConstants::MAIN_CONTENT
                                        )
                                ,"CSS" => array(
                                           "height" => "100%"
                                          ,"width" => "100%"
                                          ,"min-width" => "1em"
                                          ,"min-height" => "1px"
                                          ,"z-index" => "0"
                                        )
                                ,"DJO" => array(
                                            "design" => "'sidebar'"
                                           ,"gutters" => 'true'
                                        )
                                ,"PRP" => array(
                                            "splitterClass" => "dojox/layout/ToggleSplitter"
                                           ,"extraCssFiles" => array("dojox/layout/resources/ToggleSplitter.css")
                                           ,"wrapped" => true
                                )
                            )
);
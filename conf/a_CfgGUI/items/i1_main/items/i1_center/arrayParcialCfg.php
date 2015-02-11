<?php
$arrParcial = array(
                  "class" => "WikiIocItemsPanelDiv"
                 ,"parms" => array(
                     "DOM" => array(
                        "id" => cfgIdConstants::CONTENT
                       ,"label" => "CentralPanel"
                       ,"region" => "center"
                       ,"class" => "ioc_content dokuwiki"
                       ,"doLayout" => "false"
                       ,"splitter" => "false"
                     )
                  )
                 ,"items" => array(
                      array(
                         "class" => "WikiIocTabsContainer"
                        ,"parms" => array(
                            "DOM" => array(
                               "id" => cfgIdConstants::BODY_CONTENT
                              ,"label" => "bodyContent"
                              ,"tabType" => cfgIdConstants::SCROLLING_TAB_TYPE
                              ,"useMenu" => true
                              ,"useSlider" => true
                            )
                         )
                      )
                  )
               );

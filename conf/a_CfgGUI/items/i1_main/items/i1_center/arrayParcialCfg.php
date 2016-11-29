<?php
$arrParcial = array(
                  "class" => "WikiIocItemsPanelDiv"
                 ,"parms" => array(
                     "DOM" => array(
                        "id" => cfgIdConstants::CONTENT
                       ,"label" => "CentralPanel"
                       ,"class" => "ioc_content dokuwiki"
                     )
                     ,"DJO" => array(
                        "region" => "'center'"
                       ,"doLayout" => "false"
                       ,"splitter" => "false"
//                       ,"toggleSplitterCollapsedSize" => "20px"                         
                     )
                     ,"CSS" => array(
                        "padding" => "0px"
                     )
                  )
                 ,"items" => array(
                      array(
                         "class" => "WikiIocTabsContainer"
                        ,"parms" => array(
                            "DOM" => array(
                               "id" => cfgIdConstants::BODY_CONTENT
                              ,"label" => "bodyContent"
                            )
                            ,"DJO" => array(
                               "useMenu" => true
                              ,"useSlider" => true
                            )
                            ,"PRP" => array(
                               "tabType" => cfgIdConstants::SCROLLING_TAB_TYPE
                            )
                         )
                      ),
        array(
            "class" => "WikiIocNotifierContainer",
            "parms" => array(
                "DOM" => array(
                    "id" => cfgIdConstants::SYSTEM_WARNING_CONTAINER,
                    "class" => "warning-container",
                    "style" => "position:absolute;bottom:5px;right:5px"
                ),
//                "CSS" => array( // ALERTA[Xavi] No funciona
//                    "position" => "absolute",
//                    "right" => "5px", // 35px per edició de textos
//                    "top" => "5px", // 63px per edició de textos
//                ),

                "DJO" => array(
                )
            )
        )

    )
);

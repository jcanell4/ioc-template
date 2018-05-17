<?php
$arrParcial = array(
    "class" => "WikiIocTreeContainer",
    "parms" => array(
        "DOM" => array(
            "id" => cfgIdConstants::TB_INDEX,
            "label" => "Índex"
        ),
        "DJO" => array(
            "treeDataSource" => "'lib/exe/ioc_ajaxrest.php/ns_tree_rest/'",
            "urlBase" => "'lib/exe/ioc_ajax.php?call=page'",
            "typeDictionary" => ["p" => [
                                        "urlBase" => "'lib/exe/ioc_ajax.php?call=project'",
                                        "params" => ['projectType','nsproject']
                                        ],
                                 "po" => [
                                        "urlBase" => "'lib/exe/ioc_ajax.php?call=project'",
                                        "params" => ['projectType','nsproject']
                                        ]
                                ]
            ,"expandProject" => "false"
            ,"processOnClickAndOpenOnClick" => "true"
            ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
        )
    )
);


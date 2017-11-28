<?php
$arrParcial = array(
    "class" => "WikiIocTreeContainer",
    "parms" => array(
        "DOM" => array(
            "id" => cfgIdConstants::TB_INDEX,
            "label" => "Índex"
        ),
        "DJO" => array(
            "treeDataSource" => "'ajaxrest.php/ns_tree_rest/'",
            "urlBase" => "'ajax.php?call=page'",
            "typeDictionary" => ["p" => [
                "urlBase" => "'ajax.php?call=project'",
                "params" => ['projectType']
            ]]

            /*,"urlBaseTyped" => array(
                                   "p" => "'ajax.php?call=commandreport'"
                                  ,"pf" => "'ajax.php?call=commandreport'"
                                )*/
            ,"expandProject" => "true"
            ,"processOnClickAndOpenOnClick" => "true"
            ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
        )
    )
);


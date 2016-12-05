<?php
$arrParcial = array(
    "class" => "WikiIocTreeContainer",
    "parms" => array(
        "DOM" => array(
            "id" => cfgIdConstants::TB_INDEX,
            "label" => "Índex"
        ),
        "DJO" => array(
            "treeDataSource" => "'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/'",
            "urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=page'",
            "typeDictionary" => ["p" => [
                "urlBase" => "'lib/plugins/ajaxcommand/ajax.php?call=project'",
                "params" => ['projectType']
            ]]

            /*,"urlBaseTyped" => array(
                                   "p" => "'lib/plugins/ajaxcommand/ajax.php?call=commandreport'"
                                  ,"pf" => "'lib/plugins/ajaxcommand/ajax.php?call=commandreport'"
                                )*/
            ,"expandProject" => "true"
            ,"processOnClickAndOpenOnClick" => "true"
            ,"standbyId" => cfgIdConstants::getConstantToString(cfgIdConstants::BODY_CONTENT)
        )
    )
);


<?php
$arrParcial = [
                "class" => "WikiIocMenuItem"
               ,"parms" => [
                            "DOM" => [
                                        "id" => cfgIdConstants::CREACIO_NOUS_USUARIS
                                       ,"title" => "Creació nous usuaris professors"
                                       ,"label" => "Creació nous usuaris professors"
                                     ]
                           ,"DJO" => [
                                        "iconClass" => "'dijitNoIcon'"
                                       ,"urlBase" => "'lib/exe/ioc_ajax.php?call=new_user_teachers'"
                                       ,"disabled" => false
                                     ]
                            ]
              ];
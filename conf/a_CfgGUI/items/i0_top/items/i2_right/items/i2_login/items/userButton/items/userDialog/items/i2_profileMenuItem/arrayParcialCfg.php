<?php
$arrParcial = array(
             'class' => "WikiIocMenuItem"
            ,'parms' => array(
                           'DOM' => array(
                                         'id' => cfgIdConstants::PROFILE_USER_MENU_ITEM
                                        ,'label' => "El meu perfil"
                                    )
                          ,'DJO' => array(
                                         'query' => "'id=profile'"
                                        ,'autoSize' => true
                                        ,'disabled' => false
                                        ,'urlBase' => "'lib/exe/ioc_ajax.php?call=profile'"
                                        ,'standbyId' => "'bodyContent'"
                                    )
                        )
            ,'hidden' => false
          );

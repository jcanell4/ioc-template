<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SAVE_PROJECT_BUTTON
                                          ,"title" => "Desar el Formulari del Projecte"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSave'"
                                          ,'getDataEventObject' => 'function(_data){var _ret=null; var id=this.dispatcher.getGlobalState().getCurrentId(); _ret={id:id, name:\'save_project\'}; return _ret;}'
                                        )
                            )
              );

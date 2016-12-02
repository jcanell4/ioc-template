<?php
$arrParcial = array(
                 "class" => "WikiEventButton"
                ,"parms" => array(
                               "DOM" => array(
                                           "id" => cfgIdConstants::SAVE_FORM_BUTTON
                                          ,"title" => "Desar Formulari"
                                          ,"class" => "iocDisplayBlock"
                                        )
                              ,"DJO" => array(
                                          "autoSize" => true
                                          ,"visible" => false
                                          ,'iconClass' => "'iocIconSave'"
                                          ,'getDataEventObject' => 'function(_data){var _ret=null; var id=this.dispatcher.getGlobalState().getCurrentId(); _ret={id:id, name:\'save_form\'}; return _ret;}'
                                        )
                            )
              );

<?php
$arrParcial = array(
                 "class" => "WikiRequestEventButton"
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
                                          ,"query" => "'do=save'"
                                          ,'getDataEventObject' => 'function(_data){var _ret=null; var globalState=this.dispatcher.getGlobalState(), id=globalState.getCurrentId(), metaDataSubSet=globalState.getContent(globalState.getCurrentId()).metaDataSubSet; _ret={id:id, metaDataSubSet:metaDataSubSet, name:\'save_project\'}; return _ret;}'
                                        )
                            )
              );

<?php
function iocNeedResetArrayGUI(){
  $_needReset = 0;
  return $_needReset;
}

function iocArrayGUI(){
$_arrIocCfgGUI = array (
  'class' => 'WikiIocBody',
  'parms' => 
  array (
    'DOM' => 
    array (
      'id' => 'main',
    ),
  ),
  'items' => 
  array (
    'i0_top' => 
    array (
      'class' => 'WikiIocDivBloc',
      'parms' => 
      array (
        'DOM' => 
        array (
          'id' => 'topBloc',
        ),
        'CSS' => 
        array (
          'height' => '55px',
          'width' => '100%',
        ),
      ),
      'items' => 
      array (
        'i0_left' => 
        array (
          'class' => 'WikiIocSpanBloc',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'zonaLogo',
            ),
            'CSS' => 
            array (
              'float' => 'left',
              'padding-left' => '5px',
              'width' => '240px',
              'height' => '50px',
              'z-index' => '900',
            ),
          ),
          'items' => 
          array (
            'logo' => 
            array (
              'class' => 'WikiIocImage',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'alt' => 'logo',
                ),
                'CSS' => 
                array (
                  'width' => '240px',
                  'height' => '50px',
                  'z-index' => '900',
                ),
                'PRP' => 
                array (
                  'src' => 'img/logo.png',
                ),
              ),
            ),
          ),
        ),
        'i1_center' => 
        array (
          'class' => 'WikiIocSpanBloc',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'zonaMenu',
            ),
            'CSS' => 
            array (
              'float' => 'left',
              'padding-top' => '5px',
              'padding-left' => '30px',
              'height' => '50px',
            ),
          ),
        ),
        'i2_right' => 
        array (
          'class' => 'WikiIocSpanBloc',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'zonaTopRight',
            ),
            'CSS' => 
            array (
              'float' => 'right',
              'height' => '50px',
            ),
          ),
          'items' => 
          array (
            'i2_login' => 
            array (
              'class' => 'WikiIocSpanBloc',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'zonaLogin',
                ),
                'CSS' => 
                array (
                  'height' => '50px',
                  'width' => '80px',
                ),
              ),
              'items' => 
              array (
                'loginButton' => 
                array (
                  'class' => 'WikiIocDropDownButton',
                  'parms' => 
                  array (
                    'DOM' => 
                    array (
                      'id' => 'loginButton',
                      'label' => 'Entrar',
                      'class' => 'iocDisplayBlock',
                    ),
                    'DJO' => 
                    array (
                      'autoSize' => false,
                      'visible' => true,
                    ),
                  ),
                  'items' => 
                  array (
                    'loginDialog' => 
                    array (
                      'class' => 'WikiIocHiddenDialog',
                      'parms' => 
                      array (
                        'DOM' => 
                        array (
                          'id' => 'loginDialog',
                        ),
                        'DJO' => 
                        array (
                          'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=login\'',
                          'standbyId' => '\'loginDialog_hidden_container\'',
                        ),
                      ),
                      'items' => 
                      array (
                        'name' => 
                        array (
                          'class' => 'WikiIocFormInputField',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'login_name',
                              'label' => 'Usuari:',
                              'name' => 'u',
                            ),
                          ),
                        ),
                        'pass' => 
                        array (
                          'class' => 'WikiIocFormInputField',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'login_pass',
                              'label' => 'Contrasenya:',
                              'name' => 'p',
                              'type' => 'password',
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
                'userButton' => 
                array (
                  'class' => 'WikiIocDropDownButton',
                  'parms' => 
                  array (
                    'DOM' => 
                    array (
                      'id' => 'userButton',
                      'label' => 'Menú User',
                      'class' => 'iocDisplayBlock',
                    ),
                    'DJO' => 
                    array (
                      'autoSize' => false,
                      'visible' => true,
                      'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=page\'',
                    ),
                  ),
                  'items' => 
                  array (
                    'userDialog' => 
                    array (
                      'class' => 'WikiIocDropDownMenu',
                      'parms' => 
                      array (
                        'DOM' => 
                        array (
                          'id' => 'userDialog',
                        ),
                        'DJO' => 
                        array (
                          'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=page\'',
                        ),
                      ),
                      'items' => 
                      array (
                        'i01_shortcutsMenuItem' => 
                        array (
                          'class' => 'WikiIocMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'shortcutsMenuItem',
                              'label' => 'Les meves dreceres',
                            ),
                            'DJO' => 
                            array (
                              'query' => '\'id=user\'',
                              'autoSize' => true,
                              'disabled' => false,
                              'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=page\'',
                              'standbyId' => '\'bodyContent\'',
                              'getQuery' => 'function(_data){var _ret=null; _ret = \'id=wiki:user:\' + this.dispatcher.getGlobalState().userId + \':dreceres\';return _ret;}',
                            ),
                          ),
                        ),
                        'i0_userMenuItem' => 
                        array (
                          'class' => 'WikiIocMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'userMenuItem',
                              'label' => 'La meva pàgina',
                            ),
                            'DJO' => 
                            array (
                              'query' => '\'id=user\'',
                              'autoSize' => true,
                              'disabled' => false,
                              'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=page\'',
                              'standbyId' => '\'bodyContent\'',
                              'getQuery' => 'function(_data){var _ret=null; _ret = \'id=wiki:user:\' + this.dispatcher.getGlobalState().userId + \':index\';return _ret;}',
                            ),
                          ),
                        ),
                        'i2_logoffMenuItem' => 
                        array (
                          'class' => 'WikiIocMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'logoffMenuItem',
                              'label' => 'Desconnectar',
                            ),
                            'DJO' => 
                            array (
                              'query' => '\'do=logoff\'',
                              'autoSize' => true,
                              'disabled' => false,
                              'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=login\'',
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ),
            'i3_noti' => 
            array (
              'class' => 'WikiIocSpanBloc',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'zonaNotification',
                ),
                'CSS' => 
                array (
                  'height' => '50px',
                  'width' => '100px',
                ),
              ),
              'items' => 
              array (
                'notiButton' => 
                array (
                  'class' => 'WikiIocNotifierButton',
                  'parms' => 
                  array (
                    'DOM' => 
                    array (
                      'id' => 'notifierButton',
                      'label' => '(0)',
                      'title' => 'Notificacions',
                      'class' => 'iocDisplayBlock',
                    ),
                    'DJO' => 
                    array (
                      'autoSize' => false,
                      'visible' => true,
                      'iconClass' => '\'iocIconInactiveAlarm\'',
                      'activeIconClass' => '\'iocIconActiveAlarm\'',
                      'displayBlock' => false,
                    ),
                  ),
                  'items' => 
                  array (
                    'notifierContainer' => 
                    array (
                      'class' => 'WikiIocNotifierContainer',
                      'parms' => 
                      array (
                        'DOM' => 
                        array (
                          'id' => 'notifierContainer',
                          'class' => 'notification-container',
                        ),
                        'DJO' => 
                        array (
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    ),
    'i1_main' => 
    array (
      'class' => 'WikiIocBorderContainer',
      'parms' => 
      array (
        'DOM' => 
        array (
          'id' => 'mainContent',
        ),
        'CSS' => 
        array (
          'height' => '100%',
          'width' => '100%',
          'min-width' => '1em',
          'min-height' => '1px',
          'z-index' => '0',
        ),
        'DJO' => 
        array (
          'design' => '\'sidebar\'',
          'gutters' => 'true',
        ),
        'PRP' => 
        array (
          'splitterClass' => 'dojox/layout/ToggleSplitter',
          'extraCssFiles' => 
          array (
            0 => 'dojox/layout/resources/ToggleSplitter.css',
          ),
          'wrapped' => true,
        ),
      ),
      'items' => 
      array (
        'i0_left' => 
        array (
          'class' => 'WikiIocItemsPanel',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'id_LeftPanel',
              'minSize' => '150',
            ),
            'CSS' => 
            array (
              'width' => '190px',
              'padding' => '0px',
            ),
            'DJO' => 
            array (
              'region' => '\'left\'',
              'splitter' => 'true',
              'onResize' => 'function(_data){var _ret=null; if(this.dispatcher.getGlobalState().login){ var user=this.dispatcher.getGlobalState().userId; this.dispatcher.almacenLocal.setUserLeftPanelSize(user,_data.size.w);}return _ret;}',
            ),
          ),
          'items' => 
          array (
            'i1_bc' => 
            array (
              'class' => 'WikiIocBorderContainer',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'leftBorderContainerPanel',
                ),
                'CSS' => 
                array (
                  'height' => '100%',
                  'width' => '100%',
                  'min-width' => '1em',
                  'min-height' => '1px',
                  'z-index' => '0',
                ),
                'DJO' => 
                array (
                  'design' => '\'sidebar\'',
                  'gutters' => 'true',
                ),
              ),
              'items' => 
              array (
                'i1_contentPaneTop' => 
                array (
                  'class' => 'WikiIocItemsPanel',
                  'parms' => 
                  array (
                    'DOM' => 
                    array (
                      'id' => 'left_top_panel',
                    ),
                    'CSS' => 
                    array (
                      'padding' => '0px',
                      'border' => '0px',
                    ),
                    'DJO' => 
                    array (
                      'region' => '\'center\'',
                      'doLayout' => 'true',
                      'splitter' => 'true',
                      'closable' => 'false',
                    ),
                    'PRP' => 
                    array (
                      'onResize' => false,
                    ),
                  ),
                  'items' => 
                  array (
                    'i0_zN' => 
                    array (
                      'class' => 'WikiIocDivBloc',
                      'parms' => 
                      array (
                        'DOM' => 
                        array (
                          'id' => 'tb_container',
                        ),
                        'CSS' => 
                        array (
                          'height' => '100%',
                          'padding' => '0px',
                        ),
                      ),
                      'items' => 
                      array (
                        'zonaNavegacio' => 
                        array (
                          'class' => 'WikiIocTabsContainer',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'zonaNavegacio',
                              'label' => 'tabsNavegacio',
                            ),
                            'DJO' => 
                            array (
                              'useMenu' => true,
                            ),
                            'PRP' => 
                            array (
                              'tabType' => 1,
                            ),
                          ),
                          'items' => 
                          array (
                            'i0_index' => 
                            array (
                              'class' => 'WikiIocTreeContainer',
                              'parms' => 
                              array (
                                'DOM' => 
                                array (
                                  'id' => 'tb_index',
                                  'label' => 'Índex',
                                ),
                                'DJO' => 
                                array (
                                  'treeDataSource' => '\'lib/plugins/ajaxcommand/ajaxrest.php/ns_tree_rest/\'',
                                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=page\'',
                                  'typeDictionary' => 
                                  array (
                                    'p' => 
                                    array (
                                      'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=project\'',
                                      'params' => 
                                      array (
                                        0 => 'projectType',
                                      ),
                                    ),
                                  ),
                                  'standbyId' => '\'bodyContent\'',
                                ),
                              ),
                            ),
                            'i1_docu' => 
                            array (
                              'class' => 'WikiIocContainerFromPage',
                              'parms' => 
                              array (
                                'DOM' => 
                                array (
                                  'id' => 'tb_docu',
                                  'label' => 'documentació',
                                ),
                                'DJO' => 
                                array (
                                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=page\'',
                                  'standbyId' => '\'bodyContent\'',
                                ),
                                'PRP' => 
                                array (
                                  'page' => ':wiki:navigation',
                                ),
                              ),
                            ),
                            'i2_menu' => 
                            array (
                              'class' => 'WikiDojoMenu',
                              'parms' => 
                              array (
                                'DOM' => 
                                array (
                                  'id' => 'tb_menu',
                                  'title' => 'menú',
                                ),
                                'DJO' => 
                                array (
                                  'contextMenuForWindow' => 'false',
                                  'activated' => 'true',
                                ),
                              ),
                              'items' => 
                              array (
                                'i1_menu1' => 
                                array (
                                  'class' => 'WikiIocMenuItem',
                                  'parms' => 
                                  array (
                                    'DOM' => 
                                    array (
                                      'id' => 'mediaManager',
                                      'label' => 'Media manager',
                                    ),
                                    'DJO' => 
                                    array (
                                      'iconClass' => '\'dijitNoIcon\'',
                                      'disabled' => false,
                                      'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=media\'',
                                    ),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
                'i2_contentPaneBottom' => 
                array (
                  'class' => 'WikiIocItemsPanel',
                  'parms' => 
                  array (
                    'DOM' => 
                    array (
                      'id' => 'left_bottom_panel',
                    ),
                    'CSS' => 
                    array (
                      'height' => '40%',
                    ),
                    'DJO' => 
                    array (
                      'region' => '\'bottom\'',
                      'doLayout' => 'true',
                      'splitter' => 'true',
                      'onResize' => 'function(_data){var _ret=null; if(this.dispatcher.getGlobalState().login){ var user=this.dispatcher.getGlobalState().userId; this.dispatcher.almacenLocal.setUserLeftBottomPanelSize(user,_data.size.h);}return _ret;}',
                    ),
                  ),
                  'items' => 
                  array (
                    'i1_zM' => 
                    array (
                      'class' => 'WikiIocDivBloc',
                      'parms' => 
                      array (
                        'DOM' => 
                        array (
                          'id' => 'zonaMetaInfoDiv',
                        ),
                        'CSS' => 
                        array (
                          'height' => '100%',
                        ),
                      ),
                      'items' => 
                      array (
                        'zonaMetainfo' => 
                        array (
                          'class' => 'WikiIocAccordionContainer',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'zonaMetaInfo',
                              'label' => 'ContainerMetaInfo',
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),
        ),
        'i1_center' => 
        array (
          'class' => 'WikiIocItemsPanelDiv',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'content',
              'label' => 'CentralPanel',
              'class' => 'ioc_content dokuwiki',
            ),
            'DJO' => 
            array (
              'region' => '\'center\'',
              'doLayout' => 'false',
              'splitter' => 'false',
            ),
            'CSS' => 
            array (
              'padding' => '0px',
            ),
          ),
          'items' => 
          array (
            0 => 
            array (
              'class' => 'WikiIocTabsContainer',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'bodyContent',
                  'label' => 'bodyContent',
                ),
                'DJO' => 
                array (
                  'useMenu' => true,
                  'useSlider' => true,
                ),
                'PRP' => 
                array (
                  'tabType' => 2,
                ),
              ),
            ),
            1 => 
            array (
              'class' => 'WikiIocWarningContainer',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'systemWarningContainer',
                  'class' => 'warning-container',
                ),
                'CSS' => 
                array (
                  'position' => 'absolute',
                  'right' => '5px',
                  'bottom' => '5px',
                ),
              ),
            ),
          ),
        ),
        'i2_right' => 
        array (
          'class' => 'WikiIocItemsPanel',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'zonaCanvi',
              'minSize' => '50',
              'class' => 'ioc_content dokuwiki',
            ),
            'CSS' => 
            array (
              'width' => '50px',
              'padding' => '0px',
            ),
            'DJO' => 
            array (
              'region' => '\'right\'',
              'doLayout' => 'true',
              'splitter' => 'false',
              'closable' => 'true',
              'onResize' => 'function(_data){var _ret=null; if(this.dispatcher.getGlobalState().login){ var user=this.dispatcher.getGlobalState().userId; this.dispatcher.almacenLocal.setUserRightPanelSize(user,_data.size.w);}return _ret;}',
            ),
          ),
          'items' => 
          array (
            'i0_new' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'newButton',
                  'title' => 'Nou',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=new\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconNew\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=new_page\'',
                  'dialogTitle' => '\'Nou Document\'',
                  'EspaideNomslabel' => '\'Espai de Noms\'',
                  'EspaideNomsplaceHolder' => '\'Espai de Noms\'',
                  'NouDocumentlabel' => '\'Nou Document\'',
                  'NouDocumentplaceHolder' => '\'Nou Document\'',
                  'labelButtonAcceptar' => '\'Acceptar\'',
                  'labelButtonCancellar' => '\'Cancel·lar\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var ns=this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; }}return _ret;}',
                ),
              ),
            ),
            'i1_save_form' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'saveFormButton',
                  'title' => 'Desar Formulari',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconSave\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; var id = this.dispatcher.getGlobalState().getCurrentId();_ret = { id: id, name: \'save_form\'};return _ret;}',
                ),
              ),
            ),
            'i1save' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'saveButton',
                  'title' => 'Desar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconSave\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; var id = this.dispatcher.getGlobalState().getCurrentId();_ret = { id: id, name: \'save\'};return _ret;}',
                ),
              ),
            ),
            'i2_detail' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaDetailButton',
                  'title' => 'Detall',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=mediadetails\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconMediaDetail\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';var node = this.dispatcher.getGlobalState().getDwPageUi().getElementParentNodeId(this.dispatcher.getGlobalState().getCurrentElementId(), \'DL\');if (node) { var elid = \'\'; if (typeof node === \'string\') { elid = node; } else { elid = node.title; } _ret = \'id=\' + elid + \'&image=\' + elid + \'&img=\' + elid + \'&do=media\';}return _ret;}',
                ),
              ),
            ),
            'i3_cancel' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'cancelButton',
                  'title' => 'Tornar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconClose\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; var id = this.dispatcher.getGlobalState().getCurrentId();_ret = { id: id, name: \'cancel\'};return _ret;}',
                ),
              ),
            ),
            'i4_edit' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'editButton',
                  'title' => 'Edició',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=edit\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconEdit\'',
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=edit\'',
                  'disableOnSend' => true,
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var globalState = this.dispatcher.getGlobalState(); var ns=globalState.getContent(globalState.currentTabId).ns; var rev = globalState.getCurrentContent().rev; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; } if (rev) { _ret+=\'&rev=\' + rev; } _ret+=this.dispatcher.getDraftManager().generateLastLocalDraftTimesParam(globalState.currentTabId)}return _ret;}',
                ),
              ),
            ),
            'i5_edparc' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'edparcButton',
                  'title' => 'Edició Parcial',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconPartialEdit\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; _ret = {};var id = this.dispatcher.getGlobalState().getCurrentId(), chunk = this.dispatcher.getGlobalState().getCurrentElementId();chunk = chunk.replace(id + \'_\', \'\');chunk = chunk.replace(\'container_\', \'\');_ret = { id: id, chunk: chunk, name: \'edit_partial\'};return _ret;}',
                ),
              ),
            ),
            'i5a_saveparc' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'saveparcButton',
                  'title' => 'Desar Parcial',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconSave\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; var id = this.dispatcher.getGlobalState().getCurrentId(), chunk = this.dispatcher.getGlobalState().getCurrentElementId();chunk = chunk.replace(id + \'_\', \'\');chunk = chunk.replace(\'container_\', \'\');_ret = { id: id, chunk: chunk, name: \'save_partial\'};return _ret;}',
                ),
              ),
            ),
            'i5b_cancelparc' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'cancelparcButton',
                  'title' => 'Tornar Parcial',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconClose\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; _ret = {};var id = this.dispatcher.getGlobalState().getCurrentId(), chunk = this.dispatcher.getGlobalState().getCurrentElementId();chunk = chunk.replace(id + \'_\', \'\');chunk = chunk.replace(\'container_\', \'\');_ret = { id: id, chunk: chunk, name: \'cancel_partial\'};return _ret;}',
                ),
              ),
            ),
            'i7_detail_supressio' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'detailSupressioButton',
                  'title' => 'Suprimeix',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=mediadetails\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconTrash\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var eldelete = this.dispatcher.getGlobalState().currentTabId; var ns = this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; var confirmar=confirm(\'Suprimiu aquesta entrada?\'); if (confirmar){ _ret=\'delete=\'+eldelete+\'&do=media&ns=\'+ns; }else{ _ret=\'id=\'+eldelete+\'&image=\'+eldelete+\'&img=\'+eldelete+\'&do=media\'; }}return _ret;}',
                ),
              ),
            ),
            'i7b_media_supressio' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaSupressioButton',
                  'title' => 'Suprimeix',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconTrash\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';var node = this.dispatcher.getGlobalState().getDwPageUi().getElementParentNodeId(this.dispatcher.getGlobalState().getCurrentElementId(), \'DL\');var ns = this.dispatcher.getGlobalState().getContent(this.dispatcher.getGlobalState().currentTabId)[\'ns\'];if (node) { var elid = \'\'; if (typeof node === \'string\') { elid = node; } else { elid = node.title; } var confirmar=confirm(\'Suprimiu aquesta entrada?\'); if (confirmar){ _ret = \'call=media&id=\' + elid + \'&image=\' + elid + \'&img=\' + elid + \'&delete=\' + elid + \'&ns=\' + ns + \'&do=media\'; }else{ _ret = \'call=media&ns=\' + ns + \'&do=media\'; }}return _ret;}',
                ),
              ),
            ),
            'i8_upload' => 
            array (
              'class' => 'WikiButtonToListen',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaUploadButton',
                  'title' => 'Obre la càrrega de fitxers',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconUpload\'',
                  'onClick' => 'function(_data){var _ret=null; _ret=\'\';require([\'dijit/registry\'], function(registry){ registry.byId(\'zonaMetaInfo\').selectChild(\'metaMediafileupload\'); /*TO DO [Josep] canviar per una constant*/});jQuery(\'#upload__file\').click();/*_ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var elid = this.dispatcher.getGlobalState().currentTabId; var ns = this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(dojo.query(\'input[type=radio][name=fileoptions]:checked\')[0] === undefined){ _ret=\'id=\' + elid + \'&ns=\' + ns + \'&do=media&versioupload=true\'; }else{ var list = dojo.query(\'input[type=radio][name=fileoptions]:checked\')[0].value; var sort = dojo.query(\'input[type=radio][name=filesort]:checked\')[0].value; _ret=\'id=\' + elid + \'&ns=\' + ns + \'&do=media&list=\'+list+\'&sort=\'+sort+\'&versioupload=true\'; } }*/return _ret;}',
                ),
              ),
            ),
            'i9_editmedia' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaEditButton',
                  'title' => 'Edició',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=mediadetails\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconMediaEdit\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var elid = this.dispatcher.getGlobalState().currentTabId; var ns = this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; _ret=\'image=\' + elid + \'&ns=\' + ns + \'&do=media&tab_details=edit&tab_files=files\';}return _ret;}',
                ),
              ),
            ),
            'i9a_tornar' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaTornarButton',
                  'title' => 'Tornar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=mediadetails\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconExit\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';var elid = this.dispatcher.getGlobalState().currentTabId;_ret = \'id=\' + elid + \'&image=\' + elid + \'&img=\' + elid + \'&do=media\';return _ret;}',
                ),
              ),
            ),
            'i9b_upload_detall' => 
            array (
              'class' => 'WikiButtonToListen',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaUpdateImageButton',
                  'title' => 'Obre la càrrega de fitxers',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconUpload\'',
                  'onClick' => 'function(_data){var _ret=null; _ret=\'\';var id = this.dispatcher.getGlobalState().getCurrentId();require([\'dijit/registry\'], function(registry){ registry.byId(\'zonaMetaInfo\').selectChild(id + \'_metaMediafileupload\'); /*TO DO [Josep] canviar per una constant*/});jQuery(document.getElementById(\'upload__file_\'+id)).click();/*_ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var elid = this.dispatcher.getGlobalState().currentTabId; var ns = this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(dojo.query(\'input[type=radio][name=fileoptions]:checked\')[0] === undefined){ _ret=\'id=\' + elid + \'&ns=\' + ns + \'&do=media&versioupload=true\'; }else{ var list = dojo.query(\'input[type=radio][name=fileoptions]:checked\')[0].value; var sort = dojo.query(\'input[type=radio][name=filesort]:checked\')[0].value; _ret=\'id=\' + elid + \'&ns=\' + ns + \'&do=media&list=\'+list+\'&sort=\'+sort+\'&versioupload=true\'; } }*/return _ret;}',
                ),
              ),
            ),
            'iA_generar_projecte' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'generateProjectButton',
                  'title' => 'Generar projecte',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=generate\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconUpload\'',
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=project\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';var gState=this.dispatcher.getGlobalState();var id=gState.getCurrentId();if (gState.currentTabId) var ns=gState.getContent(gState.currentTabId).ns;var query=this.query;require ([\'dijit/registry\'], function(registry) { if (id) { if (!ns) ns=id; var widget=registry.byId(id); var projectType=widget.getProjectType(); if(query){ _ret=query+\'&id=\'+ns+\'&projectType=\'+projectType; }else{ _ret=\'id=\'+ns+\'&projectType=\'+projectType; } }});return _ret;}',
                ),
              ),
            ),
          ),
        ),
        'i3_bottom' => 
        array (
          'class' => 'WikiIocItemsPanel',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'id_BottomPanel',
            ),
            'CSS' => 
            array (
              'height' => '60px',
            ),
            'DJO' => 
            array (
              'region' => '\'bottom\'',
              'doLayout' => 'false',
              'splitter' => 'true',
              'onResize' => 'function(_data){var _ret=null; if(this.dispatcher.getGlobalState().login){ var user=this.dispatcher.getGlobalState().userId; this.dispatcher.almacenLocal.setUserBottomPanelSize(user,_data.size.h);}return _ret;}',
            ),
          ),
          'items' => 
          array (
            0 => 
            array (
              'class' => 'WikiIocTextContentPane',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'zonaMissatges',
                ),
                'PRP' => 
                array (
                  'missatge' => 'estoy aquí',
                ),
              ),
            ),
          ),
        ),
      ),
    ),
  ),
);

return $_arrIocCfgGUI;
}
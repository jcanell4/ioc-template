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
                          'method' => '\'post\'',
                          'urlBase' => '\'lib/exe/ioc_ajax.php?call=login\'',
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
                      'urlBase' => '\'lib/exe/ioc_ajax.php?call=page\'',
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
                          'urlBase' => '\'lib/exe/ioc_ajax.php?call=page\'',
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
                              'autoSize' => true,
                              'disabled' => false,
                              'urlBase' => '\'lib/exe/ioc_ajax.php?call=page\'',
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
                              'urlBase' => '\'lib/exe/ioc_ajax.php?call=page\'',
                              'standbyId' => '\'bodyContent\'',
                              'getQuery' => 'function(_data){var _ret=null; _ret = \'id=wiki:user:\' + this.dispatcher.getGlobalState().userId + \':index\';return _ret;}',
                            ),
                          ),
                        ),
                        'i2_profileMenuItem' => 
                        array (
                          'class' => 'WikiIocMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'profileUserMenuItem',
                              'label' => 'El meu perfil',
                            ),
                            'DJO' => 
                            array (
                              'query' => '\'id=profile\'',
                              'autoSize' => true,
                              'disabled' => false,
                              'urlBase' => '\'lib/exe/ioc_ajax.php?call=profile\'',
                              'standbyId' => '\'bodyContent\'',
                              'getQuery' => 'function(_data){var _ret=null; _ret = \'page=usermanager&user=\' + this.dispatcher.getGlobalState().userId + \'&fn[edit][\' + this.dispatcher.getGlobalState().userId + \']=1\';return _ret;}',
                            ),
                          ),
                          'hidden' => false,
                        ),
                        'i3_logoffMenuItem' => 
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
                              'urlBase' => '\'lib/exe/ioc_ajax.php?call=login\'',
                              'getQuery' => 'function(_data){var _ret=null; var requiredPages = this.dispatcher.getGlobalState().getAllRequiredPagesNS();console.log(requiredPages.join(\',\'));_ret = \'do=logoff&unlock=\' + requiredPages.join(\',\') +\'\';return _ret;}',
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ),
            'i3_noti_inbox' => 
            array (
              'class' => 'WikiIocSpanBloc',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'zonaNotificationInbox',
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
                      'id' => 'notifierButtonInbox',
                      'label' => '(0)',
                      'title' => 'Notificacions rebudes',
                      'class' => 'iocDisplayBlock',
                    ),
                    'DJO' => 
                    array (
                      'mailbox' => '\'inbox\'',
                      'counter' => true,
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
                          'id' => 'notifierContainerInbox',
                          'class' => 'notification-container',
                        ),
                        'DJO' => 
                        array (
                          'name' => '\'inbox\'',
                        ),
                      ),
                    ),
                  ),
                ),
              ),
            ),
            'i3_noti_outbox' => 
            array (
              'class' => 'WikiIocSpanBloc',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'zonaNotificationOutbox',
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
                      'id' => 'notifierButtonOutbox',
                      'title' => 'Notificacions enviades',
                      'class' => 'iocDisplayBlock',
                    ),
                    'DJO' => 
                    array (
                      'mailbox' => '\'outbox\'',
                      'autoSize' => false,
                      'visible' => true,
                      'iconClass' => '\'iocIconOutbox\'',
                      'displayBlock' => false,
                    ),
                    'PRP' => 
                    array (
                      'mailbox' => 'outbox',
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
                          'id' => 'notifierContainerOutbox',
                          'class' => 'notification-container',
                        ),
                        'DJO' => 
                        array (
                          'name' => '\'outbox\'',
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
              'overflow' => 'hidden',
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
                                  'treeDataSource' => '\'lib/exe/ioc_ajaxrest.php/ns_tree_rest/\'',
                                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=page\'',
                                  'typeDictionary' => 
                                  array (
                                    'p' => 
                                    array (
                                      'urlBase' => '\'lib/exe/ioc_ajax.php?call=project\'',
                                      'params' => 
                                      array (
                                        0 => 'projectType',
                                        1 => 'nsproject',
                                      ),
                                    ),
                                    'po' => 
                                    array (
                                      'urlBase' => '\'lib/exe/ioc_ajax.php?call=project\'',
                                      'params' => 
                                      array (
                                        0 => 'projectType',
                                        1 => 'nsproject',
                                      ),
                                    ),
                                    'p#w' => 
                                    array (
                                      'urlBase' => 'lib/exe/ioc_ajax.php?call=project&do=workflow&action=view',
                                      'params' => 
                                      array (
                                        0 => 'projectType',
                                        1 => 'nsproject',
                                      ),
                                    ),
                                  ),
                                  'expandProject' => 'false',
                                  'processOnClickAndOpenOnClick' => 'function(_data){var _ret=null; _ret=_data===\'p\'||_data===\'po\';return _ret;}',
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
                                  'urlBase' => '\'lib/exe/ioc_ajax.php?\'',
                                  'defaultCall' => '\'call=print\'',
                                ),
                                'PRP' => 
                                array (
                                  'page' => 'wiki:navigation',
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
                                'i0_menu0' => 
                                array (
                                  'class' => 'WikiIocMenuItem',
                                  'parms' => 
                                  array (
                                    'DOM' => 
                                    array (
                                      'id' => 'canvisRecents',
                                      'label' => 'Canvis recents',
                                    ),
                                    'DJO' => 
                                    array (
                                      'iconClass' => '\'dijitNoIcon\'',
                                      'disabled' => false,
                                      'urlBase' => '\'lib/exe/ioc_ajax.php?call=recent\'',
                                    ),
                                  ),
                                  'hidden' => false,
                                ),
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
                                      'urlBase' => '\'lib/exe/ioc_ajax.php?call=media\'',
                                    ),
                                  ),
                                ),
                                'i2_menu2' => 
                                array (
                                  'class' => 'WikiIocMenuItem',
                                  'parms' => 
                                  array (
                                    'DOM' => 
                                    array (
                                      'id' => 'seleccioProjectes',
                                      'title' => 'Selecció de projectes',
                                      'label' => 'Selecció de projectes',
                                    ),
                                    'DJO' => 
                                    array (
                                      'iconClass' => '\'dijitNoIcon\'',
                                      'urlBase' => '\'lib/exe/ioc_ajax.php?call=supplies_form\'',
                                      'disabled' => false,
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
                  'urlBase' => '\'lib/exe/ioc_ajax.php\'',
                  'urlListProjects' => '\'lib/exe/ioc_ajaxrest.php/list_projects_rest/\'',
                  'urlListTemplates' => '\'lib/exe/ioc_ajaxrest.php/list_templates_rest/\'',
                  'dialogTitle' => '\'Nou Document\'',
                  'EspaideNomslabel' => '\'Espai de Noms\'',
                  'EspaideNomsplaceHolder' => '\'Espai de Noms\'',
                  'Projecteslabel' => '\'Selecció del tipus de projecte\'',
                  'ProjectesplaceHolder' => '\'Selecció del tipus de projecte\'',
                  'NouProjectelabel' => '\'Nom del nou Projecte\'',
                  'NouProjecteplaceHolder' => '\'Nom del nou Projecte\'',
                  'Templateslabel' => '\'Selecció de la plantilla\'',
                  'TemplatesplaceHolder' => '\'Selecció de la plantilla\'',
                  'NouDocumentlabel' => '\'Nom del nou Document\'',
                  'NouDocumentplaceHolder' => '\'Nom del nou Document\'',
                  'NumUnitatslabel' => '\'Indica el nombre d\\\'unitats\'',
                  'NumUnitatsplaceHolder' => '\'nombre d\\\'unitats\'',
                  'labelButtonNumUnitats' => '\'mostra taula\'',
                  'labelButtonAcceptar' => '\'Acceptar\'',
                  'labelButtonCancellar' => '\'Cancel·lar\'',
                  'standbyId' => 'bodyContent',
                  'disableOnSend' => true,
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var ns=this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; }}return _ret;}',
                ),
              ),
            ),
            'i0a_rename' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'renameFolderButton',
                  'title' => 'Rename Folder',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=new\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconRenameFolder\'',
                  'urlBase' => '\'lib/exe/ioc_ajax.php\'',
                  'dialogTitle' => '\'Canviar de nom o moure una carpeta\'',
                  'DirectoriOrigenlabel' => '\'Carpeta origen\'',
                  'DirectoriOrigenplaceHolder' => '\'Selecciona la carpeta a l\\\'arbre\'',
                  'DirectoriDestilabel' => '\'Carpeta destí (no és obligatori)\'',
                  'DirectoriDestiplaceHolder' => '\'Carpeta destí\'',
                  'NouNomCarpetalabel' => '\'Nou nom de la caprpeta\'',
                  'NouNomCarpetaplaceHolder' => '\'Nou nom de la carpeta\'',
                  'labelButtonAcceptar' => '\'Acceptar\'',
                  'labelButtonCancellar' => '\'Cancel·lar\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var ns=this.dispatcher.getGlobalState().getContent(this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; }}return _ret;}',
                ),
              ),
            ),
            'i1_save_project' => 
            array (
              'class' => 'WikiRequestEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'saveProjectButton',
                  'title' => 'Desar el Formulari del Projecte',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconSave\'',
                  'query' => '\'do=save\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; var globalState=this.dispatcher.getGlobalState();var id = globalState.getCurrentId();var metaDataSubSet=globalState.getContent(globalState.getCurrentId()).metaDataSubSet;_ret = { id: id, metaDataSubSet: metaDataSubSet, name: \'save_project\'};return _ret;}',
                ),
              ),
            ),
            'i1a_revert' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'revertButton',
                  'title' => 'Revertir',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconUndo\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; var globalState = this.dispatcher.getGlobalState();var id = globalState.getCurrentId();var ns=globalState.getContent(id).ns;var pType = globalState.getContent(id).projectType;var projectOwner = globalState.getContent(id).projectOwner;var projectSourceType = globalState.getContent(id).projectSourceType;_ret = { id: id, name: \'save\', extraDataToSend: {do:\'save_rev\', ns:ns, projectType:pType, projectOwner:projectOwner, projectSourceType:projectSourceType }};return _ret;}',
                ),
              ),
            ),
            'i1b_revert_project' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'revertProjectButton',
                  'title' => 'Revertir Projecte',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconUndo\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; var globalState = this.dispatcher.getGlobalState();var id = globalState.getCurrentId();var ns = globalState.getContent(globalState.currentTabId)[\'ns\'];var pType = globalState.getContent(id).projectType;var metaDataSubSet = globalState.getContent(id).metaDataSubSet;var rev = globalState.getContent(id).rev;_ret = { id: id, name: \'revert_project\', dataToSend: {id: ns, projectType: pType, metaDataSubSet: metaDataSubSet, rev: rev}};return _ret;}',
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
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=mediadetails\'',
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
                  'getDataEventObject' => 'function(_data){var _ret=null; var globalState = this.dispatcher.getGlobalState();var id = globalState.getCurrentId();_ret = { id: id, name: \'cancel\', dataToSend: {keep_draft: false},};var contentCache = globalState.getContent(globalState.currentTabId);if (contentCache.projectOwner && contentCache.projectOwner !== \'undefined\') { _ret[\'projectOwner\'] = contentCache.projectOwner; _ret[\'projectSourceType\'] = contentCache.projectSourceType;};return _ret;}',
                ),
              ),
            ),
            'i3_cancel_project' => 
            array (
              'class' => 'WikiRequestEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'cancelProjectButton',
                  'title' => 'Cancel·lar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconClose\'',
                  'query' => '\'do=cancel\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; var globalState = this.dispatcher.getGlobalState();var id = globalState.getCurrentId();var pType = globalState.getContent(id).projectType;_ret = { id: id, name: \'cancel_project\', projectType: pType, dataToSend: {keep_draft: false}};return _ret;}',
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
                  'standbyId' => 'bodyContent',
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=edit\'',
                  'disableOnSend' => true,
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var globalState = this.dispatcher.getGlobalState(); var ns=globalState.getContent(globalState.currentTabId).ns; var rev = globalState.getCurrentContent().rev; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; } if (rev) { _ret+=\'&rev=\' + rev; } _ret+=this.dispatcher.getDraftManager().generateLastLocalDraftTimesParam(globalState.currentTabId, ns); _ret+=\'&editorType=\' + globalState.userState[\'editor\']; if (globalState.getContent(globalState.currentTabId).projectOwner) { _ret+=\'&projectOwner=\' + globalState.getContent(globalState.currentTabId).projectOwner; _ret+=\'&projectSourceType=\' + globalState.getContent(globalState.currentTabId).projectSourceType; }};return _ret;}',
                ),
              ),
            ),
            'i4_edit_project' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'editProjectButton',
                  'title' => 'Edició',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=edit\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconEdit\'',
                  'standbyId' => 'bodyContent',
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=project\'',
                  'disableOnSend' => true,
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var globalState=this.dispatcher.getGlobalState(); var ns=globalState.getContent(globalState.currentTabId).ns; var rev=globalState.getCurrentContent().rev; var projectType=globalState.getContent(globalState.getCurrentId()).projectType; var metaDataSubSet=globalState.getContent(globalState.getCurrentId()).metaDataSubSet; if (!metaDataSubSet) metaDataSubSet=globalState.getContent(globalState.currentTabId).metaDataSubSet; var hasDraft; var localDraft=this.dispatcher.getDraftManager().getContentLocalDraft(ns,metaDataSubSet); if(this.query) _ret=this.query+\'&\'; _ret+=\'id=\'+ns+\'&projectType=\'+projectType; if(metaDataSubSet) _ret+=\'&metaDataSubSet=\'+metaDataSubSet; if(localDraft && localDraft.project && localDraft.project.metaDataSubSet===metaDataSubSet) _ret+=\'&hasDraft=true\'; _ret+=this.dispatcher.getDraftManager().generateLastLocalDraftTimesParam(globalState.currentTabId, ns); if(rev) _ret+=\'&rev=\'+rev; _ret+=\'&editorType=\'+globalState.userState[\'editor\'];};return _ret;}',
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
                  'getDataEventObject' => 'function(_data){var _ret=null; _ret = {};var id = this.dispatcher.getGlobalState().getCurrentId(), chunk = this.dispatcher.getGlobalState().getCurrentElementId();chunk = chunk.replace(id + \'_\', \'\');chunk = chunk.replace(\'container_\', \'\');_ret = { id: id, chunk: chunk, name: \'cancel_partial\', keep_draft: false};return _ret;}',
                ),
              ),
            ),
            'i6_print' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'printButton',
                  'title' => 'Versió per imprimir',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconPreviewPrint\'',
                  'urlBase' => '\'lib/exe/ioc_ajax.php\'',
                  'method' => '\'post\'',
                  'standbyId' => 'bodyContent',
                  'getPostData' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var id = this.dispatcher.getGlobalState().currentTabId; var ns=this.dispatcher.getGlobalState().getContent(id)[\'ns\']; var rev = this.dispatcher.getGlobalState().getCurrentContent().rev; var hasChanges; if(this.dispatcher.getGlobalState().getCurrentContent().action===\'edit\' && this.dispatcher.getChangesManager().isContentChanged(id)){ hasChanges = 1; }else if(this.dispatcher.getGlobalState().getCurrentContent().action===\'sec_edit\' && this.dispatcher.getChangesManager().isChanged(id)){ hasChanges = 2; }else{ hasChanges = 0; } if(hasChanges==1){ _ret={ call:\'preview\', id: ns, wikitext:this.dispatcher.getWidget(id).getQuerySave().wikitext }; /*console.log(\'call:preview, id:\'+ns+\'wikitext:\' + _ret.wikitext);*/ }else if(hasChanges==2){ var editor =this.dispatcher.getWidget(id); var queryValues = editor.getEditedText(editor.getCurrentHeaderId()); _ret={ call:\'preview\', id: ns, wikitext:queryValues.prefix+queryValues.wikitext+queryValues.suffix }; /*console.log(\'call:preview, id:\'+ns+\'wikitext:\' + _ret.wikitext);*/ }else{ _ret={ call:\'print\', id: ns, }; /*console.log(\'call:print, id:\'+ns);*/ } if (rev) { _ret.rev = rev; }}return _ret;}',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';/*if (this.dispatcher.getGlobalState().currentTabId) { var id = this.dispatcher.getGlobalState().currentTabId; var ns=this.dispatcher.getGlobalState().getContent(id)[\'ns\']; var rev = this.dispatcher.getGlobalState().getCurrentContent().rev; var hasChanges; if(this.dispatcher.getGlobalState().getCurrentContent().action===\'edit\' && this.dispatcher.getChangesManager().isContentChanged(id)){ hasChanges = 1; }else if(this.dispatcher.getGlobalState().getCurrentContent().action===\'sec_edit\' && this.dispatcher.getChangesManager().isContentChanged(id)){ hasChanges = 2; }else{ hasChanges = 0; } if(hasChanges==1){ _ret=\'call=preview&id=\' + ns +\'&wikitext=\'+this.dispatcher.getWidget(id).getQuerySave().wikitext; }else if(hasChanges==2){ var currentSection = this.dispatcher.getGlobalState().getCurrentElementId(); var queryValues = this.dispatcher.getWidget(id).getQuerySave(currentSection); _ret=\'call=preview&id=\' + ns +\'&wikitext=\'+queryValues.prefix+queryValues.wikitext+queryValues.suffix; }else{ _ret=\'call=print&id=\' + ns; } if(this.query){ _ret+=\'&\'+this.query; } if (rev) { _ret+=\'&rev=\' + rev; }}*/if(this.query){ _ret=this.query;}return _ret;}',
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
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=mediadetails\'',
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
                  'urlBase' => '\'lib/exe/ioc_ajax.php?\'',
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
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=mediadetails\'',
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
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=mediadetails\'',
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
                  'iconClass' => '\'iocIconFactory\'',
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=project\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';var gState=this.dispatcher.getGlobalState();var id=gState.getCurrentId();if (id) { var ns=id; if (gState.currentTabId) ns=gState.getContent(gState.currentTabId).ns; var projectType=gState.getContent(id).projectType; if(this.query){ _ret=this.query+\'&\'; } _ret+=\'id=\'+ns+\'&projectType=\'+projectType;}return _ret;}',
                ),
              ),
            ),
            'iX0_send_message' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'sendMessageButton',
                  'title' => 'Enviar missatge',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'call' => '\'send_message\'',
                  'parent' => '\'selected_projects\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconSendMessage\'',
                  'urlBase' => '\'lib/exe/ioc_ajax.php\'',
                  'urlListRols' => '\'lib/exe/ioc_ajaxrest.php/list_rols_rest/\'',
                  'dialogTitle' => '\'Enviar un missatge\'',
                  'labelRols' => '\'Selecciona els rols dels destinataris\'',
                  'placeholderRols' => '\'Rols destinataris\'',
                  'labelLlista' => '\'Llista de rols seleccionats\'',
                  'labelMissatge' => '\'Missatge pels destinataris\'',
                  'placeholderMissatge' => '\'Escriu un missatge\'',
                  'labelButtonAcceptar' => '\'Enviar\'',
                  'labelButtonCancellar' => '\'Cancel·lar\'',
                  'standbyId' => 'bodyContent',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var ns=this.dispatcher.getGlobalState().getContent(this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; }}return _ret;}',
                ),
              ),
            ),
            'iY_ftp_send' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'ftpSendButton',
                  'title' => 'Envia fitxers per FTP',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=ftpsend\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconUpload\'',
                  'standbyId' => 'bodyContent',
                  'hasTimer' => true,
                  'disableOnSend' => true,
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=ftpsend\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var globalState = this.dispatcher.getGlobalState(); var ns = globalState.getContent(globalState.currentTabId).ns; var pType = globalState.getContent(globalState.currentTabId).projectType; var pMoodleToken = globalState.getUserState(\'moodleToken\'); _ret=(this.query) ? this.query + \'&id=\'+ns : \'id=\'+ns; if (pType && pType!==\'\' && pType!==undefined) _ret+=\'&projectType=\'+pType; if (pMoodleToken && pMoodleToken!==\'\' && pMoodleToken!==undefined) _ret+=\'&moodleToken=\'+pMoodleToken; }return _ret;}',
                ),
              ),
            ),
            'iYa_ftp_project' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'ftpProjectButton',
                  'title' => 'Envia fitxers de projecte via FTP',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=ftp_project\'',
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconUpload\'',
                  'standbyId' => 'bodyContent',
                  'hasTimer' => true,
                  'disableOnSend' => true,
                  'urlBase' => '\'lib/exe/ioc_ajax.php?call=project\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';var globalState = this.dispatcher.getGlobalState();if (globalState.currentTabId) { var ns = globalState.getContent(globalState.currentTabId).ns; _ret=(this.query) ? this.query + \'&id=\'+ns : \'id=\'+ns; var pType = globalState.getContent(globalState.currentTabId).projectType; if (pType && pType!==\'\' && pType!==undefined) _ret+=\'&projectType=\'+pType; var pMoodleToken = globalState.getUserState(\'moodleToken\'); if (pMoodleToken && pMoodleToken!==\'\' && pMoodleToken!==undefined) _ret+=\'&moodleToken=\'+pMoodleToken;}return _ret;}',
                ),
              ),
            ),
            'iZ0_rename_project' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'renameProjectButton',
                  'title' => 'Canviar nom',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconRenameProject\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; if (this.newname){ var globalState=this.dispatcher.getGlobalState(); var id=globalState.getCurrentId(); var ns=globalState.getContent(globalState.currentTabId)[\'ns\']; var pType = globalState.getContent(id).projectType; var project = (this.do) ? this.do : \'rename_project\'; var action = (this.action) ? this.action : \'\'; _ret = { id: id, name: \'rename_project\', dataToSend: {id: ns, projectType: pType, newname: this.newname, do: project, action: action} };}return _ret;}',
                  'onClick' => 'function(_data){var _ret=null; var normalitzaCaracters = dojo.require(\'ioc/functions/normalitzaCaracters\');if (this.dispatcher.getGlobalState().currentTabId) { this.newname = prompt(\'Escriu el nou nom pel projecte\'); this.newname = normalitzaCaracters(this.newname);};return _ret;}',
                ),
              ),
            ),
            'iZ1_duplicate_project' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'duplicateProjectButton',
                  'title' => 'Duplicar projecte',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconDuplicateProject\'',
                  'query' => '\'do=duplicate_project\'',
                  'urlBase' => '\'lib/exe/ioc_ajax.php\'',
                  'dialogTitle' => '\'Duplicar un projecte\'',
                  'EspaideNomslabel' => '\'Escriu el nou Espai de Noms\'',
                  'EspaideNomsplaceHolder' => '\'Espai de Noms\'',
                  'NouProjectlabel' => '\'Escriu el nom del nou Projecte\'',
                  'NouProjectplaceHolder' => '\'Nom del nou Projecte\'',
                  'labelButtonAcceptar' => '\'Crear\'',
                  'labelButtonCancellar' => '\'Cancel·lar\'',
                  'getQuery' => 'function(_data){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var ns=this.dispatcher.getGlobalState().getContent(this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; }}return _ret;}',
                ),
              ),
            ),
            'iZ9_remove_project' => 
            array (
              'class' => 'WikiEventButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'removeProjectButton',
                  'title' => 'Eliminar projecte',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'autoSize' => true,
                  'visible' => false,
                  'iconClass' => '\'iocIconRemoveProject\'',
                  'getDataEventObject' => 'function(_data){var _ret=null; if (this.ok==true){ var globalState=this.dispatcher.getGlobalState(); var id=globalState.getCurrentId(); var ns=globalState.getContent(globalState.currentTabId)[\'ns\']; var pType=globalState.getContent(id).projectType; var userId=globalState.userId; _ret = { id: id, name: \'remove_project\', dataToSend: {id: ns, projectType: pType, user_id: userId} };}return _ret;}',
                  'onClick' => 'function(_data){var _ret=null; var globalState=this.dispatcher.getGlobalState();if (globalState.currentTabId) { var id=globalState.getCurrentId(); this.ok=confirm(\'Vols eliminar el projecte\\n\\n\\t\\\'\'+globalState.getContent(id).ns+\'\\\'?\');}return _ret;}',
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
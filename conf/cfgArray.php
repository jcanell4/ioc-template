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
        'i0_logo' => 
        array (
          'class' => 'WikiIocImage',
          'parms' => 
          array (
            'CSS' => 
            array (
              'position' => 'absolute',
              'top' => '2px',
              'left' => '0px',
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
        'i1_menu' => 
        array (
          'class' => 'WikiDojoToolBar',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'barraMenu',
              'label' => 'BarraMenu',
            ),
            'CSS' => 
            array (
              'position' => 'fixed',
              'top' => '28px',
              'left' => '270px',
              'z-index' => '900',
            ),
          ),
          'items' => 
          array (
            'i0_menu_edicio' => 
            array (
              'class' => 'WikiDojoButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'menu_edicio',
                  'label' => 'EDICIÓ',
                  'class' => 'dijitInline',
                ),
                'DJO' => 
                array (
                  'visible' => true,
                  'onClick' => 'function(){var _ret=null; alert(\'hola soc onclick de menu_edicio\');return _ret;}',
                ),
              ),
            ),
            'i1_menu_vista' => 
            array (
              'class' => 'WikiDojoButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'menu_vista',
                  'label' => 'VISTA',
                  'class' => 'dijitInline',
                ),
                'DJO' => 
                array (
                  'visible' => true,
                  'onClick' => 'function(){var _ret=null; alert(\'hola soc onclick de menu_vista\');return _ret;}',
                ),
              ),
            ),
            'i2_menu_correccio' => 
            array (
              'class' => 'WikiDojoButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'menu_correccio',
                  'label' => 'CORRECCIÓ',
                  'class' => 'dijitInline',
                ),
                'DJO' => 
                array (
                  'visible' => true,
                  'onClick' => 'function(){var _ret=null; alert(\'hola soc onclick de menu_correccio amb la constant cfgIdConstants::TOPBLOC\');return _ret;}',
                ),
              ),
            ),
          ),
        ),
        'i2_login' => 
        array (
          'class' => 'WikiIocDivBloc',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'zonaLogin',
            ),
            'CSS' => 
            array (
              'width' => '80px',
              'height' => '60px',
              'float' => 'right',
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
                  'autoSize' => true,
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
                  'autoSize' => true,
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
                    'i0_userMenuItem' => 
                    array (
                      'class' => 'WikiIocMenuItemButton',
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
                          'getQuery' => 'function(){var _ret=null; _ret = \'id=wiki:user:\' + this.dispatcher.getGlobalState().userId;return _ret;}',
                        ),
                      ),
                    ),
                    'i1_talkUserMenuItem' => 
                    array (
                      'class' => 'WikiIocMenuItemButton',
                      'parms' => 
                      array (
                        'DOM' => 
                        array (
                          'id' => 'talkUserMenuItem',
                          'label' => 'Discussió',
                        ),
                        'DJO' => 
                        array (
                          'query' => '\'id=talkUser\'',
                          'autoSize' => true,
                          'disabled' => false,
                          'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=page\'',
                          'standbyId' => '\'bodyContent\'',
                          'getQuery' => 'function(){var _ret=null; _ret = \'id=talk:wiki:user:\' + this.dispatcher.getGlobalState().userId;return _ret;}',
                        ),
                      ),
                    ),
                    'i2_logoffMenuItem' => 
                    array (
                      'class' => 'WikiIocMenuItemButton',
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
              'region' => 'left',
              'doLayout' => 'true',
              'splitter' => 'true',
              'minSize' => '150',
              'closable' => 'false',
            ),
            'CSS' => 
            array (
              'width' => '190px',
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
                  'height' => '40%',
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
                      'tabType' => 1,
                      'useMenu' => true,
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
                        'i0_menu0' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'menu0',
                              'label' => 'menú-0',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitEditorIcon dijitEditorIconCut\'',
                              'onClick' => 'function(){var _ret=null; alert(\'hola soc onclick de menu 0\');return _ret;}',
                            ),
                          ),
                        ),
                        'i1_menu1' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'menu1',
                              'label' => 'menú-1 sin-Icono',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitNoIcon\'',
                              'onClick' => 'function(){var _ret=null; alert(\'hola soc onclick de menu 1\');return _ret;}',
                            ),
                          ),
                        ),
                        'i2_separador' => 
                        array (
                          'class' => 'WikiDojoMenuSeparator',
                          'parms' => 
                          array (
                          ),
                        ),
                        'i3_menu2' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'menu2',
                              'label' => 'menú-2',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitEditorIcon dijitEditorIconPaste\'',
                              'onClick' => 'function(){var _ret=null; alert(\'hola soc onclick de menu 2\');return _ret;}',
                            ),
                            'PRP' => 
                            array (
                              'MenuSeparator' => 1,
                            ),
                          ),
                        ),
                        'i4_submenu' => 
                        array (
                          'class' => 'WikiDojoSubMenu',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'submenu',
                              'label' => 'SubMenú',
                            ),
                          ),
                          'items' => 
                          array (
                            'i0_menu0' => 
                            array (
                              'class' => 'WikiDojoMenuItem',
                              'parms' => 
                              array (
                                'DOM' => 
                                array (
                                  'id' => 'submenu_menu0',
                                  'label' => 'submenú-menú-0',
                                ),
                                'DJO' => 
                                array (
                                  'iconClass' => '\'dijitEditorIcon dijitEditorIconCut\'',
                                  'onClick' => 'function(){var _ret=null; alert(\'hola soc onClick del menú 0 del submenú\');return _ret;}',
                                ),
                              ),
                            ),
                          ),
                        ),
                        'i5_separador' => 
                        array (
                          'class' => 'WikiDojoMenuSeparator',
                          'parms' => 
                          array (
                          ),
                        ),
                        'i6_iocmenuitem' => 
                        array (
                          'class' => 'WikiIocMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'ioc_menu6',
                              'label' => 'IocMenuItem = page',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitNoIcon\'',
                              'disabled' => false,
                              'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=page\'',
                            ),
                          ),
                        ),
                        'i7_iocmenuitem' => 
                        array (
                          'class' => 'WikiIocMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'ioc_menu7',
                              'label' => 'IocMenuItem = logoff',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitNoIcon\'',
                              'disabled' => false,
                              'query' => '\'do=logoff\'',
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
                  'height' => '60%',
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
        'i1_center' => 
        array (
          'class' => 'WikiIocItemsPanelDiv',
          'parms' => 
          array (
            'DOM' => 
            array (
              'id' => 'content',
              'label' => 'CentralPanel',
              'region' => 'center',
              'class' => 'ioc_content dokuwiki',
              'doLayout' => 'false',
              'splitter' => 'false',
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
                  'tabType' => 2,
                  'useMenu' => true,
                  'useSlider' => true,
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
              'region' => 'right',
              'doLayout' => 'true',
              'splitter' => 'true',
              'minSize' => '50',
              'closable' => 'true',
            ),
            'CSS' => 
            array (
              'width' => '65px',
              'padding' => '0px',
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
                  'label' => 'Nou',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=new\'',
                  'autoSize' => true,
                  'visible' => false,
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=new_page\'',
                  'dialogTitle' => '\'Nou Document\'',
                  'EspaideNomslabel' => '\'Espai de Noms\'',
                  'EspaideNomsplaceHolder' => '\'Espai de Noms\'',
                  'NouDocumentlabel' => '\'Nou Document\'',
                  'NouDocumentplaceHolder' => '\'Nou Document\'',
                  'labelButtonAcceptar' => '\'Acceptar\'',
                  'labelButtonCancellar' => '\'Cancel·lar\'',
                  'getQuery' => 'function(){var _ret=null; var ns=this.dispatcher.getGlobalState().pages[ this.dispatcher.getGlobalState().currentTabId][\'ns\'];if(this.query){ _ret=this.query + \'&id=\' + ns;}else{ _ret=\'id=\' + ns;}return _ret;}',
                ),
              ),
            ),
            'i1_save' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'saveButton',
                  'label' => 'Desar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=save\'',
                  'autoSize' => true,
                  'visible' => false,
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=save\'',
                  'getPostData' => 'function(){var _ret=null; require([\'dojo/dom-form\'],function(domForm){ _ret=domForm.toObject(\'dw__editform\');});return _ret;}',
                  'getQuery' => 'function(){var _ret=null; var ns=this.dispatcher.getGlobalState().pages[ this.dispatcher.getGlobalState().currentTabId][\'ns\'];if(this.query){ _ret=this.query + \'&id=\' + ns;}else{ _ret=\'id=\' + ns;}return _ret;}',
                ),
              ),
            ),
            'i2_preview' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'previewButton',
                  'label' => 'Previsualitza',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=preview\'',
                  'autoSize' => true,
                  'visible' => false,
                ),
              ),
            ),
            'i3_detail' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaDetailButton',
                  'label' => 'Detall',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'lib/plugins/ajaxcommand/ajax.php?call=media\'',
                  'autoSize' => true,
                  'visible' => false,
                ),
              ),
            ),
            'i4_cancel' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'cancelButton',
                  'label' => 'Cancel·la',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=cancel\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=cancel\'',
                  'getQuery' => 'function(){var _ret=null; var ns=this.dispatcher.getGlobalState().pages[ this.dispatcher.getGlobalState().currentTabId][\'ns\'];if(this.query){ _ret=this.query + \'&id=\' + ns;}else{ _ret=\'id=\' + ns;}return _ret;}',
                ),
              ),
            ),
            'i5_edit' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'editButton',
                  'label' => 'Edició',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=edit\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=edit\'',
                  'getQuery' => 'function(){var _ret=null; var ns=this.dispatcher.getGlobalState().pages[ this.dispatcher.getGlobalState().currentTabId][\'ns\'];var rev = this.dispatcher.getGlobalState().getCurrentContent().rev;if(this.query){ _ret=this.query + \'&id=\' + ns;}else{ _ret=\'id=\' + ns;}if (rev) { _ret+=\'&rev=\' + rev;}return _ret;}',
                ),
              ),
            ),
            'i6_edparc' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'edparcButton',
                  'label' => 'Ed. Parc.',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=edparc\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=edit\'',
                  'getQuery' => 'function(){var _ret=null; var self = this;require([\'ioc/dokuwiki/dwPageUi\'], function(dwPageUi){ var q=dwPageUi.getFormQueryToEditSection( self.dispatcher.getGlobalState().getCurrentSectionId()); if(self.query){ _ret=self.query + \'&\' + q; }else{ _ret=q; }});return _ret;}',
                ),
              ),
            ),
            'i7_exit' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'exitButton',
                  'label' => 'Sortir',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=logoff\'',
                  'autoSize' => true,
                  'visible' => false,
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=login\'',
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
              'region' => 'bottom',
              'doLayout' => 'false',
              'splitter' => 'true',
            ),
            'CSS' => 
            array (
              'height' => '60px',
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
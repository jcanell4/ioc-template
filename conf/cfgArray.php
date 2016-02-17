<?php
function iocNeedResetArrayGUI(){
  $_needReset = 1;
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
          'items' => 
          array (
            'menu' => 
            array (
              'class' => 'WikiDojoMenuBar',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'barraMenu',
                  'label' => 'BarraDeMenu',
                ),
              ),
              'items' => 
              array (
                'i0_menu_chuches' => 
                array (
                  'class' => 'WikiDojoPopupMenuBarItem',
                  'parms' => 
                  array (
                    'DOM' => 
                    array (
                      'id' => 'menu_chuches',
                      'title' => 'Chuches',
                    ),
                  ),
                  'items' => 
                  array (
                    'chuches' => 
                    array (
                      'class' => 'WikiIocDropDownMenu',
                      'parms' => 
                      array (
                        'DOM' => 
                        array (
                          'id' => 'popup_chuches',
                          'title' => 'Chuches',
                        ),
                      ),
                      'items' => 
                      array (
                        'nube' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'nube',
                              'label' => 'nube',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitEditorIcon dijitEditorIconCut\'',
                              'onClick' => 'function(){alert(\'¿Cuántas nubes quieres?\')}',
                            ),
                          ),
                        ),
                        'osito' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'osito',
                              'label' => 'osito',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitEditorIcon dijitEditorIconCut\'',
                              'onClick' => 'function(){alert(\'¿osito o coca-cola?\')}',
                            ),
                          ),
                        ),
                        'regaliz' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'regaliz',
                              'label' => 'regaliz',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitEditorIcon dijitEditorIconCut\'',
                              'onClick' => 'function(){alert(\'Sólo tenemos regaliz de palo\')}',
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
                'i1_menu_pitufos' => 
                array (
                  'class' => 'WikiDojoPopupMenuBarItem',
                  'parms' => 
                  array (
                    'DOM' => 
                    array (
                      'id' => 'menu_pitufos',
                      'title' => 'Pitufos',
                    ),
                  ),
                  'items' => 
                  array (
                    'pitufos' => 
                    array (
                      'class' => 'WikiIocDropDownMenu',
                      'parms' => 
                      array (
                        'DOM' => 
                        array (
                          'id' => 'popup_pitufos',
                          'title' => 'Pitufos',
                        ),
                      ),
                      'items' => 
                      array (
                        'dormilon' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'dormilon',
                              'label' => 'dormilón',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitEditorIcon dijitEditorIconCut\'',
                              'onClick' => 'function(){alert(\'¿Estás dormido?\')}',
                            ),
                          ),
                        ),
                        'gruñón' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'gruñón',
                              'label' => 'gruñón',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitEditorIcon dijitEditorIconCut\'',
                              'onClick' => 'function(){alert(\'¿osito o coca-cola?\')}',
                            ),
                            'PRP' => 
                            array (
                              'MenuSeparator' => true,
                            ),
                          ),
                        ),
                        'pitufina' => 
                        array (
                          'class' => 'WikiDojoMenuItem',
                          'parms' => 
                          array (
                            'DOM' => 
                            array (
                              'id' => 'pitufina',
                              'label' => 'pitufina',
                            ),
                            'DJO' => 
                            array (
                              'iconClass' => '\'dijitEditorIcon dijitEditorIconCut\'',
                              'onClick' => 'function(){alert(\'Sólo tenemos regaliz de palo\')}',
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
                'i2_menu_destroy' => 
                array (
                  'class' => 'WikiDojoMenuBarItem',
                  'parms' => 
                  array (
                    'DOM' => 
                    array (
                      'id' => 'menu_destroy',
                      'label' => 'Destroy',
                    ),
                    'DJO' => 
                    array (
                      'onClick' => 'function(){alert(\'Fes clic per a la destrucció completa del projecte\')}',
                    ),
                  ),
                ),
              ),
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
              'id' => 'zonaLogin',
            ),
            'CSS' => 
            array (
              'float' => 'right',
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
                          'getQuery' => 'function(){var _ret=null; _ret = \'id=wiki:user:\' + this.dispatcher.getGlobalState().userId + \':index\';return _ret;}',
                        ),
                      ),
                    ),
                    'i1_talkUserMenuItem' => 
                    array (
                      'class' => 'WikiIocMenuItem',
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
                          'getQuery' => 'function(){var _ret=null; _ret = \'id=talk:wiki:user:\' + this.dispatcher.getGlobalState().userId + \':index\';return _ret;}',
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
                              'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=recent\'',
                            ),
                          ),
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
                  'getQuery' => 'function(){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var ns=this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; }}return _ret;}',
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
                  'label' => 'Desar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=save\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=save\'',
                  'getDataEventObject' => 'function(){var _ret=null; var id = this.dispatcher.getGlobalState().getCurrentId();_ret = { id: id, name: \'save_\' + id};return _ret;}',
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
                  'label' => 'Detall',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=mediadetails\'',
                  'autoSize' => true,
                  'visible' => false,
                  'getQuery' => 'function(){var _ret=null; _ret=\'\';var node = this.dispatcher.getGlobalState().getDwPageUi().getElementParentNodeId(this.dispatcher.getGlobalState().getCurrentElementId(), \'DL\');if (node) { var elid = \'\'; if (typeof node === \'string\') { elid = node; } else { elid = node.title; } _ret = \'id=\' + elid + \'&image=\' + elid + \'&img=\' + elid + \'&do=media\';}return _ret;}',
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
                  'label' => 'Tornar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=cancel\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=cancel_\'',
                  'getDataEventObject' => 'function(){var _ret=null; var id = this.dispatcher.getGlobalState().getCurrentId();_ret = { id: id, name: \'cancel_\' + id};return _ret;}',
                ),
              ),
            ),
            'i3_cancel.old' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'cancelButton',
                  'label' => 'Tornar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=cancel\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=cancel\'',
                  'getQuery' => 'function(){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var ns=this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; }}return _ret;}',
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
                  'getQuery' => 'function(){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var ns=this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; var rev = this.dispatcher.getGlobalState().getCurrentContent().rev; if(this.query){ _ret=this.query + \'&id=\' + ns; }else{ _ret=\'id=\' + ns; } if (rev) { _ret+=\'&rev=\' + rev; }}return _ret;}',
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
                  'label' => 'Ed. Parc.',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=edit_partial\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=edit_partial\'',
                  'getDataEventObject' => 'function(){var _ret=null; _ret = {};var id = this.dispatcher.getGlobalState().getCurrentId(), chunk = this.dispatcher.getGlobalState().getCurrentElementId();chunk = chunk.replace(id + \'_\', \'\');chunk = chunk.replace(\'container_\', \'\');_ret = { id: id, chunk: chunk, name: \'edit_partial_\' + id};return _ret;}',
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
                  'label' => 'Desar Parc.',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=save_partial\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=save_partial\'',
                  'getDataEventObject' => 'function(){var _ret=null; var id = this.dispatcher.getGlobalState().getCurrentId(), chunk = this.dispatcher.getGlobalState().getCurrentElementId();chunk = chunk.replace(id + \'_\', \'\');chunk = chunk.replace(\'container_\', \'\');_ret = { id: id, chunk: chunk, name: \'save_partial_\' + id};return _ret;}',
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
                  'label' => 'Tornar Parc.',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'query' => '\'do=cancel_partial\'',
                  'autoSize' => true,
                  'visible' => false,
                  'standbyId' => '\'bodyContent\'',
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=cancel_partial\'',
                  'getDataEventObject' => 'function(){var _ret=null; _ret = {};var id = this.dispatcher.getGlobalState().getCurrentId(), chunk = this.dispatcher.getGlobalState().getCurrentElementId();chunk = chunk.replace(id + \'_\', \'\');chunk = chunk.replace(\'container_\', \'\');_ret = { id: id, chunk: chunk, name: \'cancel_partial_\' + id};return _ret;}',
                ),
              ),
            ),
            'i7_supressio' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaSupressioButton',
                  'label' => 'Suprimeix',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?\'',
                  'autoSize' => true,
                  'visible' => false,
                  'getQuery' => 'function(){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var eldelete = this.dispatcher.getGlobalState().currentTabId; var ns = this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; var confirmar=confirm(\'Suprimiu aquesta entrada?\'); if (confirmar){ _ret=\'call=mediadetails&delete=\'+eldelete+\'&do=media&ns=\'+ns; }else{ _ret=\'call=mediadetails&id=\'+eldelete+\'&image=\'+eldelete+\'&img=\'+eldelete+\'&do=media\'; }}return _ret;}',
                ),
              ),
            ),
            'i8_upload' => 
            array (
              'class' => 'WikiIocButton',
              'parms' => 
              array (
                'DOM' => 
                array (
                  'id' => 'mediaUploadButton',
                  'label' => 'Upload',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=mediadetails\'',
                  'autoSize' => true,
                  'visible' => false,
                  'getQuery' => 'function(){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var elid = this.dispatcher.getGlobalState().currentTabId; var ns = this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; if(dojo.query(\'input[type=radio][name=fileoptions]:checked\')[0] === undefined){ _ret=\'id=\' + elid + \'&ns=\' + ns + \'&do=media&versioupload=true\'; }else{ var list = dojo.query(\'input[type=radio][name=fileoptions]:checked\')[0].value; var sort = dojo.query(\'input[type=radio][name=filesort]:checked\')[0].value; _ret=\'id=\' + elid + \'&ns=\' + ns + \'&do=media&list=\'+list+\'&sort=\'+sort+\'&versioupload=true\'; } }return _ret;}',
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
                  'label' => 'Edició',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=mediadetails\'',
                  'autoSize' => true,
                  'visible' => false,
                  'getQuery' => 'function(){var _ret=null; _ret=\'\';if (this.dispatcher.getGlobalState().currentTabId) { var elid = this.dispatcher.getGlobalState().currentTabId; var ns = this.dispatcher.getGlobalState().getContent( this.dispatcher.getGlobalState().currentTabId)[\'ns\']; _ret=\'image=\' + elid + \'&ns=\' + ns + \'&do=media&tab_details=edit&tab_files=files\';}return _ret;}',
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
                  'label' => 'Tornar',
                  'class' => 'iocDisplayBlock',
                ),
                'DJO' => 
                array (
                  'urlBase' => '\'lib/plugins/ajaxcommand/ajax.php?call=mediadetails\'',
                  'autoSize' => true,
                  'visible' => false,
                  'getQuery' => 'function(){var _ret=null; _ret=\'\';var elid = this.dispatcher.getGlobalState().currentTabId;_ret = \'id=\' + elid + \'&image=\' + elid + \'&img=\' + elid + \'&do=media\';return _ret;}',
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
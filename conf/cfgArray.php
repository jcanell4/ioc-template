<?php
$needReset = 0;
$arrIocCfgGUI = array (
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
                  'onClick' => 'function(){alert(\'hola soc onclick de menu_edicio\')}',
                  'onMouseOver' => 'function(){alert(\'hola soc onmouseover.js de menu_edicio\')}',
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
                  'onClick' => 'function(){alert(\'hola soc onclick de menu_vista\')}',
                  'onMouseOver' => 'function(){alert(\'hola soc onmouseover.js de menu_vista\')}',
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
                  'onClick' => 'function(){alert(\'hola soc onclick de menu_correccio amb la constant topBloc\')}',
                  'onMouseOver' => 'function(){alert(\'hola soc onmouseover.js de menu_correccio\')}',
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
                          'id' => 'name',
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
                          'id' => 'pass',
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
            'zN' => 
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
                0 => 
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
                    0 => 
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
                    1 => 
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
                  ),
                ),
              ),
            ),
            'zM' => 
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
                0 => 
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
            0 => 
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
                ),
              ),
            ),
            1 => 
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
                ),
              ),
            ),
            2 => 
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
            3 => 
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
            4 => 
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
                ),
              ),
            ),
            5 => 
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
                ),
              ),
            ),
            6 => 
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
                ),
              ),
            ),
            7 => 
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
<?php

class cfgIdConstants
{

    //id principal
    const MAIN = "main";

    //elemento topBloc de MAIN
    const TOP_BLOC = "topBloc";
    const ZONA_LOGO = "zonaLogo";
    //elemento Barramenu del elemento topBloc de MAIN
    const ZONA_MENU = "zonaMenu";
    const BARRA_MENU = "barraMenu";

    //elemento zonaLogin del elemento topBloc de MAIN
    const ZONA_LOGIN = "zonaLogin";
    const LOGIN_BUTTON = "loginButton";
    const LOGIN_DIALOG = "loginDialog";
    const LOGIN_NAME = "login_name";
    const LOGIN_PASS = "login_pass";
    const USER_BUTTON = "userButton";
    const USER_DIALOG = "userDialog";
    const USER_MENU_ITEM = "userMenuItem";
    const SHORTCUTS_MENU_ITEM = "shortcutsMenuItem";
    const TALK_USER_MENU_ITEM = "talkUserMenuItem";
    const LOGOFF_MENU_ITEM = "logoffMenuItem";
    const NOTIFIER_BUTTON_INBOX = "notifierButtonInbox";
    const NOTIFIER_BUTTON_OUTBOX = "notifierButtonOutbox";
    const NOTIFIER_BUTTON_OUTBOX_LABEL = "Enviats"; // TODO[Xavi] Localitzar, com ho fem?
    const NOTIFIER_CONTAINER_INBOX = "notifierContainerInbox";
    const NOTIFIER_CONTAINER_OUTBOX= "notifierContainerOutbox";

    //elemento mainContent de MAIN
    const MAIN_CONTENT = "mainContent";
    const MAIN_BC      = "mainContent_bc"; //Main BorderContainer

    const LEFT_PANEL = "id_LeftPanel";
    const TB_CONTAINER = "tb_container";
    const ZONA_NAVEGACIO = "zonaNavegacio";
    const TB_INDEX = "tb_index";
    const TB_PERFIL = "tb_perfil";
    const TB_ADMIN = "tb_admin";
    const TB_DOCU = "tb_docu";
    const TB_MENU = "tb_menu";
    const TB_SHORTCUTS = "tb_shortcuts";
    const LEFT_BC_PANEL = "leftBorderContainerPanel";
    const LEFT_TOP = "left_top_panel";
    const LEFT_BOTTOM = "left_bottom_panel";

    const EINES_CANVIS_RECENTS = "canvisRecents";
    const EINES_MEDIAMANGER = "mediaManager";

    const ZONA_METAINFO_DIV = "zonaMetaInfoDiv";
    const ZONA_METAINFO = "zonaMetaInfo";

    const CONTENT = "content";
    const BODY_CONTENT = "bodyContent";

    const ZONA_CANVI = "zonaCanvi";
    const NEW_BUTTON = "newButton";
    const NEW_BUTTON_DIALOG = "newButtonDialog";
    const SAVE_BUTTON = "saveButton";
    const CANCEL_BUTTON = "cancelButton";
    const EDIT_BUTTON = "editButton";
    const ED_PARC_BUTTON = "edparcButton";
    const CANCEL_PARC_BUTTON = "cancelparcButton";
    const SAVE_PARC_BUTTON = "saveparcButton";
    const SAVE_FORM_BUTTON = "saveFormButton";
    const GENERATE_PROJECT_BUTTON = "generateProjectButton";
    const PRINT_BUTTON = "printButton";

    const MEDIA_DETAIL_BUTTON = "mediaDetailButton";
    const MEDIA_TORNAR_BUTTON = "mediaTornarButton";
    const DETAIL_SUPRESSIO_BUTTON = "detailSupressioButton";
    const MEDIA_SUPRESSIO_BUTTON = "mediaSupressioButton";
    const MEDIA_UPLOAD_BUTTON = "mediaUploadButton";
    const MEDIA_UPDATE_IMAGE_BUTTON = "mediaUpdateImageButton";
    const MEDIA_EDIT_BUTTON = "mediaEditButton";
    const EXIT_BUTTON = "exitButton";

    const BOTTOM_PANEL = "id_BottomPanel";
    const ZONA_MISSATGES = "zonaMissatges";
    const ZONA_TOP_RIGHT = "zonaTopRight";
    const ZONA_TOP_NOTIFICATION_INBOX = "zonaNotificationInbox";
    const ZONA_TOP_NOTIFICATION_OUTBOX = "zonaNotificationOutbox";

    const SYSTEM_WARNING_CONTAINER = "systemWarningContainer";

    //Constantes de la clase WikiIocTabsContainer de WikiIocComponents
    const DEFAULT_TAB_TYPE = 0;
    const RESIZING_TAB_TYPE = 1;
    const SCROLLING_TAB_TYPE = 2;

    # Access Control Lists
    const ACL_NONE = AUTH_NONE;
    const ACL_READ = AUTH_READ;
    const ACL_EDIT = AUTH_EDIT;
    const ACL_CREATE = AUTH_CREATE;
    const ACL_UPLOAD = AUTH_UPLOAD;
    const ACL_DELETE = AUTH_DELETE;

    //Otras constantes provenientes de scriptRef.tpl
    const SECTOK_ID = "ajax";             //antes era %%ID%%

    //Constant necessaria per a la generació dinàmica de controls
    const WIKI_IOC_BUTTON_PATH = "/i1_main/i2_right";

    /*
    const NAVEGACIO_NODE_ID   = "zonaNavegacio";    //cambiada por ZONA_NAVEGACIO
    const METAINFO_NODE_ID    = "zonaMetaInfo";     //cambiada por ZONA_METAINFO
    const INFO_NODE_ID        = "zonaMissatges";    //cambiada por ZONA_MISSATGES
    const CANVI_NODE_ID       = "zonaCanvi";        //cambiada por ZONA_CANVI
    const TAB_INDEX           = "tb_index";         //cambiada por TB_INDEX
    const TAB_DOCU            = "tb_docu";          //cambiada por TB_DOCU (no existe)
    const USER_MENUITEM       = "userMenuItem";     //cambiada por USER_MENU_ITEM
    const TALKUSER_MENUITEM   = "talkUserMenuItem"; //cambiada por TALK_USER_MENU_ITEM
    const LOGOFF_MENUITEM     = "logoffMenuItem";   //cambiada por LOGOFF_MENU_ITEM
    */
    
    //Constant de tipus de projecte
    const PROJECT_TYPE = 'projectType';
    
    public function getConstantsIds()
    {
        global $js_packages;
        $oCfgClass = new ReflectionClass ('cfgIdConstants');
        $arrConstants = $oCfgClass->getConstants();
        //Otras constantes provenientes de scriptRef.tpl
        $arrConstants['DOJO_SOURCE'] = $js_packages["dojo"];
        $arrConstants['SECTOK'] = getSecurityToken();
        return $arrConstants;
    }

    public function getConstantToString($constante)
    {
        return "'" . $constante . "'";
    }

    function __construct()
    {
    }
}

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/dojo/1.8/dojo/dojo.js"></script>
<script type="text/javascript">
    require([
      "dojo/dom"
     ,"dojo/dom-style"
     ,"dojo/window"
     ,"ioc/wiki30/dispatcherSingleton"
     ,"dijit/registry"
     ,"dojo/ready"
     ,"dojo/_base/lang"
     ,"dijit/dijit"
     ,"dojo/parser"
     ,"dijit/layout/BorderContainer"
     ,"dijit/layout/ContentPane"
     ,"dijit/MenuBar"
     ,"dijit/PopupMenuBarItem"
     ,"dijit/MenuItem"
     ,"dijit/Menu"
     ,"dijit/TitlePane"
     ,"ioc/gui/ResizingTabController"
     ,"ioc/gui/IocButton"
     ,"ioc/gui/IocDropDownButton"
     ,"dijit/layout/TabContainer"
     ,"dijit/layout/AccordionContainer"
     ,"dijit/Toolbar"
     ,"dijit/layout/SplitContainer"
     ,"dijit/TooltipDialog"
     ,"dijit/form/TextBox"
     ,"ioc/gui/IocForm"        
     ,"ioc/gui/ContentTabDokuwikiPage"
     ,"ioc/gui/ContentTabDokuwikiNsTree"
     ,"ioc/gui/ActionHiddenDialogDokuwiki"
     ,"dojo/domReady!"
    ], function(dom, domStyle, win, wikiIocDispatcher, registry, ready, lang){
            
			var divMainContent = dom.byId("@@MAIN_CONTENT@@");
            var tab_index = '@@TAB_INDEX@@';
            var tab_docu = '@@TAB_DOCU@@';
            var login_dialog = '@@LOGIN_DIALOG@@';
            var login_button = '@@LOGIN_BUTTON@@';
            var exit_button = '@@EXIT_BUTTON@@';
            var edit_button = '@@EDIT_BUTTON@@';
            var new_button = '@@NEW_BUTTON@@';
            var save_button = '@@SAVE_BUTTON@@';
            var ed_parc_button = '@@ED_PARC_BUTTON@@';
            var h = 100*(win.getBox().h-55)/win.getBox().h;
            domStyle.set(divMainContent, "height", h+"%");
            /*
            var wikiIocDispatcher = new Dispatcher({
                containerNodeId: "@@BODY_CONTENT@@"
            });
            */
            wikiIocDispatcher.containerNodeId = "@@BODY_CONTENT@@";
            wikiIocDispatcher.navegacioNodeId = "@@NAVEGACIO_NODE_ID@@";
			wikiIocDispatcher.metaInfoNodeId = "@@METAINFO_NODE_ID@@";	//dom node de la zona de meta-informació
            wikiIocDispatcher.infoNodeId = "@@INFO_NODE_ID@@";	//dom node de la zona de missatges
            wikiIocDispatcher.sectokManager.putSectok("%%ID%%", "%%SECTOK%%");
//            wikiIocDispatcher.loginButtonId = '@@LOGIN_BUTTON@@';
//            wikiIocDispatcher.exitButtonId = '@@EXIT_BUTTON@@';
//            wikiIocDispatcher.editButtonId = '@@EDIT_BUTTON@@';
            wikiIocDispatcher.updateFromState = function(){
                if(!this.globalState.login){
                    this.changeWidgetProperty(login_button, "visible", true);
                    this.changeWidgetProperty(exit_button, "visible", false);
                    this.changeWidgetProperty(new_button, "visible", false);
                    this.changeWidgetProperty(edit_button, "visible", false);
                    this.changeWidgetProperty(save_button, "visible", false);
                    this.changeWidgetProperty(ed_parc_button, "visible", false);
                }else{
                    this.changeWidgetProperty(login_button, "visible", false);
                    this.changeWidgetProperty(exit_button, "visible", true);
                    this.changeWidgetProperty(new_button, "visible", true);
                    if(this.globalState.currentTabId){
                        var page = this.globalState.pages[this.globalState.currentTabId];
                        if(page.action==='view'){
                            this.changeWidgetProperty(edit_button, "visible", true);
                            this.changeWidgetProperty(save_button, "visible", false);
                            this.changeWidgetProperty(ed_parc_button, "visible", true);
                        }else if(page.action==='edit'){
                            this.changeWidgetProperty(edit_button, "visible", false);
                            this.changeWidgetProperty(save_button, "visible", true);
                            this.changeWidgetProperty(ed_parc_button, "visible", false);
                        }
                    }
                }
            };


			ready(function(){
				var tbContainer = registry.byId(wikiIocDispatcher.navegacioNodeId);
                tbContainer.watch("selectedChildWidget", function(name,oldTab,newTab){
					if (newTab.updateRendering)
						newTab.updateRendering();
					});

                tbContainer = registry.byId(tab_index);
                tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
                tbContainer.set("standbyId", wikiIocDispatcher.containerNodeId);
                wikiIocDispatcher.toUpdateSectok.push(tbContainer);
                tbContainer.updateSectok();

                tbContainer = registry.byId(tab_docu);
                tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=page"); 
                tbContainer.set("standbyId", wikiIocDispatcher.containerNodeId);

                tbContainer = registry.byId(exit_button);
                tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
                //tbContainer.set("standbyId", "loginDialog_hidden_container");

                tbContainer = registry.byId(edit_button);
                tbContainer.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=edit"); 
                //tbContainer.set("standbyId", "loginDialog_hidden_container");

                var loginDialog = registry.byId(login_dialog);
                loginDialog.set("urlBase", "lib/plugins/ajaxcommand/ajax.php?call=login"); 
                loginDialog.set("standbyId", "loginDialog_hidden_container");

                loginDialog.on('hide',function(){
                    loginDialog.reset(); 
                });

                var loginCancelButton = registry.byId(login_dialog+'_CancelButton');
                loginCancelButton.on('click',function(){
                    var but = registry.byId(login_button);
                    but.closeDropDown(false);
                });

                    //Prova per insertar un nou element a la zona de metainformació
                    //var array = {'type':'metainfo', 'value':{'docId':'id_doc3', 'id':'meta3', 'title':'títol MetaInfo3', 'content':"contingut meta informació 3"}};
                    //wikiIocDispatcher.processResponse(array);
            });
		
    });
</script>


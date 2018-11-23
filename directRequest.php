<?php
/**
 * Tractament de les peticions explícites de pàgina que arriben per GET
 * Si s'ha fet una petició explícita de pàgina a través de GET, iniciem el tractament,
 * en canvi, si no hi ha petició explícita, no fem res.
 * @author culpable Rafael
 */

class directRequest {
    /**
     * Obté els paràmetres de que puguin arribar per $_GET
     * Si s'ha fet una petició explícita de pàgina a través de GET, iniciem el tractament,
     * en canvi, si no hi ha petició explícita, no fem res.
     * @return string[] hash amb els paràmetres
     */
    public function generateEmbebdedScript() {
        global $_GET;
        /**
         * exemple: ?id=talk:start&do=edit&rev=0
         * exemple: ?id=wiki:user:z-perm2:doc_1&do=edit&rev=0
         * exemple: ?id=wiki_user_z-perm2_doc_1&do=edit&rev=0
         */
        if (isset($_GET['id'])) { //Fem tractament només si es fa una petició de pàgina explícita per GET
            $id = str_replace(':', '_', $_GET['id']);
            $query['pages'][$id]['ns'] = $_GET['id'];
            if (isset($_GET['do'])) {
                $query['pages'][$id]['action'] = $_GET['do'];
            }
            if (isset($_GET['rev'])) {
                $query['pages'][$id]['rev'] = $_GET['rev'];
            }

            if (isset($query)) {
                $queryEncoded = json_encode($query);

                $ret = "\n<script type='text/javascript'>\n".
                        "require([\n".
                            "\t'ioc/wiki30/GlobalState',\n".
                            "\t'ioc/wiki30/dispatcherSingleton',\n".
                            "\t'dojo/domReady!'\n".
                        "], function (globalState, getDispatcher) {\n".
                                "\tvar wikiIocDispatcher = getDispatcher();\n".
                                "\twikiIocDispatcher.setRequestedState(globalState.newInstance(". $queryEncoded . "));\n".
                            "}\n".
                        ");\n".
                        "</script>\n";
            }
        }
        return $ret;
    }

}

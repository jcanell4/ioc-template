<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WikiIocContentPage
 *
 * @author professor
 */

//check if we are running within the DokuWiki environment
if (!defined("DOKU_INC")) die();
if (!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', tpl_incdir().'classes/');

require_once(DOKU_TPL_CLASSES.'WikiIocBuilder.php');

class WikiIocContentPage extends WikiIocBuilder{
    
    public function __construct(){
    }
    
    public function printRenderingCode() {
        trigger_event('TPL_CONTENT_DISPLAY',
                $this->getRenderingCode(),'ptln');        
    }
    
    public function getRenderingCode() {
        global $ACT;
        ob_start();
        trigger_event('TPL_ACT_RENDER', $ACT, "tpl_content_core");
        $html_output = ob_get_clean()."\n";
        return $html_output;
    }
    
    public function getId(){
        global $ID;
        return $ID;
    }
}

?>

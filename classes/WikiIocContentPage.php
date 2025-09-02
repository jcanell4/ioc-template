<?php
//check if we are running within the DokuWiki environment
if(!defined("DOKU_INC")) die();
if(!defined('DOKU_TPL_CLASSES')) define('DOKU_TPL_CLASSES', WikiGlobalConfig::tplIncDir().'classes/');

require_once(DOKU_TPL_CLASSES . 'WikiIocBuilder.php');

/**
 * Class WikiIocContentPage
 * Aquesta es la pàgina principal que conté el template.
 *
 * @author Josep Cañellas <jcanell4@ioc.cat>
 */
class WikiIocContentPage extends WikiIocBuilder {

    /**
     * Constructor buit per evitar que passi al constructor de la superclasse.
     */
    public function __construct() {
    }

    /**
     * Activa l'event 'TPL_CONTENT_DISPLAY' de dokuwiki, amb el codi a renderitzar, i realitza la acció 'ptln'.
     */
    public function printRenderingCode() {
        trigger_event(
            'TPL_CONTENT_DISPLAY',
            $this->getRenderingCode(),
            'ptln'
        );
    }

    /**
     * Activa el buffer de sortida, activa l'event 'TPL_ACT_RENDER' de dokuwiki, li pasa el codi emmagatzemat a $ACT i
     * realitza la acció 'tpl_content_core'). Tanca el buffer i retorna el string amb el codi html.
     *
     * Les dades es passen per referència, així que el valor de $ACT pot ser canviat dins de trigger_event().
     *
     * @return string codi html per mostrar
     */
    public function getRenderingCode() {
        global $ACT;
        ob_start();
        trigger_event('TPL_ACT_RENDER', $ACT, "tpl_content_core");
        $html_output = ob_get_clean() . "\n";
        return $html_output;
    }

    /**
     * Retorna el valor de la ID global.
     *
     * @return string
     */
    public function getId() {
        global $ID;
        return $ID;
    }
}
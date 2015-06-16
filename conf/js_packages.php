<?php

/*
 * Definició de packages
 */

if (!defined('DOKU_INC')) die();
require_once DOKU_INC.'lib/plugins/ownInit/conf/default.php';

global $js_packages;
$js_packages["ioc"]="/iocjslib/ioc";
$js_packages["ace-builds"]="/ace-builds/src-noconflict";

$js_packages["dojo"]=$conf['dojo_url_base']."dojo";
$js_packages["dijit"]=$conf['dojo_url_base']."dijit";
$js_packages["dojox"]=$conf['dojo_url_base']."dojox";
?>

<html><head><style>
 body {font:13px sans-serif;}
 pre {font:10pt courier;}
</style></head><body>
<div style="top:0px; left:10px; width:96%; border:0; padding:8px; position:absolute;">
<?php
/**
 * Mòdul principal de construcció del fitxer cfgArray.php
 * que contindrà l'array generador de la GUI
**/
if (!defined('DOKU_INC')) define('DOKU_INC', true);
if (!defined('DOKU_TPL_INCDIR')) define('DOKU_TPL_INCDIR', "../");
require_once(DOKU_TPL_INCDIR . 'conf/default.php');
require_once(DOKU_TPL_INCDIR . 'conf/cfgIdConstants.php');
require_once(DOKU_TPL_INCDIR . 'conf/cfgBuilder.php');

echo "<p>M&ograve;dul principal de construcci&oacute; del fitxer <b>cfgArray.php</b><br>que contindr&agrave; l'array generador de la GUI</p>";

//$ruta = realpath(dirname(__FILE__)) . "/a_CfgGUI";
$ruta = $conf["ioc_path_cfg_gui"];
$fileArrayCfgGUI = $conf["ioc_file_cfg_gui"];

$inst = new cfgBuilder();
$aIocCfg = $inst->getArrayCfg($ruta);
$inst->writeArrayToFile($aIocCfg, $fileArrayCfgGUI);

echo "array generat: <pre>".print_r($aIocCfg, true)."</pre>";
echo "<p>Fi del proc&eacute;s de generaci&oacute; del fitxer <b>$fileArrayCfgGUI</b></p>"
?>
</div></body></html>

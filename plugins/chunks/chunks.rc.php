<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=rc
[END_COT_EXT]
==================== */

/**
 * Header file for Template chunks plugin
 *
 * @package chunks
 * @author Andrey Matsovkin
 * @copyright Copyright (c) 2011-2014
 * @license Distributed under BSD license.
 * Made with «Extension Template» (https://github.com/macik/cot-extension_template)
 */

defined('COT_CODE') or die('Wrong URL.');

global $pl_cfg;
$pl_cfg = $cfg['plugin']['chunks'];

if ($_GET['e']=='chunks') {
    define('PLUGMARK',true);
    // cot_rc_add_file($cfg['plugins_dir'] . '/chunks/tpl/chunks.css');
    // cot_rc_add_file($cfg['plugins_dir'] . '/chunks/js/chunks.js');
    // cot_rc_add_file($cfg['plugins_dir'] . '/chunks/lib/jquery.sample.min.js');
}

if ($cfg['jquery'] && $cfg['turnajax']){
    // cot_rc_add_file($cfg['plugins_dir'] . '/chunks/lib/jquery.sample.css');
    // cot_rc_add_embed('autocomplete', '$(document).ready(function(){ alert('ok');});');
} else {

}
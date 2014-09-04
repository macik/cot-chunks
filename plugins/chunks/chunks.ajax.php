<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
[END_COT_EXT]
==================== */

/**
 * Template chunks Ajax interaction back-end
 *
 * @package chunks
 * @author Andrey Matsovkin
 * @copyright Copyright (c) 2011-2014
 * @license Distributed under BSD license.
 * Made with «Extension Template» (https://github.com/macik/cot-extension_template)
 */

defined('COT_CODE') or die('Wrong URL.');
// plug.php?r=chunks
define('chunks_AJAX',TRUE);
$plug_name = 'chunks';
$ajax_link = cot_url('plug',array('r'=>$plug_name));
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', $r);
cot_block($usr['auth_read']);
//require cot_incfile('chunks', 'plug', 'common');

//header("Content-type: text/html; charset=utf-8");
header("Content-type: application/json; charset=utf-8");
if ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' || (strtolower($_SERVER['SERVER_NAME'])=='localhost') ) {
    echo 'Test result. AJAX query OK.';
} else {
    echo 'Test result. NaAQ (not an ajax query)';
}
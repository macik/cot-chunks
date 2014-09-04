<?php
/**
 * Localization file for Template chunks
 * @author Andrey Matsovkin
 * @copyright Copyright (c) 2011-2014
 * @license Distributed under BSD license.
 * Made with «Extension Template» (https://github.com/macik/cot-extension_template)
*/

defined('COT_CODE') or die('Wrong URL');

$L['plu_title'] = 'Template chunks'; // Title for stand alone

$L['info_desc'] ='Extension for template system to use admin editable chunks'; // plugin description
if (version_compare($cfg['version'], '0.9.12') > 0) // still buggy in Siena 0.9.12
	$L['info_notes'] = 'If&nbsp;your enjoy my&nbsp;plugin please consider donating to&nbsp;help support future developments. <b>Thanks!</b> <br /><a href="mailto:macik.spb@gmail.com">macik.spb@gmail.com</a>'; 

$L['cfg_VAR'] =array('Param name','description'); // cfg l10n
$L['cfg_VAR_params'] = array(1,2,3);

// $L[$extrafield['field_name'] . '_' . $var  пример $L['extrafieldname_1']

$adminhelp1 = '';
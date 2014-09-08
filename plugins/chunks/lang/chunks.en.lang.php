<?php
/**
 * Localization file for Template chunks
 * Author=Andrey Matsovkin & Dr2005alex
 * Copyright=Copyright (c) 2014 Cotonti team
 * @license Distributed under BSD license.
*/

defined('COT_CODE') or die('Wrong URL');

//$L['plu_title'] = 'Template chunks'; // Title for stand alone

$L['info_desc'] ='Extension for template system to use admin editable chunks'; // plugin description

$L['cfg_allowed_loops'] =array('Allowed same tag recursion loops','0 is for restriction'); // cfg l10n
$L['cfg_throw_exception'] =array('Always throw exception on chunk parsing errors','otherwise throw exception only in DEBUG_MODE'); // cfg l10n
$L['cfg_VAR_params'] = array(1,2,3);

$Ls['chunks'] = array('chunk','chunks');
$L['Chunk'] = 'Chunk';
$L['chunk_not_found'] = 'Chunk {$0} не найден';
$L['chunk_looped'] = 'Chunk {$0} cause a looped call and exeed a recursion levels limit: {$1}';
// $L[$extrafield['field_name'] . '_' . $var  пример $L['extrafieldname_1']

$adminhelp1 = '';
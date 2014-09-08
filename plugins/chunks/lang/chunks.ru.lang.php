<?php
/**
 * Localization file for Template chunks
 * Author=Andrey Matsovkin & Dr2005alex
 * Copyright=Copyright (c) 2014 Cotonti team
 * @license Distributed under BSD license.
*/

defined('COT_CODE') or die('Wrong URL');

//$L['plu_title'] = 'Чанки'; // Title for stand alone

$L['info_desc'] ='Расширение шаблонизатора системой чанков'; // plugin description

$L['cfg_allowed_loops'] =array('Количество разрешенных рекурсивных вызовов одного и того же тега','0 — рекурсия запрещена'); // cfg l10n
$L['cfg_throw_exception'] =array('Всегда использовать исключения при найденных ошибках при обработке чанков','иначе только в ражиме отладки сайта (`debug_mode`)'); // cfg l10n
$L['cfg_VAR_params'] = array(1,2,3);

$Ls['chunks'] = array('чанк','чанка','чанков');
$L['Chunk'] = 'Чанк';
$L['chunk_not_found'] = 'Чанк {$0} не найден';
$L['chunk_looped'] = 'Чанк {$0} вызвал циклическую обработку и превысил лимит вложенности: {$1}';
// $L[$extrafield['field_name'] . '_' . $var  пример $L['extrafieldname_1']

$adminhelp1 = '';
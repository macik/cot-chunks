<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=global
[END_COT_EXT]
==================== */

/**
 * Template chunks
 *
 * @package chunks
 * @author Andrey Matsovkin
 * @copyright Copyright (c) 2011-2014
 * @license Distributed under BSD license.
 * Made with «Extension Template» (https://github.com/macik/cot-extension_template)
 */

defined('COT_CODE') or die('Wrong URL.');
$plug_name = 'chunks';

//require_once cot_incfile('chunks', 'plug'');

XTemplate::init(array(
'cache'        => false,
));

// register extender
XTemplate::set_extender(
	array(
		'`\{(CHUNK):([\w\.\-]+)\s*(.*?)\}`'	=>	'cot_chunk_parse',
		// TAG PCRE => Handler name
	)
);

/**
 * Template Chunks parser
 *
 * @param array $found_ext_tag array of PCRE search results (see tag defenition in set_extender):
 *     $found_ext_tag[1] — name of tag token, examlpe `CHUNK`
 *     $found_ext_tag[2] — Name of chunk
 *     $found_ext_tag[3] — rest of all
 * @param XTemplate $tpl_context instance of current template
 * @return string
 */
function cot_chunk_parse($found_ext_tag, $tpl_context)
{
	global $cfg;

	$chunk_name = $found_ext_tag[2];

	// check for endless recursion
	if (in_array($chunk_name, $tpl_context->extender_handling_list))
	{
		throw new Exception('Chunk '.htmlspecialchars($chunk_name).' cause endless recursion');
	}
	else
	{
		array_push($tpl_context->extender_handling_list, $chunk_name);
	}

	// cheking this chunk from DB
	// ……not implemented yet………

	// trying to load chunk from file
	// $chunk_file = $cfg['themes_dir'].'/'.$cfg['defaulttheme'].'/chunks/'.strtolower($found_ext_tag[2]);
	$chunk_file = cot_tplfile('chunk.'.strtolower($chunk_name), 'plug' );
	if (file_exists($chunk_file))
	{
		$chunk = cotpl_read_file($chunk_file);
		// FIXME: is it neccessary to made manual recursion?
		/*
		if ($code[0] == chr(0xEF) && $chunk[1] == chr(0xBB) && $chunk[2] == chr(0xBF)) $chunk = mb_substr($chunk, 0);
		$tpl_context->handle_extenders($chunk);
		*/
		return $chunk;
	}
	else
	{
		// FIXME: choose best oportunity
		// return '';
		throw new Exception('Chunk ' . htmlspecialchars($chunk_name) . ' not found');
	}

}
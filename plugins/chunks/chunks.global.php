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

require_once $cfg['system_dir'] . '/cotemplate.etag.php';

// -- For development brunch only, remove on production -----
XTemplate::init(array(
	'cache'        => false,
));
$chunks_test_string = 'Chunks work!';
// -----------------------------------------------------------

class_exists('Cotpl_ethandler') && $chunk_handler = new Cotpl_ethandler('`\{(CHUNK):([\w\.\-]+)\s*(.*?)\}`', 'chunk_parse');

// register extender
XTemplate::set_extender(
	array(
		'CHUNK' => $chunk_handler,
	)
);

/**
 * Loads chunk content from DB
 * @param string $chunk_name Chunk name
 * @return string Chunk content
 */
function chunk_from_db($chunk_name){
	// loads from DB as common slot (see addition Slots plugin)
	// ……not implemented yet………

	return '';
}

/**
 * Loads chunk content from file
 * @param string $chunk_name Chunk name
 * @throws Exception if file not found
 * @return string Chunk content
 */
function chunk_from_file($chunk_name){
	$chunk_file = cot_tplfile(array('chunks',strtolower($chunk_name)), 'plug' );
	if (file_exists($chunk_file))
	{
		$chunk = cotpl_read_file($chunk_file);
		if ($code[0] == chr(0xEF) && $chunk[1] == chr(0xBB) && $chunk[2] == chr(0xBF)) $chunk = mb_substr($chunk, 0);
		return $chunk;
	}
	else
	{
		// FIXME: choose best opportunity (exception or return empty)
		//if (cot::$cfg['debug_mode'])
		throw new Exception('Chunk ' . htmlspecialchars($chunk_name) . ' not found');
		// TODO: Use lang files
	}
	return '';
}

/**
 * Template Chunks parser
 * @param Cotpl_etblock $block instance of block containing parsed tag
 * @param array $tag array of PCRE search results (see tag definition in set_extender):
 *     $tag[1] — name of tag token, example `CHUNK`
 *     $tag[2] — Name of chunk
 *     $tag[3] — rest of all
 * @return string Parsed chunk
 */
function chunk_parse(Cotpl_etblock $block, $tag)
{
	global $cfg;
	// TODO: move to config
	$allowed_loops = 0;

	$chunk_name = $tag[2];

	$etag = new Cotpl_etag($block, $chunk_name);
	if (! $looped = $etag->looped($allowed_loops))
	{
		// get this chunk from DB
		$chunk = chunk_from_db($chunk_name);
		if (empty($chunk))
		{
			// otherwise trying to load chunk from file
			$chunk = chunk_from_file($chunk_name);
		}
		if (! empty($chunk))
		{
			$parents = $block->parents;
			$parents[] = $etag->id;
			$sub_block = new Cotpl_etblock($parents);
			return $sub_block->parse($chunk);
		}
		else return '';
	}
	else
	{
		// TODO: Use lang files
		if (cot::$cfg['debug_mode']) throw new Exception('Chunk ' . htmlspecialchars($chunk_name) . ' cause looped call: ' . $looped);
	}
	return '';
}


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
 * @author Andrey Matsovkin & Dr2005Alex
 * @copyright Copyright (c) 2014 Cotonti team
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
$tag_token = 'CHUNK';
if (class_exists('Cotpl_ethandler'))
{
	// with short token syntax «!CHUNK_NAME»
	$chunk_handler = new Cotpl_ethandler('`\{(?:(\!|'.$tag_token.'):\s*)([\w\.\-]+)(?:\s*(\()?)([^\)}]*(?(3)|\)?))(\))?\}`', 'chunk_parse');
	//$chunk_handler = new Cotpl_ethandler('`\{('.$tag_token.'):\s*([\w\.\-]+)(?:\s*(\()?)([^\)}]*(?(3)|\)?))(\))?\}`', 'chunk_parse');
}

// register extender in CoTemplate
XTemplate::set_extender(
	array(
		$tag_token => $chunk_handler,
	)
);

$chunk_allowed_loops = cot::$cfg['plugin']['chunks']['allowed_loops'];
$chunk_throw_exception = cot::$cfg['plugin']['chunks']['throw_exception'];
if (!$chunk_allowed_loops || !is_numeric($chunk_allowed_loops)) $chunk_allowed_loops = 0;

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
	global $L;

	$chunk_file = cot_tplfile(array('chunks',strtolower($chunk_name)), 'plug' );
	if (file_exists($chunk_file))
	{
		$chunk = cotpl_read_file($chunk_file);
		if ($code[0] == chr(0xEF) && $chunk[1] == chr(0xBB) && $chunk[2] == chr(0xBF)) $chunk = mb_substr($chunk, 0);
		return $chunk;
	}
	else
	{
		if (cot::$cfg['debug_mode'] || $chunk_throw_exception)
		{
			require_once cot_langfile('chunks', 'plug');
			throw new Exception(cot_rc('chunk_not_found' , htmlspecialchars($chunk_name)));
		}
	}
	return '';
}

/**
 * Parse tag parameters and change placeholders
 *
 * @param string $chunk Chunk text body
 * @param string $param_str Parameters string
 *
 * Parameters format:
 *   123,'string',TAG_NAME — unnamed params
 *   p1=123, p2='string', p3=TAG_NAME — named params
 *
 *  Callback also works:
 *  	a=PHP.var|my_func($this)
 *
 * Parameters should be comma separated. Strings must be enclosed on quotes (' or ").
 * String without quotes treated as TPL tag name and replaced with it's value.
 *
 * @return string Parsed Tag content
 */
function chunk_parse_params($chunk, $param_str)
{
		// named parameters
		preg_match_all("`\s*(?:([a-z][\w\d]+)(?:\s*=\s*))?([^\,]+?)\s*\,\s*`i", $param_str . ',', $pp);
		$params = array();
		foreach ($pp[1] as $key => $name)
		{
			if ($name)
			{
				$params[$name] = $pp[2][$key];
			}
			else
			{
				$params[] = $pp[2][$key];
			}
		}

	// prepare values
	foreach ($params as $key => &$val)
	{
		$val = trim($val);
		if (! is_numeric($val))
		{
			$first_char = substr($val, 0, 1);
			if (strpos('"\'`', $first_char) !== false)
			{
				// strip quotes
				if (substr($val, - 1) == $first_char)
				{
					$val = str_replace($first_char, '', $val);
				}
			}
			else
			{
				// made TPL tag reference
				$val = '{' . $val . '}';
			}
		}
	}
	return cot_rc($chunk, $params);
}

/**
 * Template Chunks parser
 * @param Cotpl_etblock $block instance of block containing parsed tag
 * @param array $tag array of PCRE search results (see tag definition in set_extender):
 *     $tag[1] — name of tag token, example `CHUNK`
 *     $tag[2] — Name of chunk
 *     $tag[4] — parameters for chunk
 * @return string Parsed chunk
 */
function chunk_parse(Cotpl_etblock $block, $tag)
{
	global $L, $cfg, $chunk_allowed_loops, $chunk_throw_exception;

	$chunk_name = $tag[2];

	$etag = new Cotpl_etag($block, $chunk_name);
	if (! $looped = $etag->looped($chunk_allowed_loops))
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
			// parsing chunk params
			if (trim($tag[4])) $chunk = chunk_parse_params($chunk, $tag[3]);
			$parents = $block->parents;
			$parents[] = $etag->id;
			$sub_block = new Cotpl_etblock($parents);
			return $sub_block->parse($chunk);
		}
		else return '';
	}
	else
	{
		if (cot::$cfg['debug_mode'] || $chunk_throw_exception)
		{
			require_once cot_langfile('chunks', 'plug');
			throw new Exception(cot_rc('chunk_looped' , array(htmlspecialchars($chunk_name), $looped)));
		}
	}
	return '';
}


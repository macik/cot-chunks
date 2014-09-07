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
$tag_token = 'CHUNK';
if (class_exists('Cotpl_ethandler'))
{
	$chunk_handler = new Cotpl_ethandler('`\{('.$tag_token.'):\s*([\w\.\-]+)(?:\s*\(?)(.*?)\)?\}`', 'chunk_parse');
}

// register extender
XTemplate::set_extender(
	array(
		$tag_token => $chunk_handler,
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
 * Parse tag parameters and change placeholders
 * @param string $chunk Chunk text body
 * @param string $param_str Parameters string
 * @return string Parsed Tag content
 */
function chunk_parse_params($chunk, $param_str)
{
		// $param_str = trim($param_str );
	if (strpos($param_str, '=') === false)
	{
		// for unnamed params use fast str_getcsv
		$params = str_getcsv($param_str, ',', '}'); // «}» used to save quotes
	}
	else
	{
		// named parameters
		/*
		 * preg_match_all("`\s*(?:([a-z][\w\d]+)(?:\s*=\s*))?([^\,]+?)\s*\,\s*`i", $param_str . ',', $p); $params = array(); foreach ($pp[1] as $key => $name) { if ($name) { $params[$name] = $pp[2][$key]; } else { $params[] = $pp[2][$key]; } }
		 */

		// TODO: make compare char-by-char parser vs regexp
		foreach (str_split($param_str.',') as $idx => $char)
		{
			if (! $quoted)
			{
				if ($char == ' ') continue;
				if ($char == '=')
				{
					$name = $token;
					$token = '';
				}
				elseif ($char == ',')
				{
					$val = $token;
					$token = '';
					if ($name)
					{
						$params[$name] = $val;
					}
					else
					{
						$params[] = $val;
					}
				}
				elseif (strpos('"\'`', $char) !== false)
				{
					$quoted = true;
					$token .= $char;
				}
				else $token .= $char;
			}
			else
			{
				if (strpos('"\'`', $char) !== false)
				{
					$quoted = false;
				}
				$token .= $char;
			}
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
 *     $tag[3] — rest of all (parameters)
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
			if (trim($tag[3])) $chunk = chunk_parse_params($chunk, $tag[3]);
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


<?php

/**
 * CoTemplate extended tags block class
 *
 * Used for block being parsed for custom tags with external defined handler
 */
class Cotpl_etblock
{
	/**
	 * @var string Unique identifier for block
	 */
	public $uid = null;
	/**
	 * @var array List of parent tags ids
	 */
	public $parents = array();
	/**
	 * @var array List of this block tags
	*/
	public $tag_list = array();
	/**
	 * @var string Extended tag type token for last found tag
	*/
	public $etag_type = '';
	/**
	 * @var array List of all processed etags
	 */
	public static $tags = array();
	/**
	 * @var Cotpl_ethandler Handler for founded tag
	*/
	private $handler = null;

	/**
	 * Simplified constructor
	 *
	 * @param array $parents Array of parent tags id while processing nested block
	 */
	function __construct($parents = array())
	{
		$this->uid = cot_unique();
		$this->parents = $parents;
	}

	/**
	 * Parsing block
	 *
	 * @param string $code Block content to parse
	 * @return mixed
	 */
	function parse($code)
	{
		// loop for defined handlers
		foreach (XTemplate::$extender_registry as $etag_type => $handler)
		{
			if (function_exists($handler->parser))
			{
				// Set type to use it later within parser
				$this->etag_type = $etag_type;
				// Set last used handler
				$this->handler = $handler;
				$code = preg_replace_callback(
					$handler->tag_re,
					array($this, 'parse_etag'),
					$code
				);
			}
		}
		return $code;
	}

	/**
	 * Custom handler caller
	 *
	 * @param array $m Result array for tag PCRE search.
	 * @return mixed
	 */
	function parse_etag($m)
	{
		return call_user_func($this->handler->parser, $this, $m, $this->etag_type);
	}

	/**
	 * Register new tag within block
	 *
	 * @param Cotpl_etag $tag
	 */
	function link_tag(Cotpl_etag $tag)
	{
		$this->tag_list[] = $tag;
	}

}

/**
 * CoTemplate extended tag class
 */
class Cotpl_etag{
	/**
	 * @var int Tag ID
	 */
	public $id = null;
	/**
	 * @var string Tag name identifier
	 */
	public $name = '';
	/**
	 * @var string Tag full name qualifier ('type.name')
	 */
	public $fullname = '';
	/**
	 * @var Cotpl_etblock Instance of container block (parent block)
	 */
	private $block = null;
	/**
	 * @var string Tag type token
	 */
	private $tag_type = '';
	/**
	 * @var array PCRE search array with tag data
	 */
	// private $tag = array(); // PCRE search results for tag
	/**
	 * @var int Global tags counter
	 */
	private static $count = 0;

	/**
	 * Extended tag class constructor
	 *
	 * @param Cotpl_etblock $block Instance of conteiner Block
	 * @param string $tag_name Tag name
	 */
	function __construct(Cotpl_etblock $block, $tag_name = '')
	{
		$this->id = Cotpl_etag::$count ++;
		$this->name = $tag_name;
		$this->block = $block;
		$this->tag_type = $block->etag_type;
		$this->fullname = $block->etag_type . '.' . $tag_name;
		$block->link_tag($this); // linking to container block
		Cotpl_etblock::$tags[$this->id] = $this; // register in global list
	}

	/**
	 * Check for loops within nested tags
	 *
	 * @param int $allowed_loops Allowed loops count
	 * @return string boolean
	 */
	function looped($allowed_loops = 0)
	{
		$block = $this->block;
		foreach ($block->parents as $tag_id)
		{
			$parent_tag = Cotpl_etblock::$tags[$tag_id];
			if ($parent_tag->fullname == $this->fullname)
			{
				if (! $allowed_loops)
				{
					$looped_chain = '';
					foreach ($block->parents as $tag_id)
					{
						$looped_chain .= Cotpl_etblock::$tags[$tag_id]->fullname . ' > ';
					}
					return $looped_chain .= $this->fullname;
				}
				else
				{
					$allowed_loops--;
				}
			}
		}
		return false;
	}
}

/**
 * Simple extended tags handler class
 */
class Cotpl_ethandler
{
	/**
	 * @var string PCRE
	 */
	public $tag_re = '//';
	/**
	 * @var string Name of external parser funciton
	 */
	public $parser = null;

	function __construct($et_regexp, $handler_function)
	{
		$this->parser = $handler_function;
		$this->tag_re = $et_regexp;
	}
}


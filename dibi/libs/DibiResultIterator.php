<?php

/**
 * dibi - tiny'n'smart database abstraction layer
 * ----------------------------------------------
 *
 * @copyright  Copyright (c) 2005, 2010 David Grudl
 * @license    http://dibiphp.com/license  dibi license
 * @link       http://dibiphp.com
 * @package    dibi
 */



/**
 * External result set iterator.
 *
 * This can be returned by DibiResult::getIterator() method or using foreach
 * <code>
 * $result = dibi::query('SELECT * FROM table');
 * foreach ($result as $row) {
 *    print_r($row);
 * }
 * unset($result);
 * </code>
 *
 * Optionally you can specify offset and limit:
 * <code>
 * foreach ($result->getIterator(2, 3) as $row) {
 *     print_r($row);
 * }
 * </code>
 *
 * @copyright  Copyright (c) 2005, 2010 David Grudl
 * @package    dibi
 */
class DibiResultIterator implements Iterator, Countable
{
	/** @var DibiResult */
	private $result;

	/** @var int */
	private $offset;

	/** @var int */
	private $limit;

	/** @var int */
	private $row;

	/** @var int */
	private $pointer;


	/**
	 * @param  DibiResult
	 * @param  int  offset
	 * @param  int  limit
	 */
	public function __construct(DibiResult $result, $offset = NULL, $limit = NULL)
	{
		$this->result = $result;
		$this->offset = (int) $offset;
		$this->limit = $limit === NULL ? -1 : (int) $limit;
	}



	/**
	 * Rewinds the iterator to the first element.
	 * @return void
	 */
	public function rewind(): void
	{
		$this->pointer = 0;
		$this->result->seek($this->offset);
		$this->row = $this->result->fetch();
	}



	/**
	 * Returns the key of the current element.
	 * @return int
	 */
	public function key(): int
	{
		return $this->pointer;
	}



	/**
	 * Returns the current element.
	 * @return int
	 */
	public function current(): int
	{
		return $this->row;
	}



	/**
	 * Moves forward to next element.
	 * @return void
	 */
	public function next(): void
	{
		//$this->result->seek($this->offset + $this->pointer + 1);
		$this->row = $this->result->fetch();
		$this->pointer++;
	}



	/**
	 * Checks if there is a current element after calls to rewind() or next().
	 * @return bool
	 */
	public function valid(): bool
	{
		return !empty($this->row) && ($this->limit < 0 || $this->pointer < $this->limit);
	}



	/**
	 * Required by the Countable interface.
	 * @return int
	 */
	public function count(): int
	{
		return $this->result->getRowCount();
	}

}

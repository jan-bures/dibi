<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 */

// no namespace



/**
 * DateTime with serialization and timestamp support for PHP 5.2.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
 */
class DateTime53 extends DateTime
{
	private array $fix;

	public function __sleep()
	{
		$this->fix = array($this->format('Y-m-d H:i:s'), $this->getTimezone()->getName());
		return array('fix');
	}



	public function __wakeup(): void
	{
		$this->__construct($this->fix[0], new DateTimeZone($this->fix[1]));
		unset($this->fix);
	}



	public function getTimestamp(): int
	{
		return (int) $this->format('U');
	}



	public function setTimestamp($timestamp): DateTime|DateTime53
	{
		return $this->__construct(date('Y-m-d H:i:s', $timestamp), new DateTimeZone($this->getTimezone()->getName())); // getTimeZone() crashes in PHP 5.2.6
	}

}

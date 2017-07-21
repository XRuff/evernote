<?php

namespace XRuff\Evernote;

use Nette\Object;

class Configuration extends Object
{
	/** @var bool $sandbox */
	public $sandbox;

	/** @var string $key */
	public $key;

	/** @var string $secret */
	public $secret;

	/** @var string $callback */
	public $callback;

	/**
	 * @param bool $sandbox
	 * @param string $key
	 * @param string $secret
	 * @param string $callback
	 */
	public function __construct(
		$sandbox,
		$key,
		$secret,
		$callback
	)
	{
		$this->sandbox = $sandbox;
		$this->key = $key;
		$this->secret = $secret;
		$this->callback = $callback;
	}
}

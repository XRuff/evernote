<?php

namespace XRuff\Evernote\DI;

use Nette;
use Nette\Utils\Validators;

class EvernoteExtension extends Nette\DI\CompilerExtension
{
	/** @var array $DEFAULTS */
	private static $DEFAULTS = [
		'sandbox' => false,
		'key' => null,
		'secret' => null,
		'callback' => null,
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig(self::$DEFAULTS);

		Validators::assert($config['sandbox'], 'boolean', 'sandbox');
		Validators::assert($config['key'], 'string', 'key');
		Validators::assert($config['secret'], 'string', 'secret');
		Validators::assert($config['callback'], 'string', 'callback');

		$configuration = $builder->addDefinition($this->prefix('config'))
			->setClass('XRuff\Evernote\Configuration')
			->setArguments([
				$config['sandbox'],
				$config['key'],
				$config['secret'],
				$config['callback'],
			]);

		$oauthHandler = $builder->addDefinition($this->prefix('evernoteOauthHandler'))
			->setClass('Evernote\Auth\OauthHandler')
			->setArguments([
				$config['sandbox'],
				true,
			]);

		$this->compiler->loadDefinitions($builder, $this->loadFromFile(__DIR__ . '/evernote.neon')['services'], $this->name);

		/*

		if ($config['userManager'] instanceof Statement) { // was an neon entity, must be valid presenter name with parameters
			$userManager = $config['userManager']->entity;
			$builder->addDefinition($this->prefix('slgUserManager'))
				->setClass($userManager);
		} else {
			throw new Nette\Utils\AssertionException("Please fix your configuration, expression 'userManager' does not look like a valid model.");
		}

		if ($config['tokenManager'] && $config['tokenManager'] instanceof Statement) {
			$tokenManager = $config['tokenManager']->entity;
			$builder->addDefinition($this->prefix('slgTokenManager'))
				->setClass($tokenManager);
		} else {
			$builder->addDefinition($this->prefix('slgTokenManager'))
				->setClass('XRuff\Components\SimpleLoginGate\Model\TokensRepository');
		}
		*/
	}

	/**
	 * @param Nette\Configurator $configurator
	 */
	public static function register(Nette\Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler) {
			$compiler->addExtension('evernote', new EvernoteExtension());
		};
	}
}

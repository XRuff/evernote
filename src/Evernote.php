<?php

namespace XRuff\Evernote;

use Evernote\Auth\OauthHandler;
use Evernote\Client;
use Evernote\Exception\AuthorizationDeniedException;
use Nette\Http\Session;
use Nette\Object;

class Evernote extends Object
{
	/** @var Nette\Http\Session $session */
	private $session;

	/** @var Configuration $config */
	private $config;

	/** @var OauthHandler $oauthHandler */
	private $oauthHandler;

	/** @var Client $client */
	private $client;

	/** @var Model $model */
	private $model;

	/**
	 * @param Session $session
	 * @param Configuration $config
	 */
	public function __construct(
		Session $session,
		Configuration $config
	)
	{
		$this->session = $session;
		$this->config = $config;
		$this->model = new Model();
	}

	public function getClient()
	{
		$evernoteSession = $this->session->getSection('evernote');

		if ($evernoteSession->token) {
			if (!$this->client) {
				$this->client = new Client($evernoteSession->token, $this->config->sandbox);
			}
			return $this->client;
		}

		return null;
	}

	public function getModel()
	{
		return $this->model;
	}

	public function authorize()
	{
		$this->oauthHandler = $this->createOauthHandler();
		$oauthData = $this->oauthHandler->authorize($this->config->key, $this->config->secret, $this->config->callback);
	}

	public function saveOauthToken() {
		$this->oauthHandler = $this->createOauthHandler();

		$evernoteSession = $this->session->getSection('evernote');
		try {
			$oauthData  = $this->oauthHandler->authorize($this->config->key, $this->config->secret, $this->config->callback);
			$evernoteSession->token = $oauthData['oauth_token'];
		} catch (AuthorizationDeniedException $e) {
			$evernoteSession->token = null;
			throw new Exception\AuthorizationDeniedException($e->getMessage());
		}
	}

	private function createOauthHandler()
	{
		return new OauthHandler($this->config->sandbox, true);
	}
}

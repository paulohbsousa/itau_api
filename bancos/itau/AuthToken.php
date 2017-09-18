<?php

namespace Bancos\Itau\Auth;
use Bancos\Itau\Config as Config;
class Token {

	private $autenticacao = null;

	private $expire = 0;

	private $created = 0;

	private $type;
	
	public function __construct(){

	}

	public function Autenticar($client_id = '' , $client_secret = ''){
		$headers['Authorization'] = 'Basic '.base64_encode($client_id .':'. $client_secret);
		$parameters['grant_type'] = 'client_credentials';
		$parameters['scope'] = Config\AuthConnection::scope;
		$parameters['client_id'] = $client_id;
		$parameters['client_secret'] = $client_secret;
		$client = new \ApiRestClient();

		$response = $client->post(Config\AuthConnection::ENDPOINT, $parameters, $headers);	
		if (!$response->response)
			throw new \ApiRestClientException('Resposta vazia do servidor de autorização!');
		$json = json_decode($response->response);

		if (isset($json->error))
			throw new \ApiRestClientException('Reposta retornou erro: ['.$json->error.']');
		if (!isset($json->access_token))
			throw new \Exception('Não foi possível resgatar o token de autorização!');

		$this->autenticacao = $json->access_token;
		$this->expire = $json->expires_in;
		$this->type = $json->token_type;
		$this->created = time();
	}

	public function setAutenticacao($v = null,$expire = 0){
		if (!$v)
			throw new \InvalidArgumentException('Um Token deve ser passado como argumento.');
		$this->autenticacao = $v;
		if ($expire)
			$this->expire = $expire;
		else
			$this->expire = time() + 240;
	}

	public function getAutenticacao(){
		return $this->autenticacao;
	}

	public function getExpire(){
		return $this->expire;
	}

	public function getCreated(){
		return $this->created;
	}

	public function getType(){
		return $this->type;
	}

}
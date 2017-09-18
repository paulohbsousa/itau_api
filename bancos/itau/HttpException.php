<?php

namespace Bancos\Itau\Exception;

class Http extends \Exception {
	public static function status($status = null){
		switch ($status){
			case 200:
				return true;
			
			case 400:
				throw new Http('Conteúdo Mal-Formado.');
			case 401:
				throw new Http('O usuário e senha ou token de acesso são inválidos.');
			case 403:
				throw new Http('O acesso à API está bloqueado ou o usuário está bloqueado.');
			case 404:
				throw new Http('O endereço acessado não existe.');
			case 429:
				throw new Http('O usuário atingiu o limite de requisições.');
			case 500:
				throw new Http('Houve um erro interno do servidor ao processar a requisição.');
			case 422:
				return false;
		}
	}
}
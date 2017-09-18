<?php

namespace Bancos\Itau\Config;

class ApiConnection {
	const ENDPOINT = 'https://gerador-boletos.itau.com.br/router-gateway-app/public/codigo_barras/registro';
}

class AuthConnection {
	const ENDPOINT = 'https://oauth.itau.com.br/identity/connect/token';
	const scope = 'readonly';
	const grant_type = 'client_credentials';
}
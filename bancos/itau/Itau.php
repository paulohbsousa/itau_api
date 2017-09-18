<?php

namespace Bancos;
use \Bancos\Itau\Exception as ExceptionPackage;
use \Bancos\Itau\Constants as Constants;
use \Bancos\Itau\Boleto as Boleto;
use \Bancos\Itau\RecebimentoDivergente as RecebimentoDivergente;
use \Bancos\Beneficiario as Beneficiario;
use \Bancos\Pagador as Pagador;
use Bancos\Itau\Config as Config;

class Itau extends \Bancos\Banco {
	
	private $tipo_ambiente;

	private $tipo_registro;

	private $tipo_produto;

	private $subproduto;

	private $object;

	private $beneficiario;

	private $pagador;

	private $moeda;

	private $autenticador;

	private $recebimento_divergente;

	private $debito;

	public function __construct($argumentos = array()){
		if (!isset($argumentos['tipo_ambiente']) || 
			!isset($argumentos['tipo_registro']) || 
			!isset($argumentos['tipo_cobranca']) ||
			!isset($argumentos['tipo_produto']) ||
			!isset($argumentos['subproduto'])
		){
			throw new ExceptionPackage\ItauException('Faltam Argumentos para a criar a classe Itau');
		}

		if ($this->valoresAceitos('Bancos\\Itau\\Constants\\TipoAmbiente',$argumentos['tipo_ambiente'])){
			$this->tipo_ambiente = $argumentos['tipo_ambiente'];
		} else {
			throw new ExceptionPackage\ItauException('tipo_ambiente inválido');
		}

		if ($this->valoresAceitos('Bancos\\Itau\\Constants\\TipoRegistro',$argumentos['tipo_registro'])){
			$this->tipo_registro = $argumentos['tipo_registro'];
		} else {
			throw new ExceptionPackage\ItauException('tipo_registro inválido');
		}

		if ($this->valoresAceitos('Bancos\\Itau\\Constants\\TipoCobranca',$argumentos['tipo_cobranca'])){
			$this->tipo_cobranca = $argumentos['tipo_cobranca'];
		} else {
			throw new ExceptionPackage\ItauException('tipo_cobranca inválido');
		}

		if ($this->valoresAceitos('Bancos\\Itau\\Constants\\TipoProduto',$argumentos['tipo_produto'])){
			$this->tipo_produto = $argumentos['tipo_produto'];
		} else {
			throw new ExceptionPackage\ItauException('tipo_produto inválido');
		}

		if ($this->valoresAceitos('Bancos\\Itau\\Constants\\SubProduto',$argumentos['subproduto'])){
			$this->subproduto = $argumentos['subproduto'];
		} else {
			throw new ExceptionPackage\ItauException('subproduto inválido');
		}

		switch ($this->tipo_cobranca){
			case 1:
				$this->object = new Boleto('N',$argumentos['tipo_carteira']);
			break;
			default:
				throw new ExceptionPackage\ItauException('tipo_cobranca não implementada ainda');
			break;
		}

		$this->autenticador = new \Bancos\Itau\Auth\Token();

	}

	public function setCobrancaBoleto($dados = array(),$juros, $multa){
		$this->recebimento_divergente = new RecebimentoDivergente(Constants\TipoAutorizacaoRecebimento::NAO_ACEITA);
		if (isset($dados['nosso_numero']))
			$this->object->setNossoNumero($dados['nosso_numero']);
		else
			throw new ExceptionPackage\ItauException('Falta nosso número.');

		if (isset($dados['numero_contrato_proposta']))
			$this->object->setNumeroContratoProposta($dados['numero_contrato_proposta']);
		else
			throw new ExceptionPackage\ItauException('Falta número contrato proposta.');

		if (isset($dados['data_vencimento']))
			$this->object->setDataVencimento($dados['data_vencimento']);
		else
			throw new ExceptionPackage\ItauException('Falta data de vencimento.');

		if (isset($dados['valor']))
			$this->object->setValor($dados['valor']);
		else
			throw new ExceptionPackage\ItauException('Falta valor do boleto.');

		if (isset($dados['data_emissao']))
			$this->object->setDataEmissao($dados['data_emissao']);
			
		if (isset($dados['codigo_barras']))
			$this->object->setCodigoBarras($dados['codigo_barras']);

		if (isset($dados['especie']))
			$this->object->setEspecie($dados['especie']);
		
		if (isset($dados['dv']))
			$this->object->setDV($dados['dv']);
		else
			throw new ExceptionPackage\ItauException('Falta DV do nosso número.');

		if (isset($dados['tipo_carteira']))
			$this->object->setTipoCarteira($dados['tipo_carteira']);
		else
			throw new ExceptionPackage\ItauException('Falta tipo_carteira do boleto.');

		if (isset($dados['seu_numero']))
			$this->object->setSeuNumero($dados['seu_numero']);

		if (isset($dados['pagamento_parcial']))
			$this->object->setPagamentoParcial($dados['pagamento_parcial']);

		if (isset($dados['quantidade_parcelas']))
			$this->object->setQuantidadeParcelas($dados['quantidade_parcelas']);

		if (isset($dados['tipo_pagamento']))
			$this->object->setTipoPagamento($dados['tipo_pagamento']);

		if (is_array($dados['instrucoes'])){
			$i = 1;
			foreach ($dados['instrucoes'] as $instrucao){
				$quantidade = isset($instrucao['quantidade']) ? isset($instrucao['quantidade']) : null;
				$data = isset($instrucao['data']) ? isset($instrucao['data']) : null;
				$this->object->setInstrucao($i++,$instrucao['instrucao'],$quantidade,$data);
			}
		}
	}

	public function cobrar(Itau\Auth\Token $auth = null , $identificador = '00000000000000'){
		if ($auth)
			$this->autenticador = $auth;
		if ( !$this->autenticador->getAutenticacao() ){
			$this->autenticador->Autenticar();
		}
		$headers['Content-Type'] = 'application/json';
		$headers['Accept'] = 'application/vnd.itau';
		$headers['access_token'] = $this->autenticador->getAutenticacao(); 
		//$headers['itau-chave'] = Config\ApiConnection::itauchave;
		$headers['identificador'] = $identificador;
		$json = $this->prepareJsonRequest();
		$client = new \ApiRestClient();
		$r = $client->post(Config\ApiConnection::ENDPOINT, $json, $headers);


		if ( !$r->info->http_code )
			throw new \ApiRestClientException('Erro fatal ao ler código de retorno!!');
		if ( !$r->response )
				throw new \ApiRestClientException('Resposta vazia do servidor de API!');
		if ( ExceptionPackage\Http::status($r->info->http_code) ){
			$response = json_decode($r->response);
			return array('objeto' => $response, 
				'status' => 'OK', 
				'status_message' => ''
			);
		} else {
			 //@TODO passar o argumento correto para o error_code
			$response = json_decode($r->response);
			return array('objeto' => $response, 
				'status' => $response->codigo, 
				'status_message' => ExceptionPackage\ItauException::error_code($response->codigo)
			);
		}
	}

	public function getAutenticador(){
		return $this->autenticador;
	}


	public function setMoeda(Moeda $moeda){
		try {
			$this->moeda = $moeda;
		} catch (Exception $e){
			throw new ExceptionPackage\ItauException($e->getMessage());
		} 
	}

	public function setBeneficiario(Beneficiario $beneficiario){
		try {
			$this->beneficiario = $beneficiario;
		} catch (Exception $e){
			throw new ExceptionPackage\ItauException($e->getMessage());
		} 
	}

	public function setPagador(Pagador $pagador){
		try {
			$this->pagador = $pagador;
		} catch (Exception $e){
			throw new ExceptionPackage\ItauException($e->getMessage());
		} 
	}

	private function prepareJsonRequest(){
		$json_request = array();

		$json_request['tipo_ambiente'] = $this->tipo_ambiente;
		$json_request['tipo_registro'] = $this->tipo_registro;
		$json_request['tipo_cobranca'] = $this->tipo_cobranca;
		$json_request['tipo_produto'] = $this->tipo_produto;

		if ($this->subproduto)
			$json_request['subproduto'] = $this->subproduto;

		if (!$this->beneficiario)
			throw new ExceptionPackage\ItauException('Falta Beneficiário!'); 
		$json_request['beneficiario']['cpf_cnpj_beneficiario'] = $this->beneficiario->getCpfCnpj();
		$json_request['beneficiario']['agencia_beneficiario'] = $this->beneficiario->getAgencia();
		$json_request['beneficiario']['conta_beneficiario'] = $this->beneficiario->getConta();
		$json_request['beneficiario']['digito_verificador_conta_beneficiario'] = $this->beneficiario->getDV();
		$json_request['beneficiario']['nome_beneficiario'] = $this->beneficiario->getNome();
		$json_request['beneficiario']['nome_fantasia_beneficiario'] = $this->beneficiario->getNome();
		$json_request['beneficiario']['logradouro_beneficiario'] = $this->beneficiario->getLogradouro();
		$json_request['beneficiario']['cidade_beneficiario'] = $this->beneficiario->getCidade();
		$json_request['beneficiario']['uf_beneficiario'] = $this->beneficiario->getUF();
		$json_request['beneficiario']['cep_beneficiario'] = $this->beneficiario->getCEP();

		if ($this->debito){
			//@TODO implementar métodos de debito
		}

		if (!$this->pagador)
			throw new ExceptionPackage\ItauException('Falta Pagador!'); 
		$json_request['pagador']['cpf_cnpj_pagador'] = $this->pagador->getCpfCnpj();
		$json_request['pagador']['nome_pagador'] = $this->pagador->getNome();
		$json_request['pagador']['logradouro_pagador'] = $this->pagador->getLogradouro();
		$json_request['pagador']['bairro_pagador'] = $this->pagador->getBairro();
		$json_request['pagador']['cidade_pagador'] = $this->pagador->getCidade();
		$json_request['pagador']['uf_pagador'] = $this->pagador->getUf();
		$json_request['pagador']['cep_pagador'] = $this->pagador->getCep();

		$emails = $this->pagador->getEmail();
		if ($emails){
			foreach($emails as $email){
				$json_request['pagador']['grupo_email_pagador']['email_pagador'] = $email;
			}
		}
		$reflector = new \ReflectionClass($this->object);
		switch ($reflector->getShortName()){
			case 'Boleto':
				$json_request['titulo_aceite'] = $this->object->getTituloAceite();
				$json_request['indicador_titulo_negociado'] = $this->object->getIndicadorTituloNegociado();
				$json_request['tipo_carteira_titulo'] = $this->object->getTipoCarteira();
				if (!$this->moeda)
					throw new ExceptionPackage\ItauException('Falta Moeda!'); 
				$json_request['moeda']['codigo_moeda_cnab'] = $this->moeda->getCodigo();
				if ($this->moeda->getQuantidade())
					$json_request['moeda']['codigo_moeda_cnab'] = $this->moeda->getQuantidade();
				$json_request['nosso_numero'] = $this->object->getNossoNumero();
				$json_request['numero_contrato_proposta'] = $this->object->getNumeroContratoProposta();
				$json_request['digito_verificador_nosso_numero'] = $this->object->getDV();
				if ($this->object->getCodigoBarras())
					$json_request['codigo_barras'] = $this->object->getCodigoBarras();
				$json_request['data_vencimento'] = $this->object->getDataVencimento();
				$json_request['data_limite_pagamento'] = $this->object->getDataVencimento(); //Campo obrigatório noa definido na documentação.
				$json_request['valor_cobrado'] = $this->object->getValor();
				if ($this->object->getSeuNumero())
					$json_request['seu_numero'] = $this->object->getSeuNumero();
				$json_request['especie'] = $this->object->getEspecie();
				$json_request['data_emissao'] = $this->object->getDataEmissao();
				$json_request['tipo_pagamento'] = $this->object->getTipoPagamento();
				$json_request['indicador_pagamento_parcial'] = $this->object->getPagamentoParcial();


				if ($this->object->getQuantidadeParcelas())
					$json_request['quantidade_parcelas'] = $this->object->getQuantidadeParcelas();
				$instrucoes = $this->object->getInstrucao();
				if ($instrucoes){
					foreach($instrucoes as $key => $instrucao){
						$json_request['instrucao_cobranca_'.$key] = $instrucao['instrucao'];
						if (isset($instrucao['quantidade']))
							$json_request['quantidade_dias_'.$key] = $instrucao['quantidade'];
						if (isset($instrucao['data']))
							$json_request['data_instrucao_'.$key] = $instrucao['data'];
					}
				}				
				$multa = $this->object->getMulta();

				if ($multa){
					$json_request['multa']['data_multa'] = $multa->getData();
					$json_request['multa']['tipo_multa'] = $multa->getTipo();
					$json_request['multa']['percentual_multa'] = $multa->getValor();
				}else{
					$json_request['multa']['tipo_multa'] = 3; //NAO SE APLICA
				}

				$juros = $this->object->getJuros();
				if ($juros){
					$json_request['juros']['data_juros'] = $juros->getData();
					$json_request['juros']['tipo_juros'] = $juros->getTipo();
					$json_request['juros']['percentual_juros'] = $juros->getValor();
				}else{
					$json_request['juros']['tipo_juros'] = 5; //NAO SE APLICA
				}

				if($this->object->getTipoCarteira() =='986' || $this->object->getTipoCarteira() =='885'|| $this->object->getTipoCarteira() =='175' ){
					$json_request['grupo_desconto']['tipo_desconto'] = 0;
					$json_request['recebimento_divergente']['tipo_autorizacao_recebimento'] = "3";
				}else{
					//@TODO - Carteiras Diferentes com descontos baseados na data de Vencimento.
					
					//@TODO - Carteiras Diferentes com Recebimentos divergentes.
					$json_request['recebimento_divergente']['tipo_autorizacao_recebimento'] = $this->recebimento_divergente->getTipoAutorizacao();
					if (isset($json_request['recebimento_divergente']['tipo_valor_percentual_recebimento']))
						$json_request['recebimento_divergente']['tipo_valor_percentual_recebimento'] = $this->recebimento_divergente->getTipoValor();
					
					if (isset($json_request['recebimento_divergente']['valor_percentual_minimo_recebimento']))
						$json_request['recebimento_divergente']['valor_percentual_minimo_recebimento'] = $this->recebimento_divergente->getMinimo();
					
					if (isset($json_request['recebimento_divergente']['valor_percentual_maximo_recebimento']))
						$json_request['recebimento_divergente']['valor_percentual_maximo_recebimento'] = $this->recebimento_divergente->getMaximo();

				}

			break;
			default:
				throw new ExceptionPackage\ItauException('Funcionalidade não implementada ainda');
			break;
		}		

		return json_encode($json_request);
	}

}
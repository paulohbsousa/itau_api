<?php

namespace Bancos\Itau;
use \Bancos\Itau\Exception as ExceptionPackage;
use \Bancos\Itau\Contants as Contants;

class Boleto implements \Bancos\IBoleto {

	private $titulo_aceite;

	private $indicador_titulo_negociado;

	private $nosso_numero;

	private $numero_contrato_proposta;

	private $data_vencimento;

	private $tipo_carteira;

	private $valor;

	private $data_emissao;

	private $codigo_barras;

	private $especie;

	private $dv;

	private $seu_numero;

	private $pagamento_parcial;

	private $tipo_pagamento;

	private $quantidade_parcelas;

	private $instrucao;

	private $multa;

	private $juros;

	public function __construct($titulo = 'N',$carteira = '986'){
		switch($titulo){
			case Constants\TituloAceite::COBRANCA:
				$this->titulo_aceite = Constants\TituloAceite::COBRANCA;
				$this->indicador_titulo_negociado = Constants\IndicadorTituloNegociado::COBRANCA;
				$this->tipo_carteira = $carteira;
				$this->pagamento_parcial = false;
				$this->especie = Constants\Especie::DIVERSOS;
				$this->tipo_pagamento = 3;
				$this->data_emissao = date('Y-m-d');
			break;
			case Constants\TituloAceite::PROPOSTA:
				$this->titulo_aceite = Constants\TituloAceite::PROPOSTA;
				$this->indicador_titulo_negociado = Constants\IndicadorTituloNegociado::PROPOSTA;
				$this->tipo_carteira = $carteira;
				$this->pagamento_parcial = false;
				$this->especie = Constants\Especie::DIVERSOS;
				$this->tipo_pagamento = 3;
				$this->data_emissao = date('Y-m-d');
			break;
			default:
				throw new ExceptionPackage\ItauException('Titulo do Aceite inválido.');
			break;
		} 

	}

	public function setMulta($multa){
		$this->multa = $multa;
	}

	public function setJuros($juros){
		$this->juros = $juros;
	}
	
	public function setNossoNumero($nn){
		$this->nosso_numero = $nn;
	}

	public function setNumeroContratoProposta($ncp){
		if (substr($ncp,9,3) != $this->tipo_carteira)
			throw new \InvalidArgumentException('Número do contrato proposta inválido. As posições 10 à 12 devem conter a carteira do título '.$ncp.' -> '.$this->tipo_carteira);
		if (substr($ncp,12,8) != $this->nosso_numero)
			throw new \InvalidArgumentException('Número do contrato proposta inválido. As posições 13 à 20 devem conter o nosso número sem DAC ('.$ncp.')');
		$this->numero_contrato_proposta = $ncp;
	}

	public function setDataVencimento($vencimento){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$vencimento)){
			$this->data_vencimento = $vencimento;
		} else {
			throw new ExceptionPackage\ItauException('Formato de data de Vencimento inválido. Deve ser YYYY-MM-DD');
		}
	}

	public function setValor($valor){
		if ($valor > 10000000)
			throw new ExceptionPackage\ItauException('Valor do título deve ser inferior a 10.000.000,00');
		$this->valor = str_pad(number_format($valor,2,'',''),17,'0',STR_PAD_LEFT);
	}

	public function setDataEmissao($data){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$data)){
			$this->data_emissao = $data;
		} else {
			throw new ExceptionPackage\ItauException('Formato de data de Emissão inválido.');
		}
	}

	public function setCodigoBarras($codigo){
		if (strlen($codigo) > 44){
			$this->codigo_barras = substr($codigo,0,44);
		} else {
			$this->codigo_barras = str_pad($codigo,44,'0',STR_PAD_LEFT);
		}
	}	

	public function setEspecie($especie){
		$reflector = new \ReflectionClass('API\\Bancos\\Itau\\Constants\\Especie');
		foreach ( $reflector->getConstants() as $valor_aceito ) {
			$constant_values[] = $valor_aceito;
		}
		if (!in_array($especie,$constant_values) )
			throw new ExceptionPackage\ItauException('Especie inválida.');
		$this->especie = $especie;
	}

	// Metodos especificos da classe

	public function setDV($dv){
		if (strlen($dv) != 1)
			throw new ExceptionPackage\ItauException('Digito verificador inválido');
		$this->dv = $dv;
	}

	public function setTipoCarteira($tipo){
		$this->tipo_carteira = $tipo;
	}

	public function setSeuNumero($numero){
		$this->seu_numero = $numero;
	}

	public function setPagamentoParcial($parcial){
		$this->pagamento_parcial = $parcial;
	}

	public function setQuantidadeParcelas($parcelas){
		$this->quantidade_parcelas = $parcelas;
	}

	public function setInstrucao($numero_instrucao = 1,$instrucao = '02',$quantidade = null, $data = null){
		if (count($instrucao) < 3){
			if (!$instrucao || !in_array($instrucao,Constants\Instrucoes::$validas))
				throw new ExceptionPackage\ItauException('Instrução inválida.');
			$this->instrucao[$numero_instrucao]['instrucao'] = $instrucao;
			if (isset($quantidade))
				$this->instrucao[$numero_instrucao]['quantidade'] = $quantidade;
			if (isset($data))
				$this->instrucao[$numero_instrucao]['data'] = $data;
		} else {
			throw new ExceptionPackage\ItauException('Máximo de 3 instruções por boleto!');
		}
	}

	public function setTipoPagamento($tipo){
		if ($tipo == 1 || $tipo == 3)
			$this->tipo_pagamento = $tipo;
		throw new ExceptionPackage\ItauException('Tipo de pagamento inválido.');
	}

	public function getTituloAceite(){
		return $this->titulo_aceite;
	}

	public function getIndicadorTituloNegociado(){
		return $this->indicador_titulo_negociado;
	}	

	public function getTipoCarteira(){
		return $this->tipo_carteira;
	}

	public function getNossoNumero(){
		return $this->nosso_numero;
	}

	public function getDV(){
		return $this->dv;
	}

	public function getCodigoBarras(){
		return $this->codigo_barras;
	}

	public function getDataVencimento(){
		return $this->data_vencimento;
	}

	public function getValor(){
		return $this->valor;
	}

	public function getSeuNumero(){
		return $this->seu_numero;
	}

	public function getEspecie(){
		return $this->especie;
	}

	public function getDataEmissao(){
		return $this->data_emissao;
	}

	public function getTipoPagamento(){
		return $this->tipo_pagamento;
	}

	public function getPagamentoParcial(){
		return $this->pagamento_parcial;
	}

	public function getQuantidadeParcelas(){
		return $this->quantidade_parcelas;
	}

	public function getInstrucao(){
		return $this->instrucao;
	}

	public function getMulta(){
		return $this->multa;
	}

	public function getJuros(){
		return $this->juros;
	}

	public function getNumeroContratoProposta(){
		return $this->numero_contrato_proposta;
	}

}
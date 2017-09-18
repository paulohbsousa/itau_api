<?php

namespace Bancos;

class Moeda {

	private $codigo_moeda_cnab;

	private $quantidade_moeda;


	public function __construct($codigo = null,$quantidade = null){
		$this->codigo_moeda_cnab = isset($codigo) ? $codigo : '09';
		$this->quantidade_moeda = isset($quantidade) ? $quantidade : null;
	}

	public function getCodigo(){
		return $this->codigo_moeda_cnab;
	}

	public function getQuantidade(){
		return $this->quantidade_moeda;
	}

}
<?php

namespace Bancos\Itau;
use \Bancos\Itau\Exception as ExceptionPackage;

class Juros {

	private $data_juros;

	private $tipo_juros;

	private $valor_percentual;


	public function setData($v){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$v)){
			$this->data_juros = $v;
		} else {
			throw new ExceptionPackage\ItauException('Formato de data de Juros inválido.');
		}
	}

	public function setTipo($v){
		if ($v < 1 || $v > 9)
			throw new ExceptionPackage\ItauException('Tipo de Juros inválido.');
		$this->tipo_juros = $v;
	}

	public function setValor($v){
		$this->valor_percentual = str_pad(number_format($v,5,'',''),13,'0',STR_PAD_LEFT);
	}

	public function getData(){
		return $this->data_juros;
	}

	public function getTipo(){
		return $this->tipo_juros;
	}

	public function getValor(){
		return $this->valor_percentual;
	}
}
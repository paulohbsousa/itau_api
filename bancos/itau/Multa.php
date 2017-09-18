<?php

namespace Bancos\Itau;
use \Bancos\Itau\Exception as ExceptionPackage;

class Multa {

	private $data_multa;

	private $tipo_multa;

	private $valor_percentual_multa;


	public function setData($v){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$v)){
			$this->data_multa = $v;
		} else {
			throw new ExceptionPackage\ItauException('Formato de data de Multa inválido.');
		}
	}

	public function setTipo($v){
		if ($v < 1 || $v > 3)
			throw new ExceptionPackage\ItauException('Tipo de Multa inválido.');
		$this->tipo_multa = $v;
	}

	public function setValor($v){
		$this->valor_percentual_multa = str_pad(number_format($v,5,'',''),13,'0',STR_PAD_LEFT);
	}

	public function getData(){
		return $this->data_multa;
	}

	public function getTipo(){
		return $this->tipo_multa;
	}

	public function getValor(){
		return $this->valor_percentual_multa;
	}
}
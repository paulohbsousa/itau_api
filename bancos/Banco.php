<?php

namespace Bancos;

abstract class Banco {

	protected $tipo_cobranca;

	public abstract function setCobrancaBoleto($dados = array(),$juros, $multa);

	public function setTipoCobranca(int $v){
		$this->tipo_cobranca = $v;
	}

	public function getTipoCobranca(){
		return $this->tipo_cobranca;
	}

	protected function valoresAceitos($constant_class, $arg){
		$reflector = new \ReflectionClass($constant_class);
		foreach ( $reflector->getConstants() as $valor_aceito ) {
			$constant_values[] = $valor_aceito;
		}
		if (!in_array($arg,$constant_values) )
			return false;
		return true;
	}

}
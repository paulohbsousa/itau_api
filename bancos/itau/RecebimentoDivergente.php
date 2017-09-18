<?php

namespace Bancos\Itau;
use \Bancos\Itau\Constants as Constants;

class RecebimentoDivergente {

	private $tipo_autorizacao_recebimento;

	private $tipo_valor_percentual_recebimento;

	private $valor_percentual_minimo_recebimento;

	private $valor_percentual_maximo_recebimento;

	public function __construct($tipo_autorizacao_recebimento = null,$tipo_valor_percentual_recebimento = null,
						$valor_percentual_minimo_recebimento = null,$valor_percentual_maximo_recebimento = null){
		if (!isset($tipo_autorizacao_recebimento))
			throw new \InvalidArgumentException('Faltam argumentos para criar Recebimento Divergente');
		if ($tipo_autorizacao_recebimento == Constants\TipoAutorizacaoRecebimento::FAIXA_VALOR){
			if (!isset($valor_percentual_minimo_recebimento) || !isset($valor_percentual_maximo_recebimento)){
				throw new \InvalidArgumentException('Faltam valores minimos e máximos para recebimento divergente');
			} else {
				$this->valor_percentual_maximo_recebimento = $valor_percentual_maximo_recebimento;
				$this->valor_percentual_minimo_recebimento = $valor_percentual_minimo_recebimento;
			}
		}
		$this->tipo_autorizacao_recebimento = $tipo_autorizacao_recebimento;
		if (isset($tipo_valor_percentual_recebimento))
			$this->tipo_valor_percentual_recebimento = $tipo_valor_percentual_recebimento;
	}


	public function getTipoAutorizacao(){
		return $this->tipo_autorizacao_recebimento;
	}

	public function getTipoValor(){
		return $this->tipo_valor_percentual_recebimento;
	}

	public function getValorMinimo(){
		return $this->valor_percentual_minimo_recebimento;
	}

	public function getValorMaximo(){
		return $this->valor_percentual_maximo_recebimento;
	}

}
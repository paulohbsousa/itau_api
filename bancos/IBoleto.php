<?php

namespace Bancos;

interface IBoleto {

	public function setNossoNumero($nn);

	function setDataVencimento($vencimento);

	function setValor($valor);

	function setDataEmissao($data);

	function setCodigoBarras($codigo);

	function setEspecie($especie);

}
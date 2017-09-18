<?php

namespace Bancos\Itau\Constants;

class TipoAmbiente {
	
	const TESTES = 1;

	const PRODUCAO = 2;

}

class TipoRegistro {
	
	const REGISTRO = 1;

	const ALTERACAO = 2;

	const CONSULTA = 3;

}

class TipoCobranca {
	
	const BOLETOS = 1;

	const DEBITO_AUTOMATICO = 2;

	const CARTAO_DE_CREDITO = 3;

	const TEF_REVERSA = 4;

}

class TipoProduto {
	const CLIENTE = '00002';
	const CLIENTE175 = '00006';
}

class SubProduto {
	const CLIENTE = '00986';
	const CLIENTE175 = '00008';
}

class TituloAceite {
	
	const COBRANCA = 'S';

	const PROPOSTA = 'N';

}


class IndicadorTituloNegociado {
	
	const COBRANCA = 'S';

	const PROPOSTA = 'N';

}


class Especie {
	
	const DUPLICATA_MERCANTIL = '01';

	const NOTA_PROMISSORIA = '02';

	const NOTA_DE_SEGURO = '03';

	const MENSALIDADE_ESCOLAR = '04';

	const RECIBO = '05';

	const CONTRATO = '06';

	const COSSEGUROS = '07';

	const DUPLICATA = '08';

	const LETRA_DE_CAMBIO = '09';

	const NOTA_DE_DEBITOS = '13';

	const DOCUMENTO_DE_DIVIDA = '15';

	const ENCARGOS_CONDOMINIAIS = '16';

	const PRESTACAO_DE_SERVICOS = '17';

	const BOLETO_DE_PROPOSTA = '18';

	const DIVERSOS = '99';

}

class TipoPagamento {
	const A_VISTA = 1;

	const VENCIMENTO = 3;
}

class IndicadorPagamentoParcial {
	const ACEITA = true;
	const NAO_ACEITA = false;
}

class TipoMulta {
	const FIXO = 1;
	const PERCENTUAL = 2;
	const NAO = 3;
}

class TipoAutorizacaoRecebimento {
	const ACEITA = 1;
	const FAIXA_VALOR = 2;
	const NAO_ACEITA = 3;
}

class TipoValorPercentualRecebimento {
	const VALOR = 'V';
	const PERCENTUAL = 'P';
}

class TipoRateio {
	const PERCENTUAL_ORIGINAL = 1;
	const VALOR_ORIGINAL = 2;
	const PERCENTUAL_LIQUIDO = 3;
	const VALOR_LIQUIDO = 4;
}

class UF {
	public static $estados = array('AC','AL','AP','AM','BA','CE','DF','ES',
									'GO','MA','MT','MS','MG','PR','PB','PA',
									'PE','PI','RJ','RN','RS','RO','RR','SC','SE','SP','TO');
}

class Instrucoes {
	public static $validas = array('02','03','05','06','07','08','09','10','11','12','13','14','15','16','17','18',
		'19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','36','37','38','39','40','42',
		'43','44','45','47','51','52','53','54','56','57','58','59','61','62','66','67','78','79','80','81','82',
		'83','84','86','87','88','89','90','91','92','93','94','98'); 
}


class Boleto {
	
}
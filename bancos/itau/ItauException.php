<?php

namespace Bancos\Itau\Exception;

class ItauException extends \Exception {
	public static function error_code($code = null){
		switch ($code){
			case '02':
			return '';
			
			case '03':
				return 'AG. COBRADORA - NÃO FOI POSSÍVEL ATRIBUIR A AGÊNCIA PELO CEP OU CEP INVÁLIDO';
			case '04':
				return 'ESTADO - SIGLA DO ESTADO INVÁLIDA';
			case '05':
				return 'DATA DE VENCIMENTO - PRAZO DA OPERAÇÃO MENOR QUE O PRAZO MÍNIMO OU MAIOR QUE O MÁXIMO';
			case '07':
				return 'VALOR DO TÍTULO - VALOR DO TÍTULO MAIOR QUE 10.000.000,00';
			case '08':
				return 'NOME DO PAGADOR - NÃO INFORMADO OU DESLOCADO';
			case '09':
				return 'AGÊNCIA/CONTA - AGÊNCIA ENCERRADA';
			case '10':
				return 'LOGRADOURO - NÃO INFORMADO OU DESLOCADO';
			case '11':
				return 'CEP - CEP NÃO NUMÉRICO';
			case '12':
				return 'SACADOR/AVALISTA - NOME NÃO INFORMADO OU DESLOCADO';
			case '13':
				return 'ESTADO/CEP - CEP INCOMPATÍVEL COM A SIGLA DO ESTADO';
			case '14':
				return 'NOSSO NÚMERO - NOSSO NÚMERO JÁ REGISTRADO NO CADASTRO DO BANCO OU FORA DA FAIXA';
			case '15':
				return 'NOSSO NÚMERO - NOSSO NÚMERO EM DUPLICIDADE NO MESMO MOVIMENTO';
			case '18':
				return 'DATA DE ENTRADA - DATA DE ENTRADA INVÁLIDA PARA OPERAR COM ESTA CARTEIRA';
			case '19':
				return 'OCORRÊNCIA - OCORRÊNCIA INVÁLIDA';
			case '21':
				return 'AG. COBRADORA - ERRO 21';
			case '22':
				return 'CARTEIRA - CARTIERA NÃO PERMITIDA';
			case '27':
				return 'CNPJ INAPTO - CNPJ DO BENEFICIÁRIO INAPTO';
			case '29':
				return 'CÓDIGO EMPRESA - CATEGORIA DA CONTA INVÁLIDA';
			case '31':
				return 'AGÊNCIA/CONTA - AGÊNCIA/CONTA DO BENEFICIÁRIO SEM PERMISSÃO PARA PROTESTO';
			case '35':
				return 'VALOR DO IOF - IOF MAIOR QUE 5%';
			case '36':
				return 'QUANTIDADE DE MOEDA - QUANTIDADE DE MOEDA INCOMPATÍVEL COM VALOR DO TÍTULO';
			case '37':
				return 'CNPJ/CPF DO PAGADOR - NÃO NUMÉRICO OU IGUAL A ZEROS';
			case '42':
				return 'NOSSO NÚMERO - NOSSO NÚMERO FORA DE FAIXA';
			case '44':
				return 'AGENCIA/CONTA - CONTA MIGRADA';
			case '52':
				return 'AG. COBRADORA - EMPRESA NÃO ACEITA BANCO CORRESPONDENTE';
			case '53':
				return 'AG. COBRADORA - EMPRESA NÃO ACEITA BANCO CORRESPONDENTE';
			case '54':
				return 'DATA DE VENCIMENTO - BANCO CORRESPONDENTE - TÍTULO COM VENCIMENTO INFERIOR A 15 DIAS';
			case '55':
				return 'DEP/BANCO CORRESPONDENTE - CEP NÃO PERTENCE À DEPOSITÁRIA INFORMADA';
			case '56':
				return 'DATA VENCIMENTO/BANCO CORRESPONDENTE - VENCTO SUPERIOR A 180 DIAS DA DATA DE ENTRADA';
			case '57':
				return 'DATA DE VENCIMENTO - CEP SÓ DEPOSITÁRIA BCO DO BRASIL COM VENCTO INFERIOR A 8 DIAS';
			case '60':
				return 'ABATIMENTO - VALOR DO ABATIMENTO INVÁLIDO';
			case '61':
				return 'JUROS DE MORA - JUROS DE MORA MAIOR QUE O PERMITIDO';
			case '62':
				return 'DESCONTO - VALOR DO DESCONTO MAIOR QUE O VALOR DO TÍTULO';
			case '63':
				return 'DESCONTO DE ANTECIPAÇÃO - VALOR DA IMPORTÂNCIA POR DIA DE DESCONTO (IDD) NÃO PERMITIDO';
			case '64':
				return 'DATA DE EMISSÃO - DATA DE EMISSÃO DO TÍTULO INVÁLIDA';
			case '65':
				return 'TAXA FINANCTO - TAXA INVÁLIDA (VENDOR)';
			case '66':
				return 'DATA DE VENCIMENTO - INVALIDA/FORA DE PRAZO DE OPERAÇÃO (MINIMO OU MAXIMO)';
			case '67':
				return 'VALOR/QUANTIDADE - VALOR DO TÍTULO/QUANTIDADE DE MOEDA INVÁLIDO';
			case '68':
				return 'CARTEIRA - CARTEIRA INVÁLIDA';
			case '69':
				return 'CARTEIRA - CARTEIRA INVÁLIDA PARA TÍTULOS COM RATEIO DE CRÉDITO';
			case '70':
				return 'AGÊNCIA/CONTA - BENEFICIÁRIO NÃO CADASTRADO PARA FAZER RATEIO DE CRÉDITO';
			case '78':
				return 'AGÊNCIA/CONTA - DUPLICIDADE DE AGÊNCIA/CONTA BENEFICIÁRIA DO RATEIO DE CRÉDITO';
			case '80':
				return 'AGÊNCIA/CONTA - QUANTIDADE DE CONTAS BENEFICIÁRIAS DO RATEIO MAIOR DO QUE O PERMITIDO (MÁXIMO DE 30 CONTAS POR TÍTULO)';
			case '81':
				return 'AGÊNCIA/CONTA - CONTA PARA RATEIO DE CRÉDITO INVÁLIDA/NÃO PERTENCE AO ITAÚ';
			case '82':
				return 'DESCONTO/ABATIMENTO - DESCONTO/ABATIMENTO NÃO PERMITIDO PARA TÍTULOS COM RATEIO DE CRÉDITO';
			case '83':
				return 'VALOR DO TÍTULO - VALOR DO TÍTULO MENOR QUE A SOMA DOS VALORES ESTIPULADOS PARA RATEIO';
			case '84':
				return 'AĜENCIA/CONTA - AGÊNCIA/CONTA BENEFICIÁRIA DO RATEIO É A CENTRALIZADORA DE CRÉDITO DO BENEFICIÁRIO';
			case '85':
				return 'AGÊNCIA/CONTA - AGÊNCIA/CONTA DO BENEFIÁRIO É CONTRATUAL. RATEIO DE CRÉDITO NÃO PERMITIDO';
			case '86':
				return 'TIPO DE VALOR - CÓDIGO DO TIPO DE VALOR INVÁLIDO / NÃO PREVISTO PARA TÍTULOS COM RATEIO DE CRÉDITO';
			case '87':
				return 'AGÊNCIA/CONTA - REGISTRO TIPO 4 SEM INFORMAÇÃO DE AGÊNCIAS/CONTAS BENEFICIÁRIAS DO RATEIO';
			case '90':
				return 'NUMERO DA LINHA - COBRANÇA MENSAGEM. NÚMERO DA LINHA DA MENSAGEM INVÁLIDO';
			case '91':
				return 'DAC - DAC AGÊNCIA/CONTA CORRENTE INVÁLIDO';
			case '92':
				return 'DAC - DAC AGÊNCIA/CONTA/CARTEIRA/NOSSO NÚMERO INVÁLIDO';
			case '93':
				return 'ESTADO - SIGLA ESTADO INVÁLIDA';
			case '94':
				return 'ESTADO - SIGLA ESTADO INCOMPATÍVEL COM CEP DO PAGADOR';
			case '95':
				return 'CEP - CEP DO PAGADOR NÃO NUMÉRICO OU INVÁLIDO';
			case '96':
				return 'ENDEREÇO - ENDEREÇO / NOME / CIDADE PAGADOR INVÁLIDO';
			case '97':
				return 'SEM MENSAGEM - COBRANÇA MENSAGEM SEM MENSAGEM, PORÉM COM REGISTRO DO TIPO 7 OU 8';
			case '98':
				return 'FLASH INVÁLIDO - REGISTRO MENSAGEM SEM FLASH CADASTRADO OU FLASH INFORMADO DIFERENTE DO CADASTRADO';
			case '99':
				return 'FLASH INVÁLIDO - CONTA DE COBRANÇA COM FLASH CADASTRADO E SEM REGISTRO DE MENSAGEM CORRESPONDENTE';
			case 'XX':
				return 'BENEFICIÁRIO - BENEFICIÁRIO NÃO AUTORIZADO A EMITIR BOLETOS PARA ESTA CONTA';	
			default:
				throw new ItauException('Código de Erro desconhecido. ['.$code.']');
			break;
		}
	}
}
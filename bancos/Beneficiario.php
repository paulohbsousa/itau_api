<?php

namespace Bancos;

class Beneficiario {

	private $cpfcnpj;

	private $nome;

	private $nome_fantasia;

	private $agencia;

	private $conta;

	private $dv;
	
	private $logradouro;

	private $cidade;

	private $cep;	

	private $uf;	

	public function __construct($agencia = null,$conta = null,$dv = null,$cpfcnpj = null,$nome = '',$nome_fantasia = '', $cep = '' ,$uf = '', $cidade = '', $logradouro = ''){
		if (!isset($agencia) || !isset($conta))
			throw new \InvalidArgumentException('Faltam argumentos para criar beneficiario');
		$cpfcnpj = str_replace(array('.','-','/'),'',$cpfcnpj);
		if (strlen($cpfcnpj) < 14){
			if (strlen($cpfcnpj) > 11){
				$this->cpfcnpj = str_pad($cpfcnpj,14,'0',STR_PAD_LEFT);
			} else {
				$this->cpfcnpj = str_pad($cpfcnpj,11,'0',STR_PAD_LEFT);
			}
		} else {
			$this->cpfcnpj = substr($cpfcnpj,0,14);
		}

		if (strlen($nome) > 50){
			$this->nome = substr($nome,0,50);
		} else {
			$this->nome = $nome;
		}

		if (strlen($nome_fantasia) > 50){
			$this->nome_fantasia = substr($nome_fantasia,0,50);
		} else {
			$this->nome_fantasia = $nome_fantasia;
		}

		if (strlen($agencia) < 4){
			$this->agencia = str_pad($agencia,4,'0',STR_PAD_LEFT);
		} else {
			$this->agencia = substr($agencia,0,4);
		}

		$conta = str_replace(array('.','-','/'),'',$conta);

		if (isset($dv)){
			$this->dv = substr($dv,0,1);
		} else {
			$this->dv = substr($conta,-1);
			$conta = substr($conta,0,-1);
		}
		if (strlen($conta) < 7){
			$this->conta = str_pad($conta,7,'0',STR_PAD_LEFT);
		} else {
			$this->conta = substr($conta,0,7);
		}

		$this->logradouro = $logradouro;
		$this->cidade = $cidade;
		$this->uf = $uf;
		$this->cep = $cep;

	}

	public function getCpfCnpj(){
		return $this->cpfcnpj;
	}

	public function getAgencia(){
		return $this->agencia;
	}

	public function getConta(){
		return $this->conta;
	}

	public function getDV(){
		return $this->dv;
	}

	public function getNome(){
		return $this->nome;
	}

	public function getLogradouro(){
		return $this->logradouro;
	}	

	public function getCidade(){
		return $this->cidade;
	}	

	public function getUF(){
		return $this->uf;
	}	

	public function getCEP(){
		return $this->cep;
	}	

}
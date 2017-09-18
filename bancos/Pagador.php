<?php

namespace Bancos;
use \Bancos\Itau\Constants as Constants;

class Pagador {

	private $cpfcnpj;

	private $nome;

	private $logradouro;

	private $bairro;

	private $cidade;

	private $uf;

	private $cep;

	private $email = array();

	public function __construct($cpfcnpj = null,$nome = null,$logradouro = null,$bairro = null,$cidade = null,$uf = null, $cep = null){
		if (!isset($cpfcnpj) || !isset($nome) || !isset($logradouro) 
			|| !isset($bairro) || !isset($cidade) || !isset($uf) || !isset($cep))
			throw new \InvalidArgumentException('Faltam argumentos para criar pagador');
		$cpfcnpj = str_replace(array('.','-','/'),'',$cpfcnpj);
		if (strlen($cpfcnpj) < 14){
			$this->cpfcnpj = str_pad($cpfcnpj,14,'0',STR_PAD_LEFT);
		} else {
			$this->cpfcnpj = substr($cpfcnpj,0,14);
		}

		$this->nome = substr($nome,0,30);

		$this->logradouro = substr($logradouro,0,40);

		$this->bairro = substr($bairro,0,15);

		$this->cidade = substr($cidade,0,20);

		$this->uf = substr($uf,0,2);
		if (!in_array($this->uf,Constants\UF::$estados))
			throw new \InvalidArgumentException('UF não pertence à uma unidade federativa.');

		$cep = str_replace('-', '', $cep);

		if (strlen($cep) < 8){
			throw new \InvalidArgumentException('CEP inválido');
		} else {
			$this->cep = $cep;
		}
	}

	public function addEmail($email){
		if (count($this->email) < 5){
			if (filter_var($email, FILTER_VALIDATE_EMAIL)){
				$this->email[] = $email;
			} else {
				throw new \InvalidArgumentException('Formato inválido de e-mail');
			}
		} else {
			throw new \InvalidArgumentException('Limite de e-mails é 5');
		}
	}

	public function getEmail(){
		return $this->email;
	}

	public function getCpfCnpj(){
		return $this->cpfcnpj;
	}

	public function getNome(){
		return $this->nome;
	}

	public function getLogradouro(){
		return $this->logradouro;
	}

	public function getBairro(){
		return $this->bairro;
	}

	public function getCidade(){
		return $this->cidade;
	}

	public function getUf(){
		return $this->uf;
	}

	public function getCep(){
		return $this->cep;
	}

}
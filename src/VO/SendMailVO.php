<?php

namespace Src\VO;

use Src\_public\Util;
use Src\VO\LogErro;

class SendMailVO extends LogErro{

private $id_email;
private $destinatario;
private $mensagem;
private $assunto;
private $data_envio;
private $anexo;
private $nome_anexo;
private $tamanho_arquivo;





/**
 * Get the value of id_email
 */ 
public function getId_email()
{
return $this->id_email;
}

/**
 * Set the value of id_email
 *
 * @return  self
 */ 
public function setId_email($id_email)
{
$this->id_email = $id_email;

return $this;
}

/**
 * Get the value of destinatario
 */ 
public function getDestinatario()
{
return $this->destinatario;
}

/**
 * Set the value of destinatario
 *
 * @return  self
 */ 
public function setDestinatario($destinatario)
{
$this->destinatario = $destinatario;

return $this;
}

/**
 * Get the value of mensagem
 */ 
public function getMensagem()
{
return $this->mensagem;
}

/**
 * Set the value of mensagem
 *
 * @return  self
 */ 
public function setMensagem($mensagem)
{
$this->mensagem = $mensagem;

return $this;
}

/**
 * Get the value of assunto
 */ 
public function getAssunto()
{
return $this->assunto;
}

/**
 * Set the value of assunto
 *
 * @return  self
 */ 
public function setAssunto($assunto)
{
$this->assunto = $assunto;

return $this;
}

/**
 * Get the value of data_envio
 */ 
public function getData_envio()
{
return $this->data_envio;
}

/**
 * Set the value of data_envio
 *
 * @return  self
 */ 
public function setData_envio($data_envio)
{
$this->data_envio = $data_envio;

return $this;
}

/**
 * Get the value of anexo
 */ 
public function getAnexo()
{
return $this->anexo;
}

/**
 * Set the value of anexo
 *
 * @return  self
 */ 
public function setAnexo($anexo)
{
$this->anexo = $anexo;

return $this;
}

/**
 * Get the value of nome_anexo
 */ 
public function getNome_anexo()
{
return $this->nome_anexo;
}

/**
 * Set the value of nome_anexo
 *
 * @return  self
 */ 
public function setNome_anexo($nome_anexo)
{
$this->nome_anexo = $nome_anexo;

return $this;
}

/**
 * Get the value of tamanho_arquivo
 */ 
public function getTamanho_arquivo()
{
return $this->tamanho_arquivo;
}

/**
 * Set the value of tamanho_arquivo
 *
 * @return  self
 */ 
public function setTamanho_arquivo($tamanho_arquivo)
{
$this->tamanho_arquivo = $tamanho_arquivo;

return $this;
}
}
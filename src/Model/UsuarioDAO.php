<?php

namespace Src\model;

use Exception;
use Src\_public\Util;
use Src\Model\Conexao;
use Src\Model\SQL\UsuarioSQL;
use Src\Model\SQL\EnderecoSQL;
use Src\VO\EmpresaVO;
use Src\VO\UsuarioVO;

class usuarioDAO extends Conexao
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = parent::retornaConexao();
    }

    public function RetornaDadosCadastraisDAO(): array
    {
        $sql = $this->conexao->prepare(UsuarioSQL::RetornarDadosCadastrais());
        $sql->bindValue(1, Util::CodigoLogado());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);

    }
    public function AlterarEmpresaDAO(EmpresaVO $vo):int
    {
        if ($vo->getEmpLogo()=='') {
        
        $sql = $this->conexao->prepare(UsuarioSQL::AlterarEmpresaSLSQL());
        $sql->bindValue(1, $vo->getNomeEmpresa());
        $sql->bindValue(2, $vo->getCNPJ());
        $sql->bindValue(3, $vo->getEndereco());
        $sql->bindValue(4, $vo->getCep());
        $sql->bindValue(5, $vo->getNumero());
        $sql->bindValue(6, $vo->getCidade());
        $sql->bindValue(7, Util::EmpresaLogado());
        }else {
            
        
        $sql = $this->conexao->prepare(UsuarioSQL::AlterarEmpresaSQL());
        $sql->bindValue(1, $vo->getNomeEmpresa());
        $sql->bindValue(2, $vo->getCNPJ());
        $sql->bindValue(3, $vo->getEndereco());
        $sql->bindValue(4, $vo->getCep());
        $sql->bindValue(5, $vo->getNumero());
        $sql->bindValue(6, $vo->getCidade());
        $sql->bindValue(7, $vo->getEmpLogo());
        $sql->bindValue(8, $vo->getLogoPath());
        $sql->bindValue(9, Util::EmpresaLogado());
    }
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -2;
        }


    }



    public function VerificarEmailDuplicadoDAO($id, $email)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::SelecionarEmailDuplicado($id));
        $i = 1;
        $sql->bindValue($i++, $email);
        if (!empty($id)) {
            $sql->bindValue($i++, $id);
        }
        $sql->execute();
        return  $sql->fetch(\PDO::FETCH_ASSOC)['login'] == '' ? true : false;
    }

    public function FiltrarPessoaDAO($nome, $filtro)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::FILTRAR_USUARIO($nome, $filtro));
        $sql->bindValue(1, Util::EmpresaLogado());
        if (!empty($nome)) {
            $sql->bindValue(2, '%' . $nome . '%');
        }
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }


/* public function CadastrarPermissaoDAO(UsuarioVO $vo)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::CadastrarPermissaoSQL());
        $sql->bindValue(1, $vo->getAdmin());
        $sql->bindValue(2, $vo->get());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }






 */







    public function RetornarUsuariosDAO()
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::RETORNAR_USUARIOS());
        $sql->bindValue(1, Util::EmpresaLogado());
        $sql->execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function RecuperarSenhaAtual($id)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::RECUPERARSENHAATUAL());
        $sql->bindValue(1, $id);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function AtualizarSenhaAtual(UsuarioVO $vo)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::ATUALIZAR_SENHA());
        $sql->bindValue(1, $vo->getSenha());
        $sql->bindValue(2, $vo->getId());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function ValidarLoginDAO($login, $status)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::BUSCAR_DADOS_ACESSO());
        $sql->bindValue(1, $login);
        $sql->bindValue(2, $status);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function CriarLogUsuario(){
        $sql = $this->conexao->prepare(UsuarioSQL::CRIAR_LOG_USUARIO_SQL());
        $i = 1;
        $sql->bindValue($i++, Util::CodigoLogado());
        $sql->bindValue($i++, Util::DataAtualBd());
        $sql->bindValue($i++, Util::HoraAtual());
        $sql->bindValue($i++, Util::LogIPUsuario());
        $sql->execute();
        return 1;
    }

    public function ValidarAcesso($login, $status, $tipo)
    {
        $sql =  $this->conexao->prepare(UsuarioSQL::VALIDAR_ACESSO());
        $sql->bindValue(1, $login);
        $sql->bindValue(2, $status);
        $sql->bindValue(3, 3);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }




    public function MudarStatusDAO(UsuarioVO $vo)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::MUDAR_STATUS());
        $sql->bindValue(1, $vo->getStatus());
        $sql->bindValue(2, $vo->getId());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }

    public function CadastrarUsuarioDAO($vo)
    {
        if ($vo->getEmpID()>0) {
            $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_EMPRESA());
            $sql->bindValue(1, Util::DataAtualBd());
            $sql->execute();
    
            $idEmpresa = $this->conexao->lastInsertId();
            
        }

        # Cadastra usuario
        $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_USUARIO());
        $i = 1;
        $sql->bindValue($i++, $vo->getTipo());
        $sql->bindValue($i++, $vo->getNome());
        $sql->bindValue($i++, $vo->getLogin());
        $sql->bindValue($i++, $vo->getSenha());
        $sql->bindValue($i++, $vo->getStatus());
        $sql->bindValue($i++, $vo->getTelefone());
        if ($vo->getEmpID()>0) {
            $sql->bindValue($i++, $vo->getEmpID());  
        }else{
            $sql->bindValue($i++, $idEmpresa);
        }
        $this->conexao->beginTransaction();
        try {
            $sql->execute();
            # Recupera o ID recem cadastrado
            $idUser = $this->conexao->lastInsertId();
            # Processo de cadastrar a cidade e estado
            $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_CIDADE());
            $sql->bindValue(1, '%' . $vo->getNomeCidade() . '%');
            $sql->execute();
            $temCidade = $sql->fetchAll(\PDO::FETCH_ASSOC);
            # Verifica se encontrou cidade e estado
            if (count($temCidade) == 0) { # Verifica a cidade
                # Seleciona o estado
                $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_ESTADO());
                $sql->bindValue(1, '%' . $vo->getEstado() . '%');
                $sql->execute();
                $temEstado = $sql->fetchAll(\PDO::FETCH_ASSOC);
                # Verifica o estado
                if (count($temEstado) == 0) {
                    # cadastra o estado
                    $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ESTADO());
                    $sql->bindValue(1, $vo->getEstado());
                    $sql->execute();
                    $idEstado = $this->conexao->lastInsertId();
                } else {
                    $idEstado = $temEstado[0]['id'];
                }
                # Cadastra a cidade
                $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_CIDADE());
                $i = 1;
                $sql->bindValue($i++, $vo->getNomeCidade());
                $sql->bindValue($i++, $idEstado);
                $sql->execute();
                $idCidade = $this->conexao->lastInsertId();
            } else {
                $idCidade = $temCidade[0]['id'];
            }
            # Cadastrar endereço
            $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ENDERECO());
            $i = 1;
            $sql->bindValue($i++, $vo->getRua());
            $sql->bindValue($i++, $vo->getBairro());
            $sql->bindValue($i++, $vo->getCep());
            $sql->bindValue($i++, $idCidade);
            $sql->bindValue($i++, $idUser);
            $sql->execute();
            # Verificar o tipo de usuario 1-Administrador, 2-Funcionário ou 3-Tecnico
            switch ($vo->getTipo()) {
                case '2': # Cadastra Funcionário
                    $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_FUNCIONARIO());
                    $i = 1;
                    $sql->bindValue($i++, $idUser);
                    $sql->bindValue($i++, $vo->getSetor());
                    $sql->execute();
                    break;
                case '3': # Cadastra Tecnico
                    $sql = $this->conexao->prepare(UsuarioSQL::INSERIR_TECNICO());
                    $i = 1;
                    $sql->bindValue($i++, $idUser);
                    $sql->bindValue($i++, $vo->getNomeEmpresa());
                    $sql->execute();
                    break;
            }
            $this->conexao->commit();
            return 1;
        } catch (Exception $ex) {
            $this->conexao->rollBack();
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }




 public function AlterarImagemUsuarioDAO(UsuarioVO $vo)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::ALTERAR_IMAGEM());
        $sql->bindValue(1, $vo->getUserLogo());
        $sql->bindValue(2, $vo->getUserLogoPath());
        $sql->bindValue(3, $vo->getId());
        try {
            $sql->execute();
            return 1;
        } catch (\Exception $ex) {

            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }










    public function AlterarUsuarioDAO($vo)
    {
        # Cadastra usuario
        $sql = $this->conexao->prepare(UsuarioSQL::ALTERAR_USUARIO($vo->getSenha()));
        $i = 1;
        $sql->bindValue($i++, $vo->getTipo());
        $sql->bindValue($i++, $vo->getNome());
        $sql->bindValue($i++, $vo->getLogin());
        if ($vo->getSenha()!="") {
            $sql->bindValue($i++, $vo->getSenha());
        }
        $sql->bindValue($i++, $vo->getTelefone());
        $sql->bindValue($i++, $vo->getId());
        
        $this->conexao->beginTransaction();
        try {
            $sql->execute();

            $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_CIDADE());
            $sql->bindValue(1, '%' . $vo->getNomeCidade() . '%');
            $sql->execute();
            $temCidade = $sql->fetchAll(\PDO::FETCH_ASSOC);
            # Verifica se encontrou cidade e estado
            if (count($temCidade) == 0) { # Verifica a cidade

                # Seleciona o estado
                $sql = $this->conexao->prepare(EnderecoSQL::SELECIONAR_ESTADO());
                $sql->bindValue(1, '%' . $vo->getEstado() . '%');
                $sql->execute();
                $temEstado = $sql->fetchAll(\PDO::FETCH_ASSOC);
                # Verifica o estado
                if (count($temEstado) == 0) {
                    # cadastra o estado
                    $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_ESTADO());
                    $sql->bindValue(1, $vo->getEstado());
                    $sql->execute();
                    $idEstado = $this->conexao->lastInsertId();
                } else {
                    $idEstado = $temEstado[0]['id'];
                }
                # Cadastra a cidade
                $sql = $this->conexao->prepare(EnderecoSQL::INSERIR_CIDADE());
                $i = 1;
                $sql->bindValue($i++, $vo->getNomeCidade());
                $sql->bindValue($i++, $idEstado);
                $sql->execute();
                $idCidade = $this->conexao->lastInsertId();
            } else {
                $idCidade = $temCidade[0]['id'];
            }
            # Cadastrar endereço
            $sql = $this->conexao->prepare(EnderecoSQL::ALTERAR_ENDERECO());
            $i = 1;
            $sql->bindValue($i++, $vo->getRua());
            $sql->bindValue($i++, $vo->getBairro());
            $sql->bindValue($i++, $vo->getCep());
            $sql->bindValue($i++, $idCidade);
            $sql->bindValue($i++, $vo->getidEndereco());
            $sql->execute();
            # Verificar o tipo de usuario 1-Administrador, 2-Funcionário ou 3-Tecnico
            switch ($vo->getTipo()) {
                case '2': # Cadastra Funcionário
                    $sql = $this->conexao->prepare(UsuarioSQL::ALTERAR_FUNCIONARIO());
                    $i = 1;
                    $sql->bindValue($i++, $vo->getSetor());
                    $sql->bindValue($i++, $vo->getId());
                    $sql->execute();
                    break;
                case '3': # Cadastra Tecnico
                    $sql = $this->conexao->prepare(UsuarioSQL::ALTERAR_TECNICO());
                    $i = 1;
                    $sql->bindValue($i++, $vo->getNomeEmpresa());
                    $sql->bindValue($i++, $vo->getId());
                    $sql->execute();
                    break;
            }
            $this->conexao->commit();
            return 1;
        } catch (Exception $ex) {
            $this->conexao->rollBack();
            $vo->setmsg_erro($ex->getMessage());
            parent::GravarLogErro($vo);
            return -1;
        }
    }


    public function DetalharUsuarioDAO($idUser)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::DETALHAR_USUARIO());
        $sql->bindValue(1, $idUser);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    public function DetalharMeusDadosDAO($idUser)
    {
        $sql = $this->conexao->prepare(UsuarioSQL::DETALHAR_MEUS_DADOS_SQL());
        $i = 1;
        $sql->bindValue($i++, $idUser);
        $sql->execute();
        return $sql->fetch(\PDO::FETCH_ASSOC);
    }
}

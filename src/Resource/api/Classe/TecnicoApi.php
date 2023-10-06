<?php

namespace Src\Resource\api\Classe;

use Src\_public\Util;
use Src\Controller\ChamadoController;
use Src\Resource\api\Classe\ApiRequest;
use Src\Controller\UsuarioController;
use Src\Controller\EquipamentoController;
use Src\Controller\LoteController;
use Src\VO\UsuarioVO;
use Src\VO\ChamadoVO;
use Src\VO\Lote_insumoVO;
use Src\Controller\ClienteController;
use Src\VO\ClienteVO;
use Src\VO\LoteVO;
use Src\VO\TecnicoVO;

class TecnicoApi extends ApiRequest
{

    private $ctrl_user;
    private $params;

    public function AddParameters($p)
    {
        $this->params = $p;
    }

    public function CheckEndPoint($endpoint)
    {
        return method_exists($this, $endpoint);
    }

    public function __construct()
    {
        $this->ctrl_user = new UsuarioController;
    }

    public function Autenticar()
    {
        return (new UsuarioController)->ValidarAcessoTecnicoAPI($this->params['email'], $this->params['senha']);
    }


    public function DetalharMeusDados()
    {
        if (Util::AuthenticationTokenAccess()) {
            if (empty($this->params['id_user'])) {
                return 0;
            }
            return $this->ctrl_user->DetalharUsuarioController($this->params['id_user']);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function AtenderChamado()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ChamadoVO;

            $vo->setId($this->params['id_chamado']);
            $vo->setTecnico_atendimento($this->params['id_tec']);

            return (new ChamadoController)->AtenderChamadoController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function AbrirChamado()
    {
        $vo = new ChamadoVO();

        $vo->setTecnico_atendimento($this->params['id_user']);
        $vo->setDescrciaoProblema($this->params['problema']);
        $vo->setNumero_nf($this->params['numero_nf']);
        $vo->setDefeito($this->params['defeito']);
        $vo->setObservacao($this->params['observacao']);
        $vo->setCliente_id($this->params['cliente_id']);
        $vo->setEmpresa_id($this->params['empresa_id']);
        $vo->setLoteID($this->params['lote']);
        return (new ChamadoController)->AbrirChamadoController($vo);
    }
    public function AlterarMeusDados()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new TecnicoVO;
            
            $vo->setId($this->params['id_user']);
            $vo->setNome($this->params['nome']);
            $vo->setLogin($this->params['login']);
            $vo->setTelefone($this->params['telefone']);
            $vo->setIdEndereco($this->params['id_end']);
            $vo->setRua($this->params['rua']);
            $vo->setBairro($this->params['bairro']);
            $vo->setCep($this->params['cep']);
            $vo->setEstado($this->params['estado']);
            $vo->setSenha($this->params['senha']);
            $vo->setNomeCidade($this->params['cidade']);
            $vo->setTipo(PERFIL_TECNICO);
            $vo->setNomeEmpresa($this->params['empresa']);

            return (new UsuarioController)->AlterarUsuarioController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }


     public function AlterarImagemUser()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new TecnicoVO;
            
            $vo->setId($this->params['id_user']);
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $arquivos = $_FILES['imagem'];
            if ($arquivos['name'] != "") {
                if ($arquivos['size'] > 2097152) { # 2097152 = 2MB
                    $ret = 10;
                } else {
                    $pasta = CAMINHO_PARA_SALVAR_IMG_USER;
                    @mkdir($pasta);
                    $nomeDoArquivo = $arquivos['name'];
                    $novoNomeDoArquivo = uniqid();
                    $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
                    if ($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != '') {
                        $ret = 11;
                    } else {
                        $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
                        $deu_certo = move_uploaded_file($arquivos["tmp_name"], $path);
                        $vo->setUserLogo($nomeDoArquivo);
                        $vo->setUserLogoPath($path);
                        return (new UsuarioController)->AlterarImagemUsuarioController($vo);
                    }
                }
            }
        }
            
        } else {
            return NAO_AUTORIZADO;
        }
    }
































    public function InserirLoteAPI()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new LoteVO;

            $vo->setNumero_lote($this->params['numero_lote']);
            $vo->setEquipamento_id($this->params['equipamento_id']);
            $vo->setEmpresa_id($this->params['empresa_id']);
            $vo->setQtdEquip($this->params['qtd_equip']);
            $vo->setData_lote($this->params['data_lote']);

            /* $vo->setInsumo_id($this->params['insumo_id']);
            $vo->setValor_insumo($this->params['valor_insumo']);
            $vo->setQuantidade_insumo($this->params['quantidade_insumo']);
            
            $vo->setServico_id($this->params['servico_id']);
            $vo->setValor_servico($this->params['valor_servico']);
            $vo->setQuantidade_servico($this->params['quantidade_servico']); */

            return (new LoteController)->InserirLoteController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function ImportarEquipamentoSQL()
    {
        // Verifica se o arquivo foi enviado corretamente
        if (isset($_FILES['excel']) && $_FILES['excel']['error'] === UPLOAD_ERR_OK) {
            $excel = $_FILES['excel'];

            $numero_lote = $this->params['numero_lote'];
            $equipamento_id = $this->params['equipamento_id'];
            $empresa_id = $this->params['empresa_id'];
            $qtd_equip = $this->params['qtd_equip'];
            $data_lote = $this->params['data_lote'];

            // Verifique o tipo de arquivo para garantir que é um Excel
            $allowedFormats = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            if (!in_array($excel['type'], $allowedFormats)) {
                return array(
                    'status' => -12, // Formato de arquivo não suportado
                );
            }

            return (new LoteController)->ImportarEquipamentoController($excel, $numero_lote, $equipamento_id, $empresa_id, $qtd_equip, $data_lote);
        } else {
            return 33;
        }
    }




    public function FiltrarLote()
    {
        if (Util::AuthenticationTokenAccess()) {
            return (new LoteController)->FiltrarLoteController($this->params['empresa_id'], $this->params['filtro'] ?? '', $this->params['status'] ?? '');
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function InativarLoteAPI()
    {
        return (new LoteController)->InativarLoteController($this->params['status'], $this->params['lote_id'], $this->params['empresa']);
    }
    public function ReabrirLoteAPI()
    {
        return (new LoteController)->ReabrirLoteController($this->params['status'], $this->params['lote_id'], $this->params['empresa']);
    }


    public function FiltrarChamadoAberto()
    {
        if (Util::AuthenticationTokenAccess()) {
            return (new ChamadoController)->FiltrarChamadoAbertoController();
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function RetornarEquipamentosLoteAPI()
    {
        if (Util::AuthenticationTokenAccess()) {
            return (new ChamadoController)->RetornarEquipamentosLoteController($this->params['LoteID'], $this->params['empresa_id']);
        } else {
            return NAO_AUTORIZADO;
        }
    }



    public function FiltrarChamadoGeral()
    {
        if (Util::AuthenticationTokenAccess()) {
            return (new ChamadoController)->FiltrarChamadoGeralController($this->params['empresa_id'], $this->params['situacao'], $this->params['id_setor'] ?? '');
        } else {
            return NAO_AUTORIZADO;
        }
    }
    public function FiltrarPorNF()
    {
        if (Util::AuthenticationTokenAccess()) {
            return (new ChamadoController)->FiltrarNFController($this->params['empresa_id'], $this->params['buscar_nf']);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function RetornarClientes()
    {
        return (new ClienteController)->SelecioneClienteCTRL($this->params['empresa_id'], $this->params['tipo']);
    }

    public function RetornaLoteAPI()
    {
        return (new LoteController)->RetornaLoteController();
    }

    public function FinalizarChamado()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new ChamadoVO;
            $vo->setId($this->params['id_chamado']);
            $vo->setTecnicoEncerramento($this->params['id_tec']);
            $vo->setLaudoTecnico($this->params['laudo']);
            $vo->setAlocar($this->params['id_alocado']);

            return (new ChamadoController)->FinalizarChamadoController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }
    public function CarregarProdServOS()
    {

        return (new ChamadoController)->CarregarProdServOSController($this->params['chamado_id']);
    }

    public function BuscarInsumoServico()
    {
        return (new LoteController)->ConsultarInsumosController($this->params['equipamento_id']);
    }

    public function BuscarInsumoServicoLoteAPI()
    {
        return (new LoteController)->ConsultarInsumoServicoLoteController($this->params['lote_equip_id']);
    }
    public function BuscarServicosLoteAPI()
    {
        return (new LoteController)->ConsultarServicosLoteController($this->params['lote_equip_id']);
    }

    public function BuscarProdutosLoteAPI()
    {
        return (new LoteController)->ConsultarProdutosLoteController($this->params['lote_equip_id']);
    }


    public function FiltrarEquipamentoLoteAPI()
    {
        return (new LoteController)->FiltrarEquipamentoLoteController($this->params['lote_id']);
    }

    public function EditarEquipamentoLoteAPI()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new LoteVO;
            $vo->setId_lote_equip($this->params['equipamento_id']);
            $vo->setNumero_serie($this->params['numeroSerie']);
            $vo->setVersao($this->params['versao']);

            return (new LoteController)->EditarEquipamentoLoteController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }

    public function EncerramentoLoteAPI()
    {
        if (Util::AuthenticationTokenAccess()) {
            $vo = new LoteVO;
            $vo->setId($this->params['lote_id']);
            $vo->setStatus('E');

            return (new LoteController)->EncerramentoLoteController($vo);
        } else {
            return NAO_AUTORIZADO;
        }
    }


    public function BuscarServicoLote()
    {
        return (new LoteController)->ConsultarServicoLoteController($this->params['equipamento_id']);
    }

    public function GravarDadosLoteGeral()
    {

        $produtos = $this->params['Produtos'];
        $lote_equip_id = $this->params['lote_equip_id'];

        return (new LoteController)->GravarDadosLoteController($produtos, $lote_equip_id);
    }

    public function GravarDadosServLoteGeral()
    {

        $servicos = $this->params['Servicos'];
        $lote_equip_id = $this->params['lote_equip_id'];

        return (new LoteController)->GravarDadosServLoteController($servicos, $lote_equip_id);
    }


    public function ConsultarEquipamento()
    {
        return (new EquipamentoController)->ConsultarEquipamentoAllController($this->params['empresa_id'], $this->params['tipo']);
    }


    /* public function AbrirChamado()
    {
        $vo = new ChamadoVO();

        $vo->setId($this->params['id_user']);
        $vo->setDescrciaoProblema($this->params['problema']);
        $vo->setAlocar($this->params['id_alocar']);

        return (new ChamadoController)->AbrirChamadoController($vo);
    }

    public function SelecionarEquipamentosAlocadosSetorController()
    {
        if (empty($this->params['id_setor'])) {
            return 0;
        }

        return (new EquipamentoController)->SelecionarEquipamentosAlocadosSetorController($this->params['id_setor']);
    }

    public function FiltrarChamado()
    {
        return (new ChamadoController)->FiltrarChamadoController($this->params['situacao']);
    }
*/
    public function VerificarSenhaAtual()
    {

        return (new UsuarioController)->ValidarSenhaAtual($this->params['id'], $this->params['senha']);
    }

    public function DetalharCliente()
    {
        return (new ClienteController)->DetalharClienteController($this->params['id_cliente']);
    }

    public function EditarCliente()
    {
        $vo = new ClienteVO();
        $vo->setCliNome($this->params['CliNome']);
        $vo->setCliCpfCnpj($this->params['CliCpfCnpj']);
        $vo->setCliCep($this->params['CliCep']);
        $vo->setCliCidade($this->params['CliCidade']);
        $vo->setCliEmail($this->params['CliEmail']);
        $vo->setCliEndereco($this->params['CliEndereco']);
        $vo->setCliEstado($this->params['CliEstado']);
        $vo->setCliBairro($this->params['CliBairro']);
        $vo->setCliDescricao($this->params['CliDescricao']);
        $vo->setCliNumero($this->params['CliNumero']);
        $vo->setCliID($this->params['CliID']);
        $vo->setCliTelefone($this->params['CliTelefone']);
        $vo->setCliDtNasc(Util::formatarDataParaBanco($this->params['CliDtNasc']));
        $vo->setCilTipo($this->params['CliTipo']);
        $vo->setCliEmpID($this->params['empresa_id']);
        return (new ClienteController)->AlterarClienteCTRL($vo);
    }

    

    public function AtualizarSenha()
    {
        $vo = new UsuarioVO;

        $vo->setId($this->params['id']);
        $vo->setSenha($this->params['senha']);
        return (new UsuarioController)->AtualizarSenhaAtual($vo, $this->params['repetir_senha']);
    }
}

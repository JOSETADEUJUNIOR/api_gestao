
<?php
include_once '_include_autoload.php';

use Src\_public\Util;
Util::VerificarLogado();
use Src\Controller\ChamadoController;
use Src\Controller\UsuarioController;

$dados = [];
$chamadosPorFuncionario = [];

$ctrl = new ChamadoController();
$userController = new UsuarioController();

$dadosUser = $userController->DetalharUsuarioController($_SESSION['id']);
$dados = $userController->RetornarDadosCadastraisController();




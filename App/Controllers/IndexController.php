<?php

namespace App\Controllers;

//Recursos
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action
{

	public function index()
	{

		$main = Container::getModel('Main');

		$main->verifyLogin();

		@$this->view->qtdFuncs = $main->getTotFuncs();
		$this->view->qtdEntra = $main->getTotEntradas();
		$this->view->qtdSaida = $main->getTotSaidas();
		$this->view->admin = $main->getAdminName();
		$this->render('index', 'layout1');
	}

	public function showLogin()
	{

		$main = Container::getModel('Main');

		if (isset($_GET['loginError'])) {
			$main->setError("Usuário ou login inválido!");
			@$this->view->loginError = $main->getError();
		}

		$this->render('login', 'layout2');
	}

	public function formAdmin()
	{
		$main = Container::getModel('Main');

		if (isset($_GET['cad'])) {
			if ($_GET['cad'] == "error") {
				$main->setError("Não foi possível cadastrar");
				@$this->view->cadError = $main->getError();
			} elseif ($_GET['cad'] == "success") {
				$main->setSuccess("Cadastrado com sucesso");
				@$this->view->cadSuccess = $main->getSuccess();
			}
		}

		$this->render('admin-create', 'layout2');
	}

	public function getAdmin()
	{

		$main = Container::getModel('Main');

		$main->verifyLogin();

		$admin = $main->getAdmin($_SESSION['id_admin']);

		@$this->view->infoAdmin = $admin;
		@$this->view->admin = $main->getAdminName();
		$this->render('admin-perfil', 'layout1');
	}

	public function listaFuncionarios()
	{

		$main = Container::getModel('Main');

		$main->verifyLogin();

		$msgError = "";
		if (isset($_GET['filtro']) && $_GET['filtro'] == "vazio") {
			$main->setError("Nenhum resultado encontrado!");
			$msgError = $main->getError();
			@$this->view->msgError = $msgError;
		}

		@$this->view->search = isset($_GET['search']) ? $_GET['search'] : "";
		@$this->view->dataCriacao = isset($_GET['data']) ? $_GET['data'] : "";

		@$this->view->admin = $main->getAdminName();

		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		$funcionarios = $main->getPage($page);

		@$this->view->data = $funcionarios;

		$this->render('funcionarios', 'layout1');
	}

	public function listaFuncionariosFiltrados()
	{

		$search = isset($_POST['search']) ? $_POST['search'] : '';
		$dataCriacao = isset($_POST['data_criacao']) ? $_POST['data_criacao'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$data = "";

		function inverteData($data){
			if(count(explode("/",$data)) > 1){
				return implode("-",array_reverse(explode("/",$data)));
			}elseif(count(explode("-",$data)) > 1){
				return implode("/",array_reverse(explode("-",$data)));
			}
		}

		if(strlen($dataCriacao) > 2){
		$data = inverteData($dataCriacao);
		}else{
			$data = $dataCriacao;
		}

		$main = Container::getModel('Main');

		$main->verifyLogin();

		@$this->view->admin = $main->getAdminName();

		$funcionarios = $main->getPageSearch($search, $data, $page);

		@$this->view->data = $funcionarios;

		if ($this->view->data['data'] == NULL) {
			header("Location: /admin/funcionarios?filtro=vazio");
		}
		$this->view->dataCriacao = $dataCriacao;

		$this->view->search = $search;

		$this->render('funcionarios', 'layout1');
	}



	public function funcionarioInfo()
	{
		$idfunc = "";
		if (isset($_GET['id'])) {
			$idfunc = $_GET['id'];
		} else {
			header("Location: /admin/funcionarios");
		}

		$main = Container::getModel('Main');

		$main->verifyLogin();

		$msgError = "";
		$msgSuccess = "";

		if (isset($_GET['alter'])) {
			if ($_GET['alter'] == "error") {
				$main->setError("Não foi possível alterar!");
				$msgError = $main->getError();
				@$this->view->msgError = $msgError;
			} elseif ($_GET['alter'] == "success") {
				$main->setSuccess("Alterado com sucesso");
				$msgSuccess = $main->getSuccess();
				@$this->view->msgSuccess = $msgSuccess;
			}
		}

		@$this->view->admin = $main->getAdminName();

		$funcionario = $main->get($idfunc);

		$admin = $main->getAdmin($funcionario[0]['administrador_id']);

		@$this->view->data = $funcionario;
		@$this->view->adminPeloId = $admin['nome_completo'];

		$this->render('funcionario-update', 'layout1');
	}

	public function formFunc()
	{
		$main = Container::getModel('Main');
		$msgError = "";
		$msgSuccess = "";

		$main->verifyLogin();

		if (isset($_GET['cad'])) {
			if ($_GET['cad'] == "error") {
				$main->setError("Não foi possível cadastrar!");
				$msgError = $main->getError();
				@$this->view->cadSuccess = $msgError;
			} elseif ($_GET['cad'] == "success") {
				$main->setSuccess("Cadastrado com sucesso");
				$msgSuccess = $main->getSuccess();
				@$this->view->cadSuccess = $msgSuccess;
			}
		}

		@$this->view->admin = $main->getAdminName();

		$this->render('funcionarios-create', 'layout1');
	}

	public function getPageExtractFromId()
	{
		$main = Container::getModel('Main');

		$main->verifyLogin();
		@$this->view->admin = $main->getAdminName();

		$idfunc = 0;
		if (isset($_GET['id'])) {
			$idfunc = (int)$_GET['id'];
		} else {
			header("Location: /admin/funcionarios");
		}

		$extratos = $main->getPageExtractFromId($idfunc);

		$this->view->extratos = $extratos;

		$this->render('funcionario-extrato', 'layout1');
	}


	public function formExtrato()
	{
		$idfunc = 0;
		if (isset($_GET['id'])) {
			$idfunc = (int)$_GET['id'];
		} else {
			header("Location: /admin/funcionarios");
		}

		$main = Container::getModel('Main');

		if (isset($_GET['cad']) && $_GET['cad'] == "error") {
			$main->setError("Não foi possível cadastrar!");
			$msgError = $main->getError();
			@$this->view->msgError = $msgError;
		}

		if (isset($_GET['cad']) && $_GET['cad'] == "success") {
			$main->setSuccess("Cadastrado com sucesso");
			$msgSuccess = $main->getSuccess();
			@$this->view->msgSuccess = $msgSuccess;
		}

		$main->verifyLogin();
		@$this->view->admin = $main->getAdminName();
		@$this->view->idfunc = $idfunc;
		$this->view->idadmin = (int)$_SESSION['id_admin'];

		$this->render('movimentacao-create', 'layout1');
	}

	public function listaExtratos()
	{
		$main = Container::getModel('Main');

		if (isset($_GET['erro']) && $_GET['erro'] == "true") {
			$main->setError("Nenhum resultado encontrado!");
			$msgError = $main->getError();
			@$this->view->msgError = $msgError;
		}
		$main->verifyLogin();
		@$this->view->admin = $main->getAdminName();

		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		$DataGet = "";
		$Fname = "";

		if (isset($_GET['nomeFunc'])) {
			$Fname = $_GET['nomeFunc'];
		}
		if (isset($_GET['datacriacao'])) {
			$DataGet = $_GET['datacriacao'];
		}
		@$this->view->nomeFunc = $Fname;
		@$this->view->dataCriacao = $DataGet;

		$extratos = $main->getPageExtract($page);

		$this->view->extratos = $extratos;

		$this->render('extract-lista', 'layout1');
	}

	public function listaExtratosSearch()
	{

		$tipo = isset($_POST['tipo_movimentacao']) ? $_POST['tipo_movimentacao'] : '';
		$nome = isset($_POST['nome_funcionario']) ? $_POST['nome_funcionario'] : '';
		$data_criacao = isset($_POST['data_criacao']) ? $_POST['data_criacao'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		$main = Container::getModel('Main');

		$main->verifyLogin();

		$data = "";

		function inverteDataExtratct($data){
			if(count(explode("/",$data)) > 1){
				return implode("-",array_reverse(explode("/",$data)));
			}elseif(count(explode("-",$data)) > 1){
				return implode("/",array_reverse(explode("-",$data)));
			}
		}

		if(strlen($data_criacao) > 2){
		$data = inverteDataExtratct($data_criacao);
		}else{
			$data = $data_criacao;
		}

		@$this->view->admin = $main->getAdminName();

		$extratos = $main->getPageExtractSearch($tipo, $nome, $data, $page);

		$this->view->extratos = $extratos;

		if ($this->view->extratos['data'] == NULL) {
			header("Location: /admin/extracts/listagem?erro=true&nomeFunc=$nome&datacriacao=$data_criacao");
		}
		$this->view->nomeFunc = $nome;
		$this->view->dataCriacao = $data_criacao;

		$this->render('extract-lista', 'layout1');
	}
}

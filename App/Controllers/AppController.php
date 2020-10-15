<?php
namespace App\Controllers;

//Recursos
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function login(){

        $main = Container::getModel('Main');
        $login = $main->login(trim($_POST['login']) , md5($_POST['password']));
     
       if($login === false){
            header("Location: /admin/login?loginError=erro");
       }

       header("Location: /");

    }
    
	public function logout(){
		$main = Container::getModel('Main');

		$main->logout();

		header("Location: /admin/login");
    }

    public function saveAdmin(){
        
		$nome = trim($_POST['nome_completo']);
		$login = trim($_POST['login']);
		$senha = trim(md5($_POST['password']));

        $main = Container::getModel('Main');

        if($main->saveAdmin($nome,$login,$senha)){
            header("Location: /admin/create?cad=success");
        }else{
            header("Location: /admin/create?cad=error");
        }
        

    }

    public function cadFuncionario(){
        $nome = trim(utf8_encode($_POST['nome_completo']));
        $login = trim($_POST['login']);
        $password = trim(md5($_POST['senha']));
        $saldo = 0;
        
        $saldo = number_format($_POST['saldo_atual'],2,'.',',');
      
        $main = Container::getModel('Main');

        $main->verifyLogin();

        if($main->cadFuncionario($nome,$login,$password,$saldo)){
            header("Location:/admin/funcionario/create?cad=success");
        }else{
            header("Location: /admin/funcionario/create?cad=error");
        }

    }

    public function Update(){
        $main = Container::getModel('Main');

        $nome = trim(utf8_encode($_POST['nome_completo']));
        $login = trim($_POST['login']);
        $saldo = number_format($_POST['saldo_atual'],2,'.',',');

        $main->verifyLogin();

        $idfunc = 0;

        if(isset($_GET['id'])){
            $idfunc = $_GET['id'];
        }else{
            header("Location: /admin/funcionarios");
        }
        
        if($main->update($idfunc,$nome,$login,$saldo)){
            header("Location: /admin/funcionario/info?id=$idfunc&alter=success");
        }else{
            header("Location: /admin/funcionario/info?id=$idfunc&alter=error");
        }
        
    }


    public function delete(){
        $idfunc = "";
		if(isset($_GET['id'])){
			$idfunc = $_GET['id'];
		}else{
			header("Location: /admin/funcionarios");
		}
        
        $main = Container::getModel('Main');

        $main->verifyLogin();

        if($main->delete($idfunc)){
            header("Location: /admin/funcionarios");
        }

    }

    public function cadExtrato(){
        $main = Container::getModel('Main');
        $main->verifyLogin();

        
        $tipo = $_POST['tipo_movimentacao'];
        $valor = number_format($_POST['valor'],2,'.',',');
        $observacao = "";

        if(isset($_POST['observacao'])){
            $observacao = $_POST['observacao'];
        }else{
            $observacao = "Não definido!";
        }

        $idfunc = (int)$_POST['funcionario_id'];
        $idadmin = (int)$_POST['administrador_id'];

        

        if($main->cadMovimentacao($tipo,$valor,$observacao,$idfunc,$idadmin)){
            header("Location: /admin/extract/create?id=$idfunc&cad=success");
        }else{
            header("Location: /admin/extract/create?id=$idfunc&cad=error");
        }

    }
  
}

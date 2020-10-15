<?php

namespace App\Models;

use MF\Model\Container;
use MF\Model\Model;

class Main extends Model{

	const SESSION = "";
	const ERROR = "UserError";
	const ERROR_REGISTER = "UserErrorRegister";
	const SUCCESS = "UserSucesss";

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

	// Função responsável por contar quantos funcionários estão cadastrados
	public function getTotFuncs(){

		$query = "SELECT * FROM tb_funcionario";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		return $stmt->rowCount();
	}

	// Função responsável por contar quantas movimentações do tipo Entrada
	public function getTotEntradas(){
		$query = "SELECT * FROM tb_movimentacao WHERE tipo_movimentacao = 'entrada'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		return $stmt->rowCount();
	}

	// Função responsável por contar quantas movimentações do tipo Saída
	public function getTotSaidas(){

		$query = "SELECT * FROM tb_movimentacao WHERE tipo_movimentacao = 'saida'";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		return $stmt->rowCount();
	}

	// // Função exclusiva para o administrador efetuar o login, está função recebe o login -> E-mail e a senha
	public function login($login, $password)
	{
		$query = "SELECT * FROM tb_administrador WHERE login = :login AND senha = :senha";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':login',$login);
		$stmt->bindParam(':senha', $password);
		$stmt->execute();

		$results = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ($stmt->rowCount() === 0) {
			return false;
		}
          
		session_start();

		$_SESSION[Main::SESSION] = utf8_encode($results['nome_completo']);
		$_SESSION['id_admin'] = $results['id'];

		return $results;
	}

	
	//Função que destri a sessão 
    public function logout()
	{
		session_start();
		session_destroy();
		$_SESSION[Main::SESSION] = "";
	}

	// Função utilizada para verificar se o administrador efetuou login
	public function verifyLogin()
	{	
		session_start();

		if(!isset($_SESSION['id_admin']) && $_SESSION[Main::SESSION] == ""){
			header("Location: /admin/login");
		}

	}

	public function getAdminName(){
		return $_SESSION[Main::SESSION];
	}

	// Função que busca todos os dados de um funcionário pelo seu id
	public function get($iduser)
	{
		$query = "SELECT * FROM tb_funcionario WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":id",$iduser);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

	// Função que busca todas as informações sobre o administrador
	public function getAdmin($idadmin)
	{
		$query = "SELECT * FROM tb_administrador WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':id', $idadmin);
		$stmt->execute();
		
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	
	// Função responsável por cadastrar um novo funcionário caso ainda não seja cadastrado
	public function cadFuncionario($nome,$login,$password,$saldo)
	{
		$query = "SELECT login FROM tb_funcionario WHERE login = :LOGIN";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":LOGIN",$login);
		$stmt->execute();

		if($stmt->rowCount() == 0){

			$query = "INSERT INTO tb_funcionario (nome_completo,login,senha,saldo_atual,administrador_id) VALUES (:fullname, :login, :senha , :saldoAtual , :adminId)";
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(':fullname',$nome);
			$stmt->bindParam(':login',$login);
			$stmt->bindParam(':senha',$password);
			$stmt->bindParam(':saldoAtual',$saldo);
			$stmt->bindParam(':adminId',$_SESSION['id_admin']);
			$stmt->execute();
			
			return true;

		}else{
			return false;
		}
	}

	// Função que atualiza os dados do funcionário
	public function update($idfunc,$nome,$login,$saldo)
	{
		date_default_timezone_set('America/Sao_Paulo');
		$dataLocal = date('Y/m/d H:i:s', time());

		$query = "UPDATE tb_funcionario SET nome_completo = :nome_completo, login = :login, saldo_atual = :saldo_atual, data_alteracao = :dataUpdate WHERE id = :idfunc";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':nome_completo',$nome);
		$stmt->bindParam(':login',$login);
		$stmt->bindParam(':saldo_atual',$saldo);
		$stmt->bindParam(':dataUpdate',str_replace('/','-',$dataLocal));
		$stmt->bindParam(':idfunc',$idfunc);
		$result = $stmt->execute();

		if($result){
			return true;
		}else{
			return false;
		}
		
	}

	// Função que deleta um funcionário pelo seu id
	public function delete($idfunc)
	{

		$query = "DELETE FROM tb_funcionario WHERE id = :idfunc";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':idfunc',$idfunc);
		$stmt->execute();

		return true;
	}

	// Paginação dos funcionários encontrados na tabela tb_funcionario
	public function getPage($page = 1, $itemsPerPage = 5)
	{

		$start = ($page - 1) * $itemsPerPage;

		$query = "SELECT id, nome_completo, saldo_atual, data_criacao, administrador_id 
		FROM 
			tb_funcionario
		ORDER BY 
			data_criacao 
		LIMIT 
			$start, $itemsPerPage;";

		$stmt = $this->db->prepare($query);
		$stmt->execute();

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$nrtotal = $stmt->rowCount($data);


		$query = "SELECT nome_completo FROM tb_administrador WHERE id = :ID";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":ID", $data[0]['administrador_id']);

		$stmt->execute();

		$admin = $stmt->fetch(\PDO::FETCH_ASSOC);

		$query = "SELECT * FROM tb_movimentacao";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$resultTotal = $stmt->rowCount();

		$pages = $resultTotal / $itemsPerPage;
		return [
			'data' => $data,
			'encontrados' => $nrtotal,
			'pages' => ceil(floatval($pages)),
			'admin' =>$admin['nome_completo']
		];
	}


	// Paginação que lista os funcionários aplicando um filtro
	public function getPageSearch($search, $data ,$page = 1, $itemsPerPage = 5)
	{
		$funcionario = '%'.$search.'%';
		$data_criacao = '%'.$data.'%';

		$start = ($page - 1) * $itemsPerPage;

		$query = "SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_funcionario
			WHERE nome_completo LIKE :search AND data_criacao LIKE :data_criacao
			ORDER BY data_criacao
			LIMIT $start, $itemsPerPage;";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':search',$funcionario);
		$stmt->bindParam(':data_criacao',$data_criacao);
		$stmt->execute();

		$results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$nrtotal = $stmt->rowCount();

		$query = "SELECT nome_completo FROM tb_administrador WHERE id = :ID";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":ID", $results[0]['administrador_id']);

		$stmt->execute();
		$admin = $stmt->fetch(\PDO::FETCH_ASSOC);

		return [
			'data' => $results,
			'encontrados' => $nrtotal,
			'pages' => ceil($nrtotal / $itemsPerPage),
			'admin' =>$admin['nome_completo']
		];
	}

	/* Cadastro de movimentações, cadastra na tabela tb_movimentacao e recebe o saldo do funcionário usando uma
	função especifica para alterar o saldo do mesmo
	*/

	public function cadMovimentacao($tipo,$valor,$observacao,$idfunc,$idadmin)
	{
		$query = "INSERT INTO tb_movimentacao (tipo_movimentacao, valor, observacao, funcionario_id, administrador_id) VALUES (:tipo_movimentacao,:valor,:observacao,:funcionario_id,:administrador_id)";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':tipo_movimentacao',$tipo);
		$stmt->bindParam(':valor',$valor);
		$stmt->bindParam(':observacao',$observacao);
		$stmt->bindParam(':funcionario_id',$idfunc);
		$stmt->bindParam(':administrador_id',$idadmin);
		$stmt->execute();

		if($tipo == "entrada"){

			$query = "SELECT saldo_atual FROM tb_funcionario WHERE id = :id";
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":id",$idfunc);
			$stmt->execute();
			$results = $stmt->fetch(\PDO::FETCH_ASSOC);

			$Saldo = $results['saldo_atual'];
			
			$moviADD = number_format($valor,2,'.',',');

			$this->addToSaldo($Saldo,$moviADD, $idfunc);

		}else{
			$query = "SELECT saldo_atual FROM tb_funcionario WHERE id = :id";
			$stmt = $this->db->prepare($query);
			$stmt->bindParam(":id",$idfunc);
			$stmt->execute();
			$results = $stmt->fetch(\PDO::FETCH_ASSOC);

			$Saldo = $results['saldo_atual'];
			
			$moviADD = number_format($valor,2,'.',',');

			$this->removeToSaldo($Saldo,$moviADD, $idfunc);
		}

		return $results;
	}

	// Função para atualizar o saldo - Aumenta o saldo
	public function addToSaldo($saldo, $movimentacao,$idfunc){

		settype($movimentacao, "float");

		$saldo_atual = str_replace(',','',$saldo);

		settype($saldo_atual, "float");

		$carteira = $saldo_atual + $movimentacao;

		$query = "UPDATE tb_funcionario SET saldo_atual = :saldo WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':saldo',$carteira);
		$stmt->bindParam(':id',$idfunc);
		$stmt->execute();

		return $stmt;

	}
	// Função para atualizar o saldo - Reduz o saldo
	public function removeToSaldo($saldo, $movimentacao, $idfunc){

		settype($movimentacao, "float");

		$saldo_atual = str_replace(',','',$saldo);

		settype($saldo_atual, "float");

		$carteira = $saldo_atual - $movimentacao;

		$query = "UPDATE tb_funcionario SET saldo_atual = :saldo WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':saldo',$carteira);
		$stmt->bindParam(':id',$idfunc);
		$stmt->execute();

		return $stmt;

	}

	// Função responsável por cadastrar um novo administrador
	public function saveAdmin($fullname, $login, $password)
	{

		$query = "INSERT INTO tb_administrador (nome_completo,login,senha) VALUES (:fullname,:login,:senha)";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':fullname',$fullname);
		$stmt->bindParam(':login',$login);
		$stmt->bindParam(':senha',$password);
		$stmt->execute();

		return true;
	}


	// Paginação das movimentações de um funcionário específico
	public function getPageExtractFromId($idfunc)
	{

		$query = "SELECT a.tipo_movimentacao, a.valor, a.observacao, a.funcionario_id, a.administrador_id, a.data_criacao, b.id, b.nome_completo
		FROM tb_movimentacao AS a
		INNER JOIN tb_funcionario AS b
		INNER JOIN tb_administrador AS c
        ON a.funcionario_id = :idfunc AND b.id = :idAdmin
		ORDER BY a.data_criacao";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':idfunc',$idfunc);
		$stmt->bindParam(':idAdmin',$_SESSION['id_admin']);
		$stmt->execute();

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$nrtotal = $stmt->rowCount();

		return [
			'data' => $data,
			'qtd' => $nrtotal
		];
	}

	//Função que lista todos os extrados da tabela tb_movimentacao
	public function getPageExtract($page = 1, $itemsPerPage = 5)
	{

		$start = ($page - 1) * $itemsPerPage;

		$query = "SELECT a.id, a.tipo_movimentacao, a.valor, a.observacao, a.funcionario_id, a.administrador_id, a.data_criacao, b.nome_completo
		FROM tb_movimentacao AS a
		INNER JOIN tb_funcionario AS b
		INNER JOIN tb_administrador AS c
        WHERE a.funcionario_id = b.id AND a.administrador_id = c.id
		ORDER BY a.data_criacao
		LIMIT $start, $itemsPerPage;";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$totSearch = $stmt->rowCount();

		$query = "SELECT * FROM tb_movimentacao";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$resultTotal = $stmt->rowCount();

		return [
			'data' => $data,
			'encontrados' => $totSearch,
			'total' => $resultTotal,
			'pages' => ceil($resultTotal / $itemsPerPage)
		];
	}

	// função que lista todos os registros da tabela com base no filtro
	public function getPageExtractSearch($tipo, $nome, $data, $page = 1, $itemsPerPage = 5)
	{	
		$nome_func = '%'.$nome.'%';
		$data_search = '%'.$data.'%';
		$start = ($page - 1) * $itemsPerPage;

		$query = "SELECT a.tipo_movimentacao, a.valor, a.observacao, a.funcionario_id, a.administrador_id, a.data_criacao, b.id, b.nome_completo, c.id
		FROM tb_movimentacao AS a
		INNER JOIN tb_funcionario AS b
		INNER JOIN tb_administrador AS c
        ON a.funcionario_id = b.id AND a.administrador_id = c.id
		WHERE  a.tipo_movimentacao LIKE :tipo AND b.nome_completo LIKE :nome AND a.data_criacao LIKE :data
		ORDER BY a.data_criacao
		LIMIT $start, $itemsPerPage";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":tipo",$tipo);
		$stmt->bindParam(":nome",$nome_func);
		$stmt->bindParam(":data",$data_search);
		$stmt->execute();

		$extracts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$totSearch = $stmt->rowCount();

		if ($totSearch > 0) {
			$page = ceil($totSearch / $itemsPerPage);
		}else{
			$page = 0;
		}

		return [
			'data' => $extracts,
			'encontrados' => $totSearch,
			'pages' => $page
		];

	}

	// Função que salva a mensagem de erro
	public static function setError($msg)
	{

		$_SESSION[Main::ERROR] = $msg;
	}

	// Função que pega a mensagem de erro
	public function getError()
	{

		$msg = (isset($_SESSION[Main::ERROR]) && $_SESSION[Main::ERROR]) ? $_SESSION[Main::ERROR] : '';

		$this->clearError();

		return $msg;
	}

	// Função que limpa a mensagem de erro
	public static function clearError()
	{

		$_SESSION[Main::ERROR] = NULL;
	}

	// Função que salva a mensagem de sucesso
	public static function setSuccess($msg)
	{

		$_SESSION[Main::SUCCESS] = $msg;
	}
	// Função que pega a mensagem de sucesso
	public function getSuccess()
	{

		$msg = (isset($_SESSION[Main::SUCCESS]) && $_SESSION[Main::SUCCESS]) ? $_SESSION[Main::SUCCESS] : '';

		$this->clearSuccess();

		return $msg;
	}
	// Função que limpa a mensagem de sucesso
	public function clearSuccess()
	{

		$_SESSION[Main::SUCCESS] = NULL;
	}

	// Função que salva e registra a mensagem de erro
	public function setErrorRegister($msg)
	{

		$_SESSION[Main::ERROR_REGISTER] = $msg;
	}
	// Função que pega a mensagem de erro registrada
	public function getErrorRegister()
	{

		$msg = (isset($_SESSION[Main::ERROR_REGISTER]) && $_SESSION[Main::ERROR_REGISTER]) ? $_SESSION[Main::ERROR_REGISTER] : '';

		$this->clearErrorRegister();

		return $msg;
	}

	// Função que limpa a mensagem de erro registrada
	public function clearErrorRegister()
	{

		$_SESSION[Main::ERROR_REGISTER] = NULL;
	}

	
}

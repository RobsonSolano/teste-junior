<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['/'] = array(  
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['/admin/create'] = array(
			'route' => '/admin/create',
			'controller' => 'IndexController',
			'action' => 'formAdmin'
		);

		$routes['/admin/create/save/'] = array(
			'route' => '/admin/create/save/',
			'controller' => 'AppController',
			'action' => 'saveAdmin'
		);
  
		$routes['/admin/login'] = array(
			'route' => '/admin/login',
			'controller' => 'IndexController',
			'action' => 'showLogin'
		);

		$routes['/admin/logar/'] = array(
			'route' => '/admin/logar/',
			'controller' => 'AppController',
			'action' => 'login'
		);

		$routes['/admin/logout/'] = array(
			'route' => '/admin/logout/',
			'controller' => 'AppController',
			'action' => 'logout'
		);

		$routes['/admin/perfil/'] = array(
			'route' => '/admin/perfil/',
			'controller' => 'IndexController',
			'action' => 'getAdmin'
		);

		$routes['/admin/funcionarios'] = array(
			'route' => '/admin/funcionarios',
			'controller'=> 'IndexController',
			'action' => 'listaFuncionarios'
		);

		$routes['/admin/funcionarios/filtro'] = array(
			'route' => '/admin/funcionarios/filtro',
			'controller' => 'IndexController',
			'action' => 'listaFuncionariosFiltrados'
		);

		$routes['/admin/funcionario/create'] = array(
			'route' => '/admin/funcionario/create',
			'controller' => 'IndexController',
			'action' => 'formFunc'
		);

		$routes['/admin/funcionario/cadastro/'] = array(
			'route' => '/admin/funcionario/cadastro/',
			'controller' => 'AppController',
			'action' => 'cadFuncionario'
		);

		$routes['/admin/funcionario/info'] = array(
			'route' => '/admin/funcionario/info',
			'controller' => 'IndexController',
			'action' => 'funcionarioInfo'
		);

		$routes['/admin/funcionario/update'] = array(
			'route' => '/admin/funcionario/update',
			'controller' => 'AppController',
			'action' => 'Update'
		);

		$routes['/admin/funcionario/delete'] = array(
			'route' => '/admin/funcionario/delete',
			'controller' => 'AppController',
			'action' => 'delete'
		);

		$routes['/admin/extract/create'] = array(
			'route' => '/admin/extract/create',
			'controller' => 'IndexController',
			'action' => 'formExtrato'
		);

		$routes['/admin/extract/create/post/'] = array(
			'route' => '/admin/extract/create/post/',
			'controller' => 'AppController',
			'action' => 'cadExtrato'
		);

		$routes['/admin/funcionario/extract'] = array(
			'route' => '/admin/funcionario/extract',
			'controller' => 'IndexController',
			'action' => 'getPageExtractFromId'
		);


		$routes['/admin/extracts/listagem'] = array(
			'route' => '/admin/extracts/listagem',
			'controller' => 'IndexController',
			'action' => 'listaExtratos'
		);

		$routes['/admin/extracts/listagem/filtro'] = array(
			'route' => '/admin/extracts/listagem/filtro',
			'controller' => 'IndexController',
			'action' => 'listaExtratosSearch'
		);


		$this->setRoutes($routes);
	}

}

?>
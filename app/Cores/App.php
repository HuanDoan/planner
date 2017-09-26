<?php

	require_once(dirname(__FILE__).'/Route.php');

	/**
	* 
	*/
	class App
	{
		private $routes;
		
		function __construct()
		{
			$this->routes = new Routes();
			$this->routes->get('/', function(){
				echo 'Homepage';
			});

			$this->routes->any('*', function(){
				echo '<h1>404 Not found</h1>';
			});
		}

		public function Run(){
			$this->routes->run();
		}
	}
?>
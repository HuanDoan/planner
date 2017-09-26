<?php
	/**
	* 
	*/
	class Routes
	{
		private $route = [];
		
		function __construct()
		{
			
		}

		private function getURI(){
			$url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
			$url = str_replace('/planner/public', '', $url);
			return $url === '' || empty($url) ? '/' : $url;
		}

		private function getReqMethod(){
			$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
			return $method;
		}

		private function addRoute($med, $url, $act){
			$this->route[] = [$med, $url, $act];
		}

		function get($url, $act){
			$this->addRoute('GET', $url, $act);
		}

		function post($url, $act){
			$this->addRoute('POST', $url, $act);
		}

		function any($url, $act){
			$this->addRoute('GET|POST', $url, $act);
		}

		function mapping(){
			$check  = false;
			$reqURL = $this->getURI();
			$reqMed = $this->getReqMethod();
			$routes = $this->route;

			foreach ($routes as $r) {
				list($method, $url, $action) = $r;

				if (strpos($method, $reqMed) === FALSE){
					continue;
				}

				if ($url === '*') {
					$check = true;
				}
				else
				{
					if (strcmp(strtolower($url), strtolower($reqURL)) === 0) {
						$check = true;
					}
					else{
						continue;
					}
				}

				if ($check === true) {
					if ( is_callable($action)) {
						$action();
					}
					return;
				}
				else{
					continue;
				}
			}
			return;
		}

		function run(){
			$this->mapping();
		}
	}
?>
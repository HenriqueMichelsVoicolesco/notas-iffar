<?php 

class ClassConexao{
		private $dsn = 'mysql:host=localhost;dbname=dbnotas';
		private $user = 'root';
		private $pass = '';
	
	function conectaDB(){

		try {
			$con=new PDO($this->dsn, $this->user, $this->pass);
			//echo "Sucesso ao conectar!";
			return $con;
		} catch (Exception $erro) {
			return $erro->getMessage();
		}
	}
}
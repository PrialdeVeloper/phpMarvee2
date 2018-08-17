<?php 
class Database{
	private $con;
	public function __construct(){
		try {
			$this->con = new PDO("mysql: host=localhost; port=3307; dbname=activity2","root","");
		} catch (PDOException $e) {
			try {
				$this->con = new PDO("mysql: host=localhost; dbname=activity2","root","");
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
	}

	public function register($query,$data){
		$stmt = $this->con->prepare($query);
		return $stmt->execute($data);
	}

	public function checkSingle($query,$data){
		$stmt = $this->con->prepare($query);
		$stmt->execute($data);
		$result = $stmt->fetchColumn();
		return $result;
	}

	public function selectAll($query,$data){
		$stmt = $this->con->prepare($query);
		$stmt->execute($data);
		return $stmt->FetchAll(PDO::FETCH_ASSOC);
	}

	public function edit($query,$data){
		$stmt = $this->con->prepare($query);
		$stmt->execute($data);
		return true;
	}
	
}
 ?>
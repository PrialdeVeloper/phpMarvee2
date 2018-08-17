<?php
session_start();

require "../model/model.php";
$model = new Database();


// para nis ngan sa babaw kilid sa image

if(isset($_POST['nameGet'])){
	$stmt = "SELECT fullname FROM user WHERE id = ?";
	$userType = $model->checkSingle($stmt,array($_SESSION['user']));
	echo $userType;
}

// para makita ang editonon
if(isset($_POST['editUser'])){
	$stmt = "SELECT * FROM user WHERE id = ?";
	$data = $model->selectAll($stmt,array($_POST['id']));
	extract($data[0]);
	echo json_encode(array("fullname"=>$fullname,"username"=>$username,"password"=>$password,"usertype"=>$userType));
}

// para ma edit sa database
if(isset($_POST['editLast'])){
	$id = $_POST['id'];
	$fname = $_POST['fname'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$stmt = "UPDATE user set fullname = ?, username = ?, password = ? where id = ?";
	$edit = $model->edit($stmt,array($fname,$username,$password,$id));
	if($edit){
		echo json_encode(array("error"=>"none"));
	}
}

// para ma delete
if(isset($_POST['deleteUser'])){
	$id = $_POST['id'];
	$status = $_POST['status'];
	$stmt = "UPDATE user set userActive = ? where id = ?";
	$delete = $model->edit($stmt,array($status,$id));
	if($delete){
		echo json_encode(array("error"=>"none"));
	}
}

// usertype kung user, iya ray ma kita kung admin tanan
if (isset($_POST['getType'])) {
	$builder = null;
	$dom = null;
	$stmt = "SELECT userType FROM user WHERE id = ?";
	$userType = $model->checkSingle($stmt,array($_SESSION['user']));
	$stmt = null;
	if($userType == "user"){ //kung user siya iya ray kwaon nga data nya e butang didto
		$stmt = "SELECT * FROM user WHERE id = ?";
		$data = $model->selectAll($stmt,array($_SESSION['user']));
		foreach ($data as $userData) {
			$count = 1;
			$status = ($userData['userActive'] == 1)?"Active":"Deactivated";
			$builder = '
			<tr>
		        <td name="fname">'.htmlentities($userData['fullname']).'</td>
		        <td name="username">'.htmlentities($userData['username']).'</td>
		        <td name="status">'.$status.'</td>
		        <td name="password">'.htmlentities($userData['password']).'</td>
		         <td name="action">
		            <a href="edit.php?editUserID='.$userData['id'].'"  data-toggle="tooltip" data-placement="top" title="Activate">
		            <i class="mdi mdi-check text-success"></i>
		             </a>
		        </td>
		    </tr>
			';
			$dom = $dom."".$builder;
			$count += 1;
			}
			echo $dom;
		$data = null;
	}
	else{
		$stmt = "SELECT * FROM user";
		$data = $model->selectAll($stmt,array());
		foreach ($data as $userData) {
			$count = 1;
			$status = ($userData['userActive'] == 1)?"Active":"Deactivated";
			$builder = '
			<tr>
		        <td name="fname">'.htmlentities($userData['fullname']).'</td>
		        <td name="username">'.htmlentities($userData['username']).'</td>
		        <td name="status">'.$status.'</td>
		        <td name="password">'.htmlentities($userData['password']).'</td>
		         <td name="action">
		          <a onclick="activateUser('.$userData['id'].');" name="deleteUser" data-toggle="tooltip" data-placement="top" title="Activate">
		            <i class="mdi mdi-check text-success"></i>
		            </a>     
		         	<a onclick="deleteUser('.$userData['id'].');" name="deleteUser"  data-toggle="tooltip" data-placement="top" title="Deactivate">
		            <i class="mdi mdi-close text-danger"></i>
		           
		        </td>
		    </tr>
			';
			$dom = $dom."".$builder;
			$count += 1;
			}
			echo $dom;
		$data = null;
	}
}

// register
if(isset($_POST['register'])){
	$name = htmlentities($_POST['fullname']);
	$username = htmlentities($_POST['username']);
	$password = htmlentities($_POST['password']);
	$userType = htmlentities($_POST['usertype']);

	$stmt = "SELECT COUNT(*) FROM user WHERE username = ?";
	$emailCheck = $model->checkSingle($stmt,array($username));
	$stmt = null;
	if($emailCheck > 0){
		echo json_encode(array("error"=>"exist"));
	}else{
		$stmt = "INSERT INTO user(fullname,username,password,userType) VALUES(?,?,?,?)";
		$return = $model->register($stmt,array($name,$username,$password,$userType));
		if($return){
			$stmt = null;
			$return = null;
			$emailCheck = null;
			$name = $username = $password = $userType = null;
			echo json_encode(array("error"=>"none"));
		}else{
			echo json_encode(array("error"=>"error adding"));
		}
	}
}	


// login
if(isset($_POST['login'])){
	$email = htmlentities($_POST['username']);
	$password = htmlentities($_POST['userPassword']);
	if(!empty($email) && !empty($password)){
		$stmt = "SELECT id FROM user WHERE (username = ? AND password = ? AND userActive = '1')";
		$id = $model->checkSingle($stmt,array($email,$password));
		if(!empty($id)){
			$_SESSION['user'] = $id;
			$email = null;
			$password = null;
			$stmt = null;
			$id = null;
			echo json_encode(array("error"=>"none"));
		}else{
			echo json_encode(array("error"=>"notExist"));
		}
	}
}

// login
if(isset($_GET['logout'])){
	session_destroy();
	header("location:../views/login.php");
}

?>
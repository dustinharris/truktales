<?php 
	
	$errors = array();
	$missing = array();
	
	if(isset($_POST['submit'])){
		$expected = array('username', 'password');
		$required = array('username', 'password');
		
		require('validation_inc.php');
	}
?>

<?php
	if(isset($_POST['submit'])){
		if(!$missing){
						
			$username=$_POST['username'];
			$password=$_POST['password'];
			
			$entry=mysql_query("SELECT * FROM users WHERE users.username = '$username'");
			if(mysql_num_rows($entry) != 0){
				$row=mysql_fetch_array($entry);
				if($row['password'] == $password){
					$role = $row['role'];
					$_SESSION['username'] = $username;
					$_SESSION['role'] = $role;
					$_SESSION['login'] = 'yes';
				}
				else {
					$errors['password']=true;
					include 'login-html.php';
				}
			}
			else {
				$errors['username']=true;
				include 'login-html.php';
			}

		}
	} else {
		include 'login-html.php';
	}
?>
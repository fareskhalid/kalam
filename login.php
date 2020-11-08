<?php

  ob_start();
  session_start();
  //include('database_connection.php');

  $pageTitle = "Kalam | Login";

  $message = '';

  if(isset($_SESSION['user_id'])){ // Check If the User Is Already Loged In
    header('location:index.php');
  }

  // Check Login Details
  if(isset($_POST["login"])):
    $username = $_POST['username'];
    $password = $_POST['password'];
  	$dbfile = file_get_contents('include/database.json');
    $db = json_decode($dbfile, true);
  	if($username === $db["users"][$username]["username"]):
  		if($password === $db["users"][$username]["password"]):
  			$_SESSION['user_id'] = $db["users"][$username]["id"];
        $_SESSION['username'] = $username;
        header('Location: index.php');
      else:
        $message = "<label>Wrong Password</label>";
  		endif;
  	else:
  		$message = "<label>Wrong Username</label>";
  	endif;
  endif;
  include "include/header.php";
?>
      <div class="body-bg login">
      		<div class="header text-white border-bottom border-danger">
      			<div class="container">
		  			<h3 class="title pt-1">Kalam</h3>
		  			<p class="desc lead">Free Online Secure Chat</p>
		  		</div>
		  	</div>
		  	<div class="content">
		  		<h1 class="text-white text-center font-weight-bold my-3">More Secure and Annonymos chat</h1>
			  	<div class="container">
					<div class="form-content mx-auto py-2 px-4 border border-primary rounded text-white">
						<h3 class="text-white text-center">Login to continue</h3>
				  		<form method="post">
				            <p class="text-danger"><?php echo $message; ?></p>
				            <div class="form-group">
				              <label>Username</label>
				              <input type="text" name="username" class="form-control" required />
				            </div>
				            <div class="form-group">
				              <label>Password</label>
				              <input type="password" name="password" class="form-control" required />
				            </div>
							<div class="login-btn form-group list-inline-item">
								<input type="submit" name="login" class="btn btn-primary" value="Login" />
							</div>
							<div class="or list-inline-item">OR</div>
							<div class="sign-btn list-inline-item">
								<a class="sign-btn btn btn-transparent border border-success text-success" href="register.php">SignUp</a>
							</div>      
				  		</form>
					</div>
				</div>
			</div>
      </div>
<?php
  include "include/footer.php";
  ob_end_flush();
?>

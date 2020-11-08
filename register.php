<?php
  ob_start();
  session_start();
  //include('database_connection.php');

  $pageTitle = "Kalam | Registing New Member";
  if(isset($_SESSION['user_id'])){
    header('location:index.php');
  }
  if(isset($_POST["register"])){
    $errors = array();
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $name     = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
    $check_query = "
    SELECT * FROM login
    WHERE username = :username
    ";
    $statement = $connect->prepare($check_query);
    $check_data = array(
      ':username'  => $username
    );
    if($statement->execute($check_data)){

      if($statement->rowCount() > 0){
        $errors[] = '<p class="text-danger"><label>Username already taken</label></p>';
      } else {
        if(empty($username)){
          $errors[] = '<p class="text-danger"><label>Username is required</label></p>';
        } else {
          if (strlen($username) < 4 ) {
            $errors[] = '<p class="text-danger"><label>Username must be more than 4 Characters</label></p>';
          } elseif (strlen($username) > 10) {
            $errors[] = '<p class="text-danger"><label>Username must be less than 10 Characters</label></p>';
          }
        }
        if(empty($password)){
          $errors[] = '<p class="text-danger"><label>Password is required</label></p>';
        } else {
          if($password != $_POST['confirm_password']){
            $errors[] = '<p class="text-danger"><label>Password not match</label></p>';
          }
          if($password == $_POST['confirm_password'] && strlen($password) < 8) {
            $errors[] = '<p class="text-danger"><label>Password must be more than 8 characters</label></p>';
          }
        }
        if(empty($errors)){
          $data = array(
          ':username'  => $username,
          ':password'  => password_hash($password, PASSWORD_DEFAULT),
          ':name'      => $name
          );
          $query = "
          INSERT INTO login
          (username, password, name)
          VALUES (:username, :password, :name)
          ";
          $statement = $connect->prepare($query);
          if($statement->execute($data)) {
            $errors[] = "<label class='text-success text-center'>Registration Completed Successfully</label>";
          }
        }
      }
    }
  }
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
          <h1 class="text-white text-center font-weight-bold my-3">Have an account to chat with others</h1>
          <div class="container">
            <div class="form-content mx-auto py-2 px-4 border border-success rounded text-white">
              <h3 class="text-white text-center">Create New Account</h3>
              <form method="post">
                <span><?php  if(!empty($errors)){foreach($errors as $err){echo $err;}}?></span>
                <div class="form-group">
                  <label>Full Name</label>
                  <input
                    type="text"
                    name="name"
                    class="form-control"
                    pattern="^[a-zA-Z ]+$"
                    placeholder="Ahmed Ali"
                    required/>
                </div>
                <div class="form-group">
                  <label>Username</label>
                  <input
                    type="text"
                    name="username"
                    class="form-control"
                    pattern="(?=.*[a-z])[a-zA-Z0-9 ]+$"
                    placeholder="ah74med"
                    required/>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input
                    type="password"
                    name="password"
                    id="pass-check"
                    class="form-control"
                    autocomplete="off"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"
                    required/>
                </div>
                <div class="form-group">
                  <label>Re-enter Password</label>
                  <input
                    type="password"
                    name="confirm_password"
                    id="conf-pass"
                    class="form-control"
                    autocomplete="off"
                    required/>
                </div>
                <div class="btn-reg form-group text-center list-inline-item">
                  <input type="submit" name="register" class="btn btn-success" value="SignUp" />
                </div>
                <div class="or list-inline-item">OR</div>
                <div class="btn-log list-inline-item">
                  <a class="btn btn-transparent border border-primary text-primary" href="login.php">Login</a>
                </div>
              </form>
            </div>
          </div>
      </div>
<?php
  include "include/footer.php";
  ob_end_flush();
?>

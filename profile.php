<?php
  ob_start();
  session_start();
  include('database_connection.php');

  if(isset($_GET['id'])): // If user comes from get request by member id
    if(is_numeric($_GET['id']) == true):
      // Check if user id is exist
      $stmt2 = $connect->prepare('SELECT * FROM login WHERE user_id = ?');
      $stmt2->execute(array($_GET['id']));
      $count = $stmt2->rowCount();
      if($count > 0) { // Check IF user Id Found
        $usrs = $stmt2->fetch();
        $pageTitle = "Kalam | " . $usrs['name'];
        include('include/header.php');
        // Vistors Profile Page
        ?>
        <div class="container">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h1 class="text-center"><?php echo $usrs['name'];?></h1>
            </div>
            <div class="panel-body">
              <ul class="profile-actions text-center">
                <li>
                  <form method="POST" action="add_friend.php">
                      <input type="hidden" name="add_friend_id" value="<?php echo $_GET['id']?>"/>
                      <input type="submit" class="btn btn-success" value="Add Friend">
                  </form>
                </li>
                <li>
                  <form method="POST" action="chat.php">
                    <input type="hidden" name="to_user_id" value="<?php echo $usrs['user_id']?>"/>
                    <input type="hidden" name="to_username" value="<?php echo $usrs['username']?>"/>
                    <input type="submit" class="btn btn-primary" name="chat" value="Messege">
                  </form>
                </li>
                <li><a href="" class="btn btn-danger" id="block_user" data-uid="<?php echo $_GET['id']?>">Block</a></li>
                <li><a href="" class="btn btn-info" id="unblock_user" data-uid="<?php echo $_GET['id']?>">Unblock</a></li>
              </ul>
            </div>
         </div>
        </div>
<?php
  include "include/footer.php";
?>
<script>
  $(document).on('click', '#block_user', function(){
      var block_uid = $(this).data('uid');
      $.ajax({
          url:"block.php",
          method:"POST",
          data:{block_uid:block_uid},
          success:function(data){
            alert('User Blocked');
          }
      })
  });
  $(document).on('click', '#unblock_user', function(){
      var unblock_uid = $(this).data('uid');
      $.ajax({
          url:"block.php",
          method:"POST",
          data:{unblock_uid:unblock_uid},
          success:function(data){
            alert('User Unblocked');
          }
      })
  });
</script>
        <?php
      } else {
        include('include/header.php');
        echo "<div class='alert alert-danger text-center'>No Such ID</div>";
      }
    else:
      include('include/header.php');
      echo "<div class='alert alert-danger text-center'>ID is not Valid</div>";
    endif;
  elseif(!isset($_SESSION['user_id'])): // Check if the visitor not signed up and Not come from GET
    header("location:login.php");
  endif;

  if(isset($_SESSION['user_id']) && ! isset($_GET['id'])): // If User LogedIn
    // Connection To Database
    $stmt = $connect->prepare('SELECT * FROM login WHERE user_id = ?');
    $data = array($_SESSION['user_id']);
    $stmt->execute($data);
    $users = $stmt->fetchAll();
    function getuser($row){
      global $users;
      foreach($users as $ur){
        return $ur[$row];
      }
    }
    $pageTitle = "Kalam | " . getuser('name');
    include('include/header.php');
    // Update user Info in Database
    if (isset($_POST['update'])):
      $name = $_POST['name'];
      $password = $_POST['password'];
      $errors = array();
      if(!empty($password)){
        if($password != $_POST['confirm_password']){
          $errors[] = '<p class="text-danger"><label>Password not match</label></p>';
        } else {
          if($password == $_POST['confirm_password'] && strlen($password) < 8) {
            $errors[] = '<p class="text-danger"><label>Password must be more than 8 characters</label></p>';
          }
        }
      $data = array(
        ':password'  => password_hash($password, PASSWORD_DEFAULT),
        ':name'      => $name,
        ':zuid'      => $_SESSION['user_id']
        );
      } else {
        $data = array(
          ':password'  => getuser('password'),
          ':name'      => $name,
          ':zuid'      => $_SESSION['user_id']
          );
      }
      if(empty($errors)){
        $statement = $connect->prepare('UPDATE login SET name = :name, password = :password WHERE user_id = :zuid');
        if($statement->execute($data)) {
          $errors[] = "<label class='text-success text-center'>Saved</label>";
        }
      }
    endif;
?>
    <header>
      <div class="overlay">
          <div class="container">
              <div class="header">
                <h3><a href="index.php">Kalam</a></h3>
                <p>Free Online Secure Chat</p>
              </div>
              <div class="dropdown pull-right chat-drop">
                <button class="dropdown-toggle chat-settings" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <span></span>
                  <span></span>
                  <span></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                  <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['username'];  ?></a></li>
                  <li><a href="profile.php"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                  <li><a href="logout.php"><span class="glyphicon glyphicon-arrow-left"></span> Logout</a></li>
                </ul>
              </div>
              <div class="back hidden-xs">
                <a href="index.php"><span class="glyphicon glyphicon-chevron-left"></span></a>
              </div>
          </div>
      </div>
    </header>
    <div class="container">
      <div class="profile-body">
        <h1 class="text-center">Edit Profile</h1>
        <form class="edit-profile" method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
              <span><?php  if(!empty($errors)){foreach($errors as $err){echo $err;}}?></span>
              <div class="form-group">
                <label>Your Name</label>
                <input
                  type="text"
                  name="name"
                  value="<?php echo getuser('name');?>"
                  class="form-control"
                  pattern="^[a-zA-Z ]+$"
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
                  placeholder= "Leave blank if you want change password"/>
              </div>
              <div class="form-group">
                <label>Re-enter Password</label>
                <input
                  type="password"
                  name="confirm_password"
                  id="conf-pass"
                  class="form-control"
                  autocomplete="off"
                  placeholder="Type password again"/>
              </div>
              <div class="form-group text-center btn-reg">
                <input type="submit" name="update" class="btn btn-success" value="Save" />
              </div>
            </form>
      </div>
    </div>
    <script>
    setInterval(function(){
     update_last_activity();
    }, 1000);
    function update_last_activity()
    {
     $.ajax({
      url:"update_last_activity.php",
      method:"POST",
      success:function(){}
     })
    }
    </script>
<?php
  endif;
  include('include/footer.php');
  ob_end_flush();
?>

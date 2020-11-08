<?php
  ob_start();
  session_start();
  include "include/header.php";
  // Check if user is loged in 
  if(!isset($_SESSION['user_id'])){
   header("location:login.php");
   exit();
  }
  // Json Database Object
  $dbfile = file_get_contents('include/database.json');
  $db = json_decode($dbfile, true);
?>
    <div class="body-bg login main">
      <div class="header text-white border-bottom border-danger">
        <div class="container-fluid">
          <h3 class="title pt-1">Kalam</h3>
          <div class="options-icon" data-target="#options-list">
            <i class="fa fa-ellipsis-v"></i>
          </div>
          <ul id="options-list" class="list-unstyled">
            <li><a href="settings.php">Settings</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
          <div class="search-icon" data-target="#search-box">
            <i class="fa fa-search"></i>
          </div>
          <form class="search" id="search-box">
            <input class="form-control border-rounded" type="text" placeholder="Type Username">
          </form>
        </div>
      </div>
      <div class="wrapper-chat">
        <div class="container-fluid main-body">
          <ul class="friends list-unstyled">
          <?php
              foreach($db["users"]["fares"]["friends"] as $friend):?>
                <li>
                  <div class="row">
                      <div class="fr-pic col-sm-2">
                        <img src="images/profile.png">
                      </div>
                      <div class="info col-sm-10" data-to-userid="<?php echo $db['users'][$friend]['id'];?>">
                        <div><?php echo $db['users'][$friend]['fullName'];?></div>
                        <span>Message from <?php echo $friend?></span>
                      </div>
                  </div>
                </li>
            <?php 
              endforeach;
            ?>
          </ul>
        </div>
      </div>
      <div class="copyrights">
        <span class="text-white">&copy;2020 - Powered by Fares Khalid</span>
      </div>
    </div>
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script> 
    <script>
$(document).ready(function(){

/*fetch_user();
getMsg();
count_unseenMsg();
*/
setInterval(function(){
 //update_last_activity();
 //fetch_user();
 //getMsg();
 //count_unseenMsg();
}, 1000);

function preventDef(e) {
  e.preventDefault;
}

function fetch_user()
{
 $.ajax({
  url:"fetch_user.php",
  method:"POST",
  success:function(data){
   $('#user_details').html(data);
  }
 })
}

function update_last_activity()
{
 $.ajax({
  url:"update_last_activity.php",
  method:"POST",
  success:function(){}
 })
}
function getMsg(){
  $.ajax({
    url: "show_messages.php",
    method: "POST",
    success: function(data){
      $('#msg-content').html(data);
    }
  })
}
function count_unseenMsg(){
  $.ajax({
    url: "count_unseen_messages.php",
    method: "POST",
    success: function(data){
      $('#count_unseenmsg').text(data);
    }
  })
}

$("#search-btn").click(function(e){
  $(".search-res").fadeToggle();
  $(".search-res input").keyup(function(){
    var username = $(this).val();
    if (username == ""){
      $(".search-res .results").html("");
    } else {
      $.ajax({
        url: "search.php",
        method: "POST",
        data: {username:username},
        success: function(data){
          $(".search-res .results").html(data);
        }
      });
    }
  });
});
});
    </script>
  </body>
</html>
<?php
  ob_end_flush();
?>

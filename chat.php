<?php
    ob_start();
    session_start();
    //include('database_connection.php');
    if(isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
        $to_user_id = $_POST['to_user_id'];
        $query = "
                SELECT * FROM login
                WHERE user_id != '".$_SESSION['user_id']."'
                ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        $stmt = $connect->prepare('SELECT * FROM login WHERE username = :zuser');
        $data = array(':zuser' => $_POST["to_username"]);
        $stmt->execute($data);
        $user = $stmt->fetch();

        $pageTitle = "Kalam | " . $user['name'];
        // Check if user is blocked
        $stmt2 = $connect->prepare('SELECT block_state FROM friends WHERE (user_id = ? OR friend_id = ?) AND ( user_id = ? OR friend_id = ?)');
        $stmt2->execute(array($_SESSION['user_id'], $_SESSION['user_id'], $to_user_id, $to_user_id));
        $count = $stmt2->rowCount();
        $check = $stmt2->fetch();
        if($count == 0 || $check['block_state'] == 0): // User is not blocked
            include "include/header.php";
?>
        <header>
            <div class="overlay">
                <div class="container">
                    <div class="chat-with"><?php echo $user['name'];?></div>
                    <span class="is_typing"></span>
                    <div class="dropdown pull-right chat-drop">
                      <button class="dropdown-toggle chat-settings" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span></span>
                        <span></span>
                        <span></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="" id="clear_chat" data-uid="<?php echo $_POST['to_user_id']?>">Clear Chat</a></li>
                        <li><a href="" id="block_user" data-uid="<?php echo $_POST['to_user_id']?>">block</a></li>
                      </ul>
                    </div>
                    <div class="back">
                        <a href="index.php"><span class="glyphicon glyphicon-chevron-left"></span></a>
                    </div>
                </div>
            </div>
        </header>
        <div class="chat-body">
            <div class="container">
                <div class="chat-history" id="user_model_details">
                    <div class="chat_history" data-touserid="<?php echo $to_user_id?>">
                        <div id="chat_history_<?php echo $to_user_id?>"></div>
                    </div>
                </div>
            </div>
            <form  class="send-box" method="POST">
                <textarea class="msg-box chat_message enter-send" name='new-msg' id="chat_message_<?php echo $to_user_id?>"></textarea>
                <button type="button" name="send_chat" id="<?php echo $to_user_id?>" class="btn btn-info send-btn send_chat">Send</button>
            </form>
        </div>
        <script src="js/jquery-1.12.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(document).ready(function(){
                function scrollToBottom(){
                  $(document).scrollTop($(document).height());
                }
                scrollToBottom();
                update_chat_history_data();
                setInterval(function(){
                  update_last_activity();
                  update_chat_history_data();
                  is_typing();
                }, 1000);
                function update_last_activity()
                {
                 $.ajax({
                  url:"update_last_activity.php",
                  method:"POST",
                  success:function(){}
                 })
                }
              function fetch_user_chat_history(to_user_id){
                  $.ajax({
                      url:"fetch_user_chat_history.php",
                      method:"POST",
                      data:{to_user_id:to_user_id},
                      success:function(data){
                          $('#chat_history_'+to_user_id).html(data);
                      }
                  })
              }

              function update_chat_history_data(){
                  $('.chat_history').each(function(){
                      var to_user_id = $(this).data('touserid');
                      fetch_user_chat_history(to_user_id);
                  });
              }

              $(document).on('click', '.send_chat', function(){
                  var to_user_id = $(this).attr('id');
                  var chat_message = $('#chat_message_'+to_user_id).val();
                  if (chat_message != " "){
                    $.ajax({
                        url:"insert_chat.php",
                        method:"POST",
                        data:{to_user_id:to_user_id, chat_message:chat_message},
                        success:function(data){
                            $('#chat_message_'+to_user_id).val('');
                            $('#chat_history_'+to_user_id).html(data);
                            scrollToBottom();
                        }
                    });
                    $.ajax({
                      url: "update_message_status.php",
                      method: "POST",
                      data:{to_user_id:to_user_id},
                      success:function(){}
                    })
                }
            });

            $(document).on('focus', '.chat_message', function(){
                var is_type = 'yes';
                $.ajax({
                    url:"update_is_type_status.php",
                    method:"POST",
                    data:{is_type:is_type},
                    success:function(){}
                    })
            });

            $(document).on('blur', '.chat_message', function(){
                var is_type = 'no';
                $.ajax({
                    url:"update_is_type_status.php",
                    method:"POST",
                    data:{is_type:is_type},
                    success:function(){}
                    })
            });

            function is_typing() {
                var to_user_id = $('.send_chat').attr('id');
                $.ajax({
                    url:"is_typing.php",
                    method:"POST",
                    data:{to_user_id:to_user_id},
                    success:function(data)
                    {
                        $('.is_typing').html(data);
                    }
                });
              }
            });

            $(document).on('click', '#block_user', function(){
                var block_uid = $(this).data('uid');
                $.ajax({
                    url:"block.php",
                    method:"POST",
                    data:{block_uid:block_uid},
                    success:function(){
                    }
                })
            });

            $(document).on('click', '#clear_chat', function(){
                var to_user_id = $(this).data('uid');
                $.ajax({
                    url:"delete_messages.php",
                    method:"POST",
                    data:{to_user_id:to_user_id},
                    success:function(){
                    }
                })
            });
        </script>
    </body>
</html>
<?php
    else:
        header('Location: index.php');
    endif;
} else {
    header('Location: login.php');
}
    ob_end_flush();
?>

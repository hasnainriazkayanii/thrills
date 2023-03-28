<?php

include('../Config/Connection.php');

session_start();
$login_check=$_SESSION['id'];


 //var_dump($data1);

if ($login_check!='1') {
 $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
  header("location:http://cheapthrillstix.com/app/appadmin/Login/login.php");
}

// Fetch text messages from database

$get_status = "SELECT * FROM `status` where is_active = 1";
$get_status_fire = mysqli_query($db,$get_status);


$get_status_edit = "SELECT * FROM `status` where is_active  = 1";
$get_status_edit_fire = mysqli_query($db,$get_status_edit);

include('../includes/header.php');

?>

<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8 new-header" style="float:left;">
                    <h3 class="new-fonts">Text Messages</h3>
                </div>
                <div class="col-md-4 text-right new-header" style="float:left;" >
               <a href="SettingDetails.php" class="btn btn-danger">Back to Settings</a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-text-message-modal">Add New</button>
                </div>
            </div>
        </div>
        <hr>
        <br>
      <?php if (isset($_SESSION['error_msg'])): ?>
         <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
         </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success_msg'])): ?>
         <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
         </div>
      <?php endif; ?>

      <div class="container">
        <div style="overflow-x:auto;">
            
            <?php
            
                $sql3="SELECT * FROM `theme_parks` where active = 1";
                $result_park = mysqli_query($db, $sql3);
                while($row_park=mysqli_fetch_assoc($result_park)){
                  $theme_park_id = $row_park['id'];
                  $theme_park_name = $row_park['name'];
//                     echo "<h5>$theme_park_name</h5>";
            
                    $sql = "SELECT t.* FROM `text_messages` t , status s where s.status_ID  = t.status and t.theme_park_id = $theme_park_id order by s.status_order asc";
//                    var_dump($sql);
                    $result = mysqli_query($db, $sql);
                    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $count = mysqli_num_rows($result);
                    if($count >= 1){
                      echo "<h5>$theme_park_name</h5>";

                    
            
            
            ?>
            
            
           <table class="table table-hover w-100 border">
            <thead>
              <tr>
                <th>Title</th>
                <th>Message</th>
                <th style="width:20%;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($messages as $message) { ?>
                <tr>
                  <td class="title"><?php echo $message['title']; ?></td>
                  <td class="message"><?php echo substr($message['message'],0,80); ?><input type="hidden" class="message_hidden" value="<?php echo $message['message']; ?>"/></td>
                  <td>
                    <button type="button" class="btn btn-warning edit-text-message-btn" data-toggle="modal" data-id="<?php echo $message['id'] ?>" data-target="#edit-text-message-modal" data-image="<?php echo $message['message_attachment'] ?>" data-status="<?php echo $message['status'] ?>" data-park='<?php echo @$message['theme_park_id'] ?>'>Edit</button>
                    <a href="delete_text_message.php?id=<?php echo $message['id']; ?>" class="btn btn-danger">Delete</a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php }  } ?>
          
          
        </div>
      </div>
    </div>
</div>



<!-- The add text message Modal -->
<div class="modal fade" id="add-text-message-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
    
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add New Text Message</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="create_text_message.php" method="post" enctype="multipart/form-data">
        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-group">
              <label class="font-weight-bold">Title</label>
              <input type="text" name="title" class="form-control">
            </div>
            
            <div class="form-group">
              <label class="font-weight-bold">Theme Park</label>
              <select name="theme_park_id" id="" class="form-control">
                <option value="">Select Theme Park</option>
                <?php
                $sql1="SELECT * FROM `theme_parks`";
                $res = mysqli_query($db, $sql1);
                while($row=mysqli_fetch_assoc($res)){
                  echo '<option value="'.$row['id'].'">'.$row['name'].' ('.$row['code'].')</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">Status</label>
              <select name="status" id="" class="form-control">
                  <option value="">Select Status</option>
                  <?php 
                    if(mysqli_num_rows($get_status_fire)>0){
                        while($rows = mysqli_fetch_assoc($get_status_fire)){
                            ?>
                                <option value="<?php echo $rows['status_ID'] ?>"><?php echo $rows['status_name'] ?></option>
                            <?php
                        }
                    }
                  ?>
                
              </select>
            </div>

            <div class="form-group">
              <label class="font-weight-bold">Message</label>
              <textarea name="message" class="form-control" rows="10"></textarea>
            </div>
            
            <div class="form-group">
                <label class="font-weight-bold">Message Attachment</label>
                <input type="file" name="message_attachment" class="form-control">
            </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
      
    </div>
  </div>
</div>

<!-- The edit text message Modal -->
<div class="modal fade" id="edit-text-message-modal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
    
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Text Message</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="edit-text-message-form" action="" method="post"  enctype="multipart/form-data">
        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-group">
              <label class="font-weight-bold">Title</label>
              <input type="text" name="title" id="title-edit-field" class="form-control">
            </div>
            <div class="form-group">
              <label class="font-weight-bold">Theme Park</label>
              <select name="theme_park_id" id="theme-edit-field" class="form-control">
                <option value="">Select Theme Park</option>
                <?php
                $sql1="SELECT * FROM `theme_parks`";
                $res = mysqli_query($db, $sql1);
                while($row=mysqli_fetch_assoc($res)){
                  echo '<option value="'.$row['id'].'">'.$row['name'].' ('.$row['code'].')</option>';
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label class="font-weight-bold">Status</label>
              <select name="status" id="status-field" class="form-control">
                <option value="">Select Status</option>
               <?php 
                    if(mysqli_num_rows($get_status_edit_fire)>0){
                        while($rows = mysqli_fetch_assoc($get_status_edit_fire)){
                            ?>
                                <option value="<?php echo $rows['status_ID'] ?>"><?php echo $rows['status_name'] ?></option>
                            <?php
                        }
                    }
                  ?>
              </select>
            </div>
            
            <div class="form-group">
              <label class="font-weight-bold">Message</label>
              <textarea name="message" id="message-edit-field" class="form-control" rows="10"></textarea>
            </div>
            
            <div class="form-group">
                <label class="font-weight-bold">Attachment</label>
                <div id="img-preview">
                    
                </div>
                <input type="file" class="form-control mt-2" name="edit-message-attach" id="edit-message-attach">
                <input type="hidden" name="old-image-path" id="old-image-path" value="">
            </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
      
    </div>
  </div>
</div>


<script type="text/javascript">

$('.edit-text-message-btn').click(function(){
    var status = $(this).attr('data-status');
    var theme_park = $(this).attr('data-park');
    var image = $(this).attr('data-image');
    var message = $(this).closest('tr').find('.message_hidden').val();
    var title = $(this).closest('tr').find('.title').text();
    var id = $(this).attr('data-id');
    $('#title-edit-field').val(title);
    $('#theme-edit-field').val(theme_park);
    $('#status-field').val(status);
    $('#message-edit-field').val(message);
    $('#edit-text-message-form').attr('action','update_text_message.php?id='+id)
            if(image!=null){
            
        $('#img-preview').html(' <img src="../'+image+'" width="100px" height="100px">')
        $('#old-image-path').val(image);
        }
   // console.log(JSON.parse(message));
})
    
//   (function () {
//     const btn = document.getElementsByClassName('edit-text-message-btn');
//     const editTextMessageForm = document.getElementById('edit-text-message-form');
//     const titleEditField = document.getElementById('title-edit-field');
//     const themeEditField = document.getElementById('theme-edit-field');
//     const messageEditField = document.getElementById('message-edit-field');
//     const statusEditField = document.getElementById('status-field');
    
//     for (let i = 0; i < btn.length; ++i)
//     {
//       btn[i].addEventListener('click', function () {
//         let message = JSON.parse(this.dataset.message);
//         titleEditField.value = message.title;
//         themeEditField.value = message.theme_park_id;
//         messageEditField.value = message.message;
//         statusEditField.value=message.status
//         editTextMessageForm.action = 'update_text_message.php?id='+message.id;
//         if(message.message_attachment!=null){
            
//         $('#img-preview').html(' <img src="../'+message.message_attachment+'" width="100px" height="100px">')
//         $('#old-image-path').val(message.message_attachment);
//         }
//     console.log(message);
//       });
//     }
//   })();
</script>
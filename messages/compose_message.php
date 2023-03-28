<?php
session_start();
include('../Config/Connection.php');

$login_check=$_SESSION['id'];

if ($login_check!='1') {
   $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
    header("location: ../Login/login.php");
}

// If the request is outside of message system refresh the message system session
if (! isset($_REQUEST['manage_state']))
   unset($_SESSION['message_system']);

/*-----------Get Customers or Members-------------*/

// Get Page number
if (isset($_REQUEST['page']) && is_numeric((int) $_REQUEST['page']) && $_REQUEST['page'])
   $page = $_REQUEST['page'];
else
   $page = 1;

// Get per page
if (isset($_REQUEST['per_page']) && is_numeric((int) $_REQUEST['per_page']) && $_REQUEST['per_page'])
   $per_page = $_REQUEST['per_page'];
else
   $per_page = 10;

if (! isset($_REQUEST['all']))
{
   if (! isset($_REQUEST['search_query']))
   {
      $sql = "SELECT count(id) as count FROM `customer`";
      $result = mysqli_query($db, $sql);
      $total_records = (int) mysqli_fetch_assoc($result)['count'];

      $total_pages = ceil($total_records / $per_page);
      $limit = $per_page;
      $offset = ($page - 1) * $per_page;

      $sql = "SELECT * FROM `customer` ORDER BY id  DESC LIMIT $offset, $limit";
      $result = mysqli_query($db, $sql);
      $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
   }
   else
   {
      $search_query = $_REQUEST['search_query'];

      $sql = "SELECT count(id) as count FROM `customer` WHERE first_name like '%".$search_query."%' OR Last_name like'%".$search_query."%' OR Phone_number like'%".$search_query[0]."%' OR homecity like'%".$search_query."%' OR Notes like'%".$search_query."%' ORDER BY id  DESC";
      $result = mysqli_query($db, $sql);
      $total_records = (int) mysqli_fetch_assoc($result)['count'];

      $total_pages = ceil($total_records / $per_page);
      $limit = $per_page;
      $offset = ($page - 1) * $per_page;

      $sql = "SELECT * FROM `customer` WHERE first_name like '%".$search_query."%' OR Last_name like'%".$search_query."%' OR Phone_number like'%".$search_query[0]."%' OR homecity like'%".$search_query."%' OR Notes like'%".$search_query."%' ORDER BY id  DESC LIMIT $offset, $limit";
      $result = mysqli_query($db, $sql);
      $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
   }

   $members_pagination = array(
      'page' => $page,
      'per_page' => $per_page,
      'total_pages' => $total_pages,
      'total_records' => $total_records
   );
}
else
{
   if (! isset($_REQUEST['search_query']))
   {
      $sql = "SELECT * FROM `customer` ORDER BY id  DESC";
      $result = mysqli_query($db, $sql);
      $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
   }
   else
   {
      $search_query = $_REQUEST['search_query'];

      $sql = "SELECT * FROM `customer` WHERE first_name like '%".$search_query."%' OR Last_name like'%".$search_query."%' OR Phone_number like'%".$search_query[0]."%' OR homecity like'%".$search_query."%' OR Notes like'%".$search_query."%' ORDER BY id  DESC";
      $result = mysqli_query($db, $sql);
      $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
      $total_recordss =count($members);
   }
}

$selected_members = array();
if (isset($_SESSION['message_system']['selected_members']))
   $selected_members = $_SESSION['message_system']['selected_members'];

$message = "";
if (isset($_SESSION['message_system']['message']))
   $message = $_SESSION['message_system']['message'];


// Get Pre defined text messages
$sql = "SELECT * FROM `text_messages`";
$result = mysqli_query($db, $sql);
$predefined_messages = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<style>

.dataTables_length{display:none!important;}

.dataTables_filter{display:none;}

.dataTables_info{display:none;}

div.dataTables_wrapper div.dataTables_paginate {
    margin: 0;
    display: none;
    white-space: nowrap;
    text-align: right;
}

@media only screen and (max-width: 678px) and (min-width: 0px) {
   .new-header {
       margin-top: 10px !important;
       width: 50% !important;
       float: left !important;
   }

    .new-fonts {
      font-size: 14px !important;
   }

    .new-header {
        padding-left: 0px;
        padding-right: 0px;
       margin-top: 10px !important;
       width: 50% !important;
       float: left !important;
   }
}

#th-new .th-1.select-all {
    width: 1% !important;
}

</style>

<?php include('../includes/header.php'); ?>

<div id="content-wrapper">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <div class="col-md-8 new-header" style="float:left;">
               <h3 class="new-fonts">Compose Message</h3>
            </div>

            <div class="col-md-4 text-right new-header" style="float:left;" >
               <a href="index.php" class="btn btn-danger">Go to Messages</a>
            </div>
         </div>
      </div>
      <hr>
      <br>
      <div class="container">
         <?php if (isset($_SESSION['notifications'])): ?>
            <?php foreach ($_SESSION['notifications'] as $key => $notification) { ?>
               <?php if ($notification['type'] === 'success'): ?>
                  <div class="alert alert-success alert-dismissible">
                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                   <?php echo $notification['message']; ?>
                 </div>
               <?php elseif ($notification['type'] === 'error'):  ?>
                  <div class="alert alert-danger alert-dismissible">
                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                   <?php echo $notification['message']; ?>
                 </div>
               <?php endif; ?>
            <?php unset($_SESSION['notifications'][$key]);} ?>
         <?php endif; ?>
         <div class="mb-3">
            <select id="predefined-message" class="form-control">
               <option value="" selected>Select Message</option>
               <?php foreach ($predefined_messages as $msg) { ?>
                  <option value="<?php echo $msg['message']; ?>" <?php if ($msg['message'] === $message) echo 'selected'; ?>><?php echo $msg['title']; ?></option>
               <?php } ?>
            </select>
         </div>
         <form id="message-multiple-members-form" action="send_message.php" method="post">
            <textarea rows="8" cols="84" required="" name="message" id="message" class="form-control" value="<?php echo $message ?>"><?php echo $message ?></textarea>
            <div class="mt-4">
               <div class="form-group">
                  <button type="submit" class="btn btn-primary">Send to selected members</button>
                  <button type="button" class="btn btn-warning" id="clear-message">Clear</button>
               </div>
            </div>
         </form>
         <div class="mt-4">
            <hr>
            <h3 class="text-center">Select Members to send message to</h3>
            <div class="card mb-3">
               <div class="card-header">
                  <i class="fas fa-table"></i>
                  Members
               </div>
               <div class="input-group">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>?manage_state=true">
                     <div class="input-group-append mar-10">
                        <input type="text" class="form-control" name="search_query" placeholder="Search for" aria-label="Search">  
                        <input type="hidden" name="all" value="true"/>
                        <input style="padding: 4px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" type="submit" class="btn btn-primary" value="Search">
                        <a style="padding: 7px 6px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px; border-radius: 5px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true&all=true'; ?>" class="btn btn-primary">Show All</a>
                     </div>
                  </form>
               </div>
               <div class="card-body">
                  <?php if (isset($_REQUEST['search_query'])):  ?>
                     <p><?php echo $total_recordss; ?> search result(s) found.</p>
                  <?php endif; ?>
                  <div class="table-responsive">
                     <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                           <div class="col-sm-12 col-md-6">
                              <div class="dataTables_length" id="dataTable_length">
                                 <label>
                                    Show 
                                    <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm">
                                       <option value="10">10</option>
                                       <option value="25">25</option>
                                       <option value="50">50</option>
                                       <option value="100">100</option>
                                    </select>
                                    entries
                                 </label>
                              </div>
                           </div>
                           <div class="col-sm-12 col-md-6">
                              <div id="dataTable_filter" class="dataTables_filter">
                                 <label>Search:<input type="search" name="search_query" class="form-control form-control-sm" placeholder="" aria-controls="dataTable"></label>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-12">
                              <table class="table table-bordered dataTable no-footer" id="dataTable" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
                                 <thead>
                                    <tr id="th-new" role="row">
                                       <th class="th-1 select-all sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width:1% !important;" aria-sort="ascending" aria-label="Name: activate to sort column descending"><input type="checkbox" id="check-all" name=""></th>
                                       <th class="th-1 sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 217px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name</th>
                                       <th class="th-2 sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 249px;" aria-label="Mobile Number: activate to sort column ascending">Mobile Number</th>
                                       <th class="th-4 sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 185px;" aria-label="Home City: activate to sort column ascending">Home City</th>
                                       <th class="th-4 sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 185px;" aria-label="Home City: activate to sort column ascending">Subscribed</th>
                                       <th class="th-7 sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 104px;" aria-label="Edit: activate to sort column ascending">Actions</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php foreach ($members as $member) { ?>
                                       <tr role="row" class="odd">
                                          <td style="padding-right:28px;">
                                             <input type="checkbox" class="check-this" name="member_ids[]" value="<?php echo $member['id']; ?>" form="message-multiple-members-form" <?php if(in_array($member['id'], $selected_members)): echo "checked"; unset($selected_members[array_search($member['id'], $selected_members)]); endif; ?>>
                                          </td>
                                          <td class="sorting_1"><?php echo $member['first_name'].' '.$member['Last_name']; ?></td>
                                          <td>+1<?php echo $member['Phone_number']; ?></td>
                                          <td><?php echo $member['homecity']; ?></td>
                                          <td><?php echo ((int)$member['is_subscribed']? 'YES': 'NO'); ?></td>
                                          <td>
                                             <form action="send_message.php" method="post">
                                                <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                                                <input type="hidden" name="message" class="individual_message">
                                                <button type="submit" class="btn btn-info">Send Message</button>
                                             </form>
                                          </td>
                                       </tr>
                                    <?php } ?>
                                 </tbody>
                              </table>

                              <!-- create remaining member id fields -->
                              <?php
                              $selected_members = array_values($selected_members);
                              foreach ($selected_members as $member_id)
                              {
                                 ?>
                                 <input type="hidden" name="member_ids[]" value="<?php echo $member_id; ?>" form="message-multiple-members-form">
                                 <?php
                              }
                              ?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

 

         <?php if (! isset($_REQUEST['all']) && $total_pages > 1): ?>
            <div class="row">
               <div class="col-md-12 text-center" style="display: flex;justify-content: center;">
                  <div id="loadMore" class="btn btn-primary px-4 mt-2" >Load More</div>
                  <br>
                  <!-- <div class="pagination">

                    <?php
                    if (isset($search_query))
                      $set_search_query = '&search_query='.$search_query;
                    else
                      $set_search_query = '';

                    $next = $members_pagination['page'] + 1;
                    if ($next > $members_pagination['total_pages'])
                     $next = null;

                    $prev = $members_pagination['page'] - 1;
                    if ($prev < 1)
                     $prev = null;
                    ?>
                     
                     <?php if ($prev !== null): ?>
                      <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;width: 80px;background: #343a40;color: #fff;text-decoration: none;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$prev; ?>"> Previous</a>
                     <?php else: ?>
                      <span style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;width: 80px;background: #343a40;color: #fff;text-decoration: none;" class="disabled">Previous</span>
                     <?php endif; ?>

                     <?php
                     if ($members_pagination['total_pages'] > 11)
                     {
                      if ($members_pagination['page'] < 7)
                      {
                        for ($i = 1; $i <= 9; ++$i)
                        {
                          if ((int) $i === (int) $members_pagination['page'])
                          {
                             ?>
                             <span style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" class="current"><?php echo $i; ?></span>
                             <?php
                          }
                          else
                          {
                             ?>
                             <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$i; ?>"><?php echo $i; ?></a>
                             <?php
                          }
                        }
                        echo "...";
                        for ($i = $members_pagination['total_pages'] - 1; $i <= $members_pagination['total_pages']; ++$i)
                        {
                           ?>
                           <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$i; ?>"><?php echo $i; ?></a>
                           <?php
                        }

                      }
                      else if($members_pagination['page'] > ($members_pagination['total_pages'] - 6))
                      {
                        for ($i = 1; $i <= 2; ++$i)
                        {
                           ?>
                           <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$i; ?>"><?php echo $i; ?></a>
                           <?php
                        }

                        echo "...";

                        for ($i = ($members_pagination['total_pages'] - 8); $i <= $members_pagination['total_pages']; ++$i)
                        {
                          if ((int) $i === (int) $members_pagination['page'])
                          {
                             ?>
                             <span style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" class="current"><?php echo $i; ?></span>
                             <?php
                          }
                          else
                          {
                             ?>
                             <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$i; ?>"><?php echo $i; ?></a>
                             <?php
                          }
                        }
                      }
                      else
                      {
                        for ($i = ($members_pagination['page'] - 3); $i < $members_pagination['page']; ++$i)
                        {
                             ?>
                             <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$i; ?>"><?php echo $i; ?></a>
                             <?php
                        }

                        ?>
                          <span style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" class="current"><?php echo $members_pagination['page']; ?></span>
                        <?php

                        for ($i = ($members_pagination['page'] + 1); $i <= ($members_pagination['page'] + 3); ++$i)
                        {
                             ?>
                             <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$i; ?>"><?php echo $i; ?></a>
                             <?php
                        }

                        echo "...";

                        for ($i = $members_pagination['total_pages'] - 1; $i <= $members_pagination['total_pages']; ++$i)
                        {
                           ?>
                           <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$i; ?>"><?php echo $i; ?></a>
                           <?php
                        }
                      }
                     }
                     else
                     {
                      for ($i = 1; $i <= $members_pagination['total_pages']; ++$i)
                      {
                        if ($i === (int) $members_pagination['page'])
                        {
                           ?>
                           <span style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" class="current"><?php echo $i; ?></span>
                           <?php
                        }
                        else
                        {
                           ?>
                           <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$i; ?>"><?php echo $i; ?></a>
                           <?php
                        }
                      }
                     }
                     ?>

                     <?php if ($next !== null): ?>
                      <a style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;width: 80px;background: #343a40;color: #fff;text-decoration: none;" href="<?php echo $_SERVER['PHP_SELF'].'?manage_state=true'.$set_search_query.'&page='.$next; ?>"> Next</a>
                     <?php else: ?>
                      <span style="border: 1px solid #ccc;padding: 7px 12px 10px 10px;width: 80px;background: #343a40;color: #fff;text-decoration: none;" class="disabled">Next</span>
                     <?php endif; ?>
                  </div> -->
               </div>
            </div>
      <?php endif; ?>
      </div>
   </div>
</div>


<script type="text/javascript">
   (function () {
      const checkAll = document.getElementById('check-all');
      const checkboxes = document.getElementsByClassName('check-this');
      checkAll.addEventListener('click', function () {
         for (let i = 0; i < checkboxes.length; ++i)
         {
            if (this.checked)
            {
               checkboxes[i].checked = true;
               addMemberToState(checkboxes[i].value);
            }
            else
            {
               checkboxes[i].checked = false;
               removeMemberFromState(checkboxes[i].value);
            }
         }
      });
   })();

   (function () {
      const message = document.getElementById('message');
      const individualMessages = document.getElementsByClassName('individual_message');

      for (let i = 0; i < individualMessages.length; ++i)
         individualMessages[i].value = message.value;

      message.addEventListener('keyup', function () {
         for (let i = 0; i < individualMessages.length; ++i)
            individualMessages[i].value = this.value;
      });
   })();

   // Clear Message
   (function () {
      const clearBtn = document.getElementById('clear-message');
      const message = document.getElementById('message');
      clearBtn.addEventListener('click', function () {
         message.value = "";
         removeMessageFromState();
      });
   })();

   // Mange state. The state is managed by manage_state.php file on server side
   (function () {
      const message = document.getElementById('message');
      const memberIds = document.getElementsByClassName('check-this');
      for (let i = 0; i < memberIds.length; ++i)
      {
         memberIds[i].addEventListener('change', function () {
            if (this.checked)
               addMemberToState(this.value);
            else
               removeMemberFromState(this.value);
         });
      }

      message.addEventListener('change', function () {
         saveMessageToState(this.value);
      })
   })();

   function addMemberToState(id)
   {
      $.ajax({
         url: 'manage_state.php?action=add_member&member_id='+id,
         success: function (res) {
         }
      });
   }

   function removeMemberFromState(id)
   {
      $.ajax({
         url: 'manage_state.php?action=remove_member&member_id='+id,
         success: function (res) {
         }
      });
   }

   function saveMessageToState(message)
   {
      $.ajax({
         url: 'manage_state.php?action=save_message&message='+message,
         success: function (res) {
         }
      });
   }

   function removeMessageFromState()
   {
      $.ajax({
         url: 'manage_state.php?action=remove_message&message='+message,
         success: function (res) {
         }
      });
   }


   // Code for selecting predefined messages
   (function () {
      const predefinedMessage = document.getElementById('predefined-message');
      predefinedMessage.addEventListener('change', function () {
         message.value = this.value;
         saveMessageToState(this.value);
      });
   })();


   //Load more using ajax
   let page_no = 2;
   let total_pages = <?php 
      $sql = "SELECT count(id) as count FROM `customer`";
      $result = mysqli_query($db, $sql);
      echo mysqli_fetch_assoc($result)['count']; ?>;

    $("#loadMore").click(function() {
      if(page_no<total_pages){
         $('#loadMore').html("Loading ...");
      $.ajax({
        url: 'compose_messageAjax.php?page=' + page_no,
        success: function(response){
          $('tbody').append(response);
          page_no += 1;
          $('#loadMore').html("Load More");
        } //success

      }) //ajax
      } //if
      else{
        $("#loadMore").attr('disabled',true);
      }
    }) //click



</script>
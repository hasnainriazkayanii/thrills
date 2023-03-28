 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

<?php
session_start();
include('../Config/Connection.php');

$login_check=$_SESSION['id'];

if ($login_check!='1') {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
         $full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
       $_SESSION['intended_url'] = $full_url;
        header("location: ../Login/login.php");
    }
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
   $per_page = 20;


if (! isset($_REQUEST['search_query']))
{
   //$sql = "SELECT count(id) as count FROM `customer` WHERE Phone_number NOT IN (SELECT contact_no FROM `archived_chats`) AND Phone_number IN (SELECT contact_no FROM `massages` WHERE seen=0 AND type='recieved')";
   $sql = "SELECT count(id) as count FROM `customer` WHERE Phone_number IN (SELECT contact_no FROM `massages` WHERE seen=0 AND type='recieved')";
   $result = mysqli_query($db, $sql);
   $total_records = (int) mysqli_fetch_assoc($result)['count'];

   $total_pages = ceil($total_records / $per_page);
   $limit = $per_page;
   $offset = ($page - 1) * $per_page;

   //$sql = "SELECT * FROM `customer` WHERE Phone_number NOT IN (SELECT contact_no FROM `archived_chats`) AND Phone_number IN (SELECT contact_no FROM `massages` WHERE seen=0 AND type='recieved') ORDER BY (SELECT created_at FROM `massages` WHERE contact_no=customer.Phone_number AND seen=0 AND type='recieved' ORDER BY created_at DESC LIMIT 1) DESC LIMIT $offset, $limit";
   $sql = "SELECT * FROM `customer` WHERE Phone_number IN (SELECT contact_no FROM `massages` WHERE seen=0 AND type='recieved') ORDER BY (SELECT created_at FROM `massages` WHERE contact_no=customer.Phone_number AND seen=0 AND type='recieved' ORDER BY created_at DESC LIMIT 1) DESC LIMIT $offset, $limit";
   $result = mysqli_query($db, $sql);
   $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else
{
   $search_query = $_REQUEST['search_query'];

   $sql = "SELECT count(id) as count FROM `customer` WHERE CONCAT(first_name,' ', Last_name) LIKE '%$search_query%' OR first_name like '%".$search_query."%' OR Last_name like'%".$search_query."%' OR Phone_number like'%".$search_query[0]."%' OR homecity like'%".$search_query."%' OR Notes like'%".$search_query."%' ORDER BY id  DESC";
   $result = mysqli_query($db, $sql);
   $total_records = (int) mysqli_fetch_assoc($result)['count'];

   $total_pages = ceil($total_records / $per_page);
   $limit = $per_page;
   $offset = ($page - 1) * $per_page;

   $sql = "SELECT * FROM `customer` WHERE CONCAT(first_name,' ', Last_name) LIKE '%$search_query%' OR first_name LIKE '%$search_query%' OR Last_name LIKE '%$search_query%' OR Phone_number like'%".$search_query[0]."%' OR homecity like'%".$search_query."%' OR Notes like'%".$search_query."%' ORDER BY id  DESC LIMIT $offset, $limit";
   $result = mysqli_query($db, $sql);
   $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$members_pagination = array(
   'page' => $page,
   'per_page' => $per_page,
   'total_pages' => $total_pages,
   'total_records' => $total_records
);


foreach ($members as &$mbr)
{
   //Get new messages count
   $sql = "SELECT count(id) as new_messages_count FROM `massages` WHERE contact_no='{$mbr['Phone_number']}' AND seen=0 AND type='recieved'";
   $result = mysqli_query($db, $sql);
   $new_messages_count = mysqli_fetch_assoc($result)['new_messages_count'];
   $mbr['new_messages_count'] = $new_messages_count;
}

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

</style>

<?php $datatable = false; ?>

<?php include('../includes/header.php'); ?>


<div id="content-wrapper">
	<div class="container-fluid">
		<div class="row">
    		<div class="col-md-12">
    			<div class="col-md-8 new-header" style="float:left;">
    				<h3 class="new-fonts">Messages</h3>
    			</div>
    			<div class="col-md-4 text-right new-header" style="float:left;" >
               <a href="archive.php" class="btn btn-warning">Archive</a>
    				<a href="compose_message.php" class="btn btn-primary">Compose Message</a>
    			</div>
    		</div>
    	</div>
    	<hr>
    	<br>
      <?php if (isset($_SESSION['success_msg'])): ?>
         <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
         </div>
      <?php endif; ?>
        <div>
            <div class="card mb-3">
               <div class="card-header">
                  <i class="fas fa-table"></i>
                  Members
               </div>
               <div class="input-group">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>?manage_state=true">
                     <div class="input-group-append mar-10">
                        <input type="text" class="form-control" name="search_query" placeholder="Search for" aria-label="Search">  
                        <input style="padding: 4px 12px;border: 1px solid #6c6c6c;font-size: 14px;margin-left:10px;border-radius: 5px;" type="submit" class="btn btn-primary" value="Search">
                     </div>
                  </form>
               </div>
               <div class="card-body">
                  <?php if (isset($_REQUEST['search_query'])):  ?>
                     <p><?php echo $members_pagination['total_records']; ?> search result(s) found.</p>
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
                                       <th class="th-1 sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 217px;" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name</th>
                                       <th class="th-2 sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 249px;" aria-label="Mobile Number: activate to sort column ascending">Mobile Number</th>
                                       <th class="th-4 sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 104px;" aria-label="Edit: activate to sort column ascending">Actions</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php foreach ($members as $member) { ?>
                                       <tr id="record-<?php echo $member['id']; ?>" role="row" class="odd <?php if($member['last_message']['seen'] === '0' && $member['last_message']['type'] === 'recieved') echo 'font-weight-bold'; ?>">
                                          <td class="sorting_1">
                                             <?php echo $member['first_name'].' '.$member['Last_name']; ?> 
                                             <?php if ($member['new_messages_count']): ?>
                                                <span class="badge badge-success" title="<?php echo $member['new_messages_count']; ?> new messages"><?php echo $member['new_messages_count']; ?></span>
                                             <?php endif; ?>
                                          </td>
                                          <td>+1<?php echo $member['Phone_number']; ?></td>
                                          <td>
                                             <!-- <a href="add_to_archive.php?contact_no=<?php echo $member['Phone_number']; ?>" class="btn btn-primary">Add to Archive</a> -->
                                             <!-- <button type="button" class="btn btn-primary add-to-archive" onclick="addToArchive(this, <?php echo $member['id']; ?>, '<?php echo $member['Phone_number']; ?>')">Add to Archive</button> -->
                                             <a href="chat.php?member_id=<?php echo $member['id']; ?>" class="btn btn-info">Messages</a>
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
                  <div class="pagination">
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
                  </div>
               </div>
            </div>
      <?php endif; ?>
	</div>
</div>


<script type="text/javascript">
   function addToArchive(self, id, contactNo)
   {
      $.ajax({
         url: 'add_to_archive.php?contact_no='+contactNo,
         method: 'GET',
         beforeSend: function () {
            self.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Adding..';
         },
         success: function () {
            self.innerHTML = 'Added To Archive';
            $("#record-"+id).fadeOut();
         }
      })
   }
</script>
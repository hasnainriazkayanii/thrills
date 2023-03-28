<?php

    //include('../Config/Connection.php');  // include your code to connect to DB.

           //your table name

  //session_start();

    $adjacents = 3;



    

    //var_dump($query);

   if(isset($_POST['submit'])=='true')



  {



      $cus_search_name=$_POST['search_name'];

      

       $query = "SELECT COUNT(*) as num FROM customer WHERE first_name like '%".$cus_search_name."%' OR Last_name like'%".$cus_search_name."%' ORDER BY id  DESC";

       

      

  }else{

      $query = "SELECT COUNT(*) as num FROM $tbl_name";

  }



  

   

    

    $total_pages = mysqli_fetch_array(mysqli_query($db,$query));

    $total_pages = $total_pages['num'];



       //your file name  (the name of this file)

    $limit = 50;

    //$page = 1;     

    if(isset($_GET['page']))

    {                           //how many items to show per page

        $page = $_GET['page'];

    }

    else

    {

        $page=1;

    }

    

    if($page) 

        $start = ($page - 1) * $limit;          //first item to display on this page

    else

        $start = 0;                             //if no page var is given, set start to 0



    /* Get data. */

   

    $sql = "SELECT * FROM $tbl_name 
     
   ";

//   var_dump($sql); die;

    $result_page = mysqli_query($db,$sql);
// $res = mysqli_fetch_assoc($result_page);
// var_dump($res);die;
    /* Setup page vars for display. */

    if ($page == 0) $page = 1;                  //if no page var is given, default to 1.

    $prev = $page - 1;                          //previous page is page - 1

    $next = $page + 1;                          //next page is page + 1

    $lastpage = ceil($total_pages/$limit);      //lastpage is = total pages / items per page, rounded up.

    $lpm1 = $lastpage - 1;                      //last page minus 1



    /* 

        Now we apply our rules and draw the pagination object. 

        We're actually saving the code to a variable in case we want to draw it more than once.

    */

       // var_dump($lastpage);

    $pagination = "";

    if($lastpage > 1)

    {   

        $pagination .= "<div class=\"pagination\">";

        //previous button

        if ($page > 1) 

            $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;width: 80px;background: #343a40;color: #fff;text-decoration: none;' href=\"$targetpage?page=$prev&active=$checkActive\"> Previous</a>";

        

        else

            $pagination.= "<span style='border: 1px solid #ccc;width: 80px;padding: 7px 12px 10px 10px;background: #343a40;color: #fff;text-decoration: none;' class=\"disabled\">Previous</span>"; 



        //pages 

        if ($lastpage < 7 + ($adjacents * 2))    //not enough pages to bother breaking it up

        {   

            for ($counter = 1; $counter <= $lastpage; $counter++)

            {

                if ($counter == $page)

                    $pagination.= "<span style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' class=\"current\">$counter</span>";

                else

                    $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=$counter&active=$checkActive\">$counter</a>";                 

            }

        }

        elseif($lastpage > 5 + ($adjacents * 2)) //enough pages to hide some

        {

            //close to beginning; only hide later pages

            if($page < 1 + ($adjacents * 2))     

            {

                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)

                {

                    if ($counter == $page)

                        $pagination.= "<span style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' class=\"current\">$counter</span>";

                    else

                        $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=$counter&active=$checkActive\">$counter</a>";                 

                }

                $pagination.= "...";

                $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=$lpm1&active=$checkActive\">$lpm1</a>";

                $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=$lastpage&active=$checkActive\">$lastpage</a>";       

            }

            //in middle; hide some front and some back

            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))

            {

                

                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)

                {

                    if ($counter == $page)

                        $pagination.= "<span style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' class=\"current\">$counter</span>";

                    else

                        $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=$counter&active=$checkActive\">$counter</a>";                 

                }

                $pagination.= "...";

                $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=$lpm1&active=$checkActive\">$lpm1</a>";

                $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=$lastpage&active=$checkActive\">$lastpage</a>";       

            }

            //close to end; only hide early pages

            else

            {

                $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=1\">1</a>";

                $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=2\">2</a>";

                $pagination.= "...";

                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)

                {

                    if ($counter == $page)

                        $pagination.= "<span style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' class=\"current\">$counter</span>";

                    else

                        $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;' href=\"$targetpage?page=$counter&active=$checkActive\">$counter</a>";                 

                }

            }

        }



        //next button

        if ($page < $counter - 1) 

            $pagination.= "<a style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;width: 80px;background: #343a40;color: #fff;text-decoration: none;' href=\"$targetpage?page=$next&active=$checkActive\">Next </a>";

        else

            $pagination.= "<span style='border: 1px solid #ccc;padding: 7px 12px 10px 10px;width: 80px;background: #343a40;color: #fff;text-decoration: none;' class=\"disabled\">Next </span>";

        $pagination.= "</div>\n";     

    }

?>



    




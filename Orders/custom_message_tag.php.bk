<?php


    function custom_taga($msg_1,$first_name,$last_name,$customer,$theme_park_name,$adults,$kids,$total,$ticket_type,$order_id,$date_of_visit,$order_time){
        $msg_1 = str_replace('{%fname%}',$first_name,$msg_1);
        $msg_1 = str_replace('{%lname%}',$last_name,$msg_1);
        $msg_1 = str_replace('{%fullname%}',$customer,$msg_1);
        $msg_1 = str_replace('{%themepname%}',$theme_park_name,$msg_1);
        $msg_1 = str_replace('{%adults%}',$adults,$msg_1);
        $msg_1 = str_replace('{%kids%}',$kids,$msg_1);
        $msg_1 = str_replace('{%ototal%}',$total,$msg_1);
        $msg_1 = str_replace('{%ttype%}',$ticket_type,$msg_1);
        $msg_1 = str_replace('{%onumber%}',$order_id,$msg_1);
        $msg_1 = str_replace('{%datevisit%}',$date_of_visit,$msg_1);
        $msg_1 = str_replace('{%otime%}',$order_time,$msg_1);
        return $msg_1;
    }

?>
<?php 

include('../Config/Connection.php');

    session_start();
    $login_check=$_SESSION['id'];

    if ($login_check!='1'){
        $_SESSION['intended_url'] = $_SERVER['SCRIPT_URI'];
        header("../Login/login.php");

    }

    if(isset($_GET['user']) && !empty($_GET['user'])){
        $user = $_GET['user'];
        $name = explode(" ", $user);
        $fname = $name[0] ?? '---';
        $lname = $name[1] ?? '---';
        $sql = "SELECT id,`first_name`,`Last_name` FROM customer WHERE first_name LIKE '$fname%' OR last_name LIKE '$lname%' Limit 0,7";
        $result = mysqli_query($db, $sql);

        $Customer_data ['status'] = false;

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $objet = new stdClass;
                $objet->id = $row["id"];
                $objet->name = $row["first_name"] . ' ' . $row["Last_name"];
                $Customer_data['data'][] = $objet;
                $Customer_data['status'] = true;
            }
 
        }

        echo json_encode($Customer_data);
        
    }

?>
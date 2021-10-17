<?php session_start();
//require('config/database.php');
  //return false;
$whitelist = array(
    '127.0.0.1',
    '::1'
);

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
 
$db_host = "localhost";
$db_username = "sfgtys_sim_root";
$db_password = "10stor78QW@";
$db_name="sfgtys_sim";
} 
else{

$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name="sim";
}

$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

	if ($mysqli->connect_error)
		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);

if(isset($_POST['sim_number'])){
    
    $sim_numbers=$_POST['sim_number'];

    $sim_Arr=explode(",",$sim_numbers);
    //echo $sim_numbers;exit;
    $employee_id=$_POST['employee_id']; 
    $start_date=$_POST['start_date'];

    foreach ($sim_Arr as $sim_number) {
    	# code...
   		$sql="INSERT INTO `mt_employees_sims`(employee_id,sim_id,start_date) VALUES ('" . $employee_id . "', '" .$sim_number. "', '" . $start_date . "'); ";

        //$insert_row = $mysqli->query($sql) === TRUE);
		if ($mysqli->query($sql) === TRUE) {
		  $success_message = "assign sim Saved Successfully.";
		} else {
		  $error_message="Error: " . $sql . "<br>" . $mysqli->error;
		  
		}
		        /*if ($insert_row) {
            $id = $mysqli->insert_id;
            fp__('mt_employees', $id);
            $success_message = "assign sim Saved Successfully.";
            //header("Location:listing_sims.php?success_message=$success_message");
            //////////////////////////////////////////////////
        } else {
            $error_message .= "Sorry ! Something Went Wrong.";
            // header("Location:listing_sims.php?error_message=$error_message");
        }  */

    }
    header("Location:listing_sims.php?success_message=$success_message");
   // header("Location:listing_sims.php?success_message=$success_message");
}





?>
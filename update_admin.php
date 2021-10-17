<?php
	require ('../config/globals.php');
	require ('../config/database.php');
	
	################################

			/*** QUERY ***/
			echo "SELECT * FROM `cl_admins` WHERE id >0";
			$query = $mysqli->query("SELECT * FROM `cl_admins` WHERE id >0");

			while($row = $query->fetch_array()) {
				$nestedData=array();

				$admin_id = $row["id"];

				$total 				= getEmailsStats('all', $admin_id);
				$cleaned 			= getEmailsStats('total_cleaned', $admin_id);
				$not_existed 	= getEmailsStats('total_notexisted', $admin_id);

				//echo 'Total=>'.$total . ': Cleaned=>'.$cleaned. ': NotExisted=>'.$not_existed; echo "<br />";

				echo "UPDATE `cl_admins` SET used_quota='".$total."', cleaned='".$cleaned."', not_existed='".$not_existed."' WHERE id ='$admin_id'"; echo "<br />";
				$mysqli->query("UPDATE `cl_admins` SET used_quota='".$total."', cleaned='".$cleaned."', not_existed='".$not_existed."' WHERE id ='$admin_id'");
			}

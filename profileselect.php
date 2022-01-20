<?php
include 'config.php';

session_start();

error_reporting(0);

$userid = mysqli_real_escape_string($conn,$_SESSION["user_id"]);

$load = mysqli_query($conn, "SELECT * FROM users WHERE id='$userid' ");

  if (mysqli_num_rows($load) > 0) {
	$row = mysqli_fetch_assoc($load);
    	$usernamepre = $row['full_name'];
    	$username = mysqli_real_escape_string($conn, $usernamepre);	
	//echo '<script type="text/javascript"> alert("Username is '.$username.' ");</script>';
  } else {
    echo "<script>alert('Loading profile details not complete.');</script>";
  }

if (isset($_POST["back"])) {
  header("Location: dashboard.php");
}

if (isset($_POST["accept"])) {

  	$profileid = mysqli_real_escape_string($conn, $_POST["profileid"]);
	$check = mysqli_query($conn, "SELECT * FROM mapservice WHERE profileid ='$profileid' AND Submission_status = '2' ");
	if (mysqli_num_rows($check)>0) {
		    $row = mysqli_fetch_assoc($check);
    		$globalid = $row['globalid'];
            $expirycheck = mysqli_query($conn, "SELECT * FROM service_requests WHERE globalid ='$globalid' AND submission_status = '2' AND expired_status = '0' ");
	        if (mysqli_num_rows($expirycheck)>0) {
                $sql = "UPDATE `service_requests` SET `Submission_status` = '3' WHERE globalid = '$globalid' ";
                $sql2 = "UPDATE `mapservice` SET `submission_status` = '3',`agreed_status`='1' WHERE profileid = '$profileid' ";

	            $result = mysqli_query($conn, $sql);
                $result2 = mysqli_query($conn, $sql2);
	            $verify = mysqli_query($conn, "SELECT * FROM service_requests WHERE globalid='$globalid' AND Submission_status = '3' ");
	            if (mysqli_num_rows($verify)>0) {
   		        echo "<script>alert('Profile selected');</script>";
  	            } else {
    		    echo "<script>alert('Selection failed');</script>";
  	            }
	            $verify2 = mysqli_query($conn, "SELECT * FROM mapservice WHERE profileid='$profileid' AND submission_status = '3' AND agreed_status = '1'");
	            if (mysqli_num_rows($verify2)>0) {
   		            //echo "<script>alert('Profile selected');</script>";
  	            } else {
    		        echo "<script>alert('Selection not reflected in MAP Table');</script>";
  	            }
  	        } else {
    		echo "<script>alert('The Application, for which this profile was uploaded for, has expired');</script>";
  	        }
  	} else {
    		echo "<script>alert('Profile Status has changed ');</script>";
  	}	
}

if (isset($_POST["reject"])) {

  	$profileidr = mysqli_real_escape_string($conn, $_POST["profileidr"]);
    $reason = mysqli_real_escape_string($conn, $_POST["reason"]);
	$check = mysqli_query($conn, "SELECT * FROM mapservice WHERE profileid ='$profileidr' AND Submission_status = '2' ");
	if (mysqli_num_rows($check)>0) {
		    $row = mysqli_fetch_assoc($check);
    		$globalid = $row['globalid'];
            $expirycheck = mysqli_query($conn, "SELECT * FROM service_requests WHERE globalid ='$globalid' AND Submission_status = '2' AND expired_status = '0' ");
	        if (mysqli_num_rows($expirycheck)>0) {
                $sql2 = "UPDATE `mapservice` SET `submission_status` = '4', `comments` = '$reason' WHERE profileid = '$profileidr' ";
                $result2 = mysqli_query($conn, $sql2);
	            $verify2 = mysqli_query($conn, "SELECT * FROM mapservice WHERE profileid='$profileidr' AND submission_status = '4' AND comments = '$reason' ");
	            if (mysqli_num_rows($verify2)>0) {
   		            echo "<script>alert('Profile rejected');</script>";
  	            } else {
    		        echo "<script>alert('Update not reflected in MAP Table');</script>";
  	            }
  	        } else {
    		echo "<script>alert('The Application, for which this profile was uploaded for, has expired');</script>";
  	        }
  	} else {
    		echo "<script>alert('Profile Status has changed ');</script>";
  	}	
};

?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="statusstyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Selection</title>
  </head>
<body>

<h1>Currently Available Profiles</h1>

<table class="content-table">
  <thead>
    <tr>
        <th>Application Number </th>
	    <th>Profile ID </th>
	    <th>Provided by MAP</th>
        <th>Employee Name</th>
      	<th>Location</th>
      	<th>Skill Set</th>
	    <th>Skill Level</th>
      	<th>Duration available for</th>
      	<th>Language </th>
      	<th>Comments</th>
	    <th>Offered Price </th>
	    <th>Profile uploaded on </th>
	    <th>Cycle</th>
    </tr>
  </thead>
  <tbody>
   <?php
	$sql = "SELECT * FROM mapservice WHERE created_by = '$username' AND agreed_status = '0' AND submission_status = '2' ";
  	$result = $conn-> query($sql);

    if ($result-> num_rows > 0) {
        while ($row = $result-> fetch_assoc()) {
                        $field0 = $row["globalid"];
            		    $field1 = $row["profileid"];
                        $field2 = $row["currentcompany"];
                        $field3 = $row["employeename"];
                        $field4 = $row["location"];
                        $field5 = $row["skillset"];
                        $field6 = $row["skilllevel"];
                        $field7 = $row["durationavailablefor"];
                        $field8 = $row["language"];
                        $field9 = $row["comments"];
			$field10 = $row["price"];
			$field11 = $row["profileuploadedon"];
			$tempglobalid = $row["globalid"];
			$internalsql = mysqli_query($conn, "SELECT cycle FROM service_requests WHERE globalid = '$tempglobalid' ");
			if (mysqli_num_rows($internalsql) > 0) {
			$row = mysqli_fetch_assoc($internalsql);
			$field12 = $row['cycle'];	
			}
	echo '<tr>
                                <td>'.$field0.'</td> 
                                <td>'.$field1.'</td> 
                                <td>'.$field2.'</td> 
                                <td>'.$field3.'</td> 
                                <td>'.$field4.'</td> 
                                <td>'.$field5.'</td> 
                                <td>'.$field6.'</td> 
                                <td>'.$field7.'</td> 
                                <td>'.$field8.'</td> 
                                <td>'.$field9.'</td> 
				<td>'.$field10.'</td>
				<td>'.$field11.'</td>
				<td>'.$field12.'</td>
                            </tr>';
                                
                                "<br>";
	}
	$result->free();
        //echo "</table>";
    }
    else {
        echo "0 results";
    }
    ?>
        
  </tbody>
</table>

<h1>Select Profile</h1>
	
	<form class="form-container js-form-container" method="post">
            <!-- No id should be same. Change / replace at all occurrences -->
            <div class="form-inputs">
                <div class="mb-3 row">
                    <label for="profileid" class="col-sm-2 col-form-label">Profile ID:</label>
                    <div class="col-sm-10">
                        <input id="profileid" name="profileid" class="form-control" type="text" placeholder="Profile ID to select" value="<?php echo $_POST["profileid"]; ?>" required />
                    </div>
                </div>
		<div class="form-input-actions">                
                    <div id="actionButtons">
                        <input type="submit" class="btn" name="accept" value="Accept Profile" />
                    </div>
                </div>
            </div>
        </form>

<h1>Decline Profile & Give Reasons</h1>
	
	<form class="form-container js-form-container" method="post">
            <!-- No id should be same. Change / replace at all occurrences -->
            <div class="form-inputs">
                <div class="mb-3 row">
                    <label for="profileidr" class="col-sm-2 col-form-label">Profile ID:</label>
                    <div class="col-sm-10">
                        <input id="profileidr" name="profileidr" class="form-control" type="text" placeholder="Profile ID to reject" value="<?php echo $_POST["profileidr"]; ?>" required />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="reason" class="col-sm-2 col-form-label">Reason to decline / Evaluate offer:</label>
                    <div class="col-sm-10">
                        <input id="reason" name="reason" class="form-control" type="text" placeholder="Give description of reason" value="<?php echo $_POST["reason"]; ?>" required />
                    </div>
                </div>
		    <div class="form-input-actions">                
                    <div id="actionButtons">
                        <input type="submit" class="btn" name="reject" value="Reject Profile" />
                    </div>
                </div>
            </div>
    </form>

	<form class="form-container js-form-container" method="post">
           
		<div class="form-input-actions">                
                    <div id="actionButtons">
			<input type="submit" class="btn" name="back" value="Go Back to Dashboard" />
                    </div>
                </div>
            </div>
        </form>



</body>
</html>



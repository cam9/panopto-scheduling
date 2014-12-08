<body>
<H4> Register a class to be recorded</H4>
	<form method="post">
		<label for="username"> Username: </label>
			<input required name="username" 
				value= "<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" 
			/>
		</br></br>
		<input type="submit"/>
	</form>
	</br></br>
	<?php
		require_once(dirname(__FILE__)."/includes/dataObjects/objects/AuthenticationInfo.php");
		require_once(dirname(__FILE__)."/includes/impl/4.2/client/AccessManagementClient.php");
		require_once(dirname(__FILE__)."/includes/impl/4.2/client/RemoteRecorderManagementClient.php");
		require_once(dirname(__FILE__)."/includes/impl/4.2/client/SessionManagementClient.php");
		error_reporting(E_ALL);
		date_default_timezone_set("Europe/London");

		$server = "bc.hosted.panopto.com";
		$auth = new AuthenticationInfo("walkerjj@bc.edu","panoptopass",null);
		$AMClient = new AccessManagementClient($server, $auth);
		$RRMClient = new RemoteRecorderManagementClient($server, $auth);
		$SMClient = new SessionManagementClient($server, $auth);
	
	
		if (isset($_POST['username']) && !empty($_POST['username'])) {  
			$server='directory.bc.edu';
			$admin='uid=adminides,ou=applicationadmins,dc=bc,dc=edu';
			$passwd='rece55i0n';
			$bcid = $_POST['username'];
			$ds=ldap_connect($server);  // assuming the LDAP server is on this host

			if ($ds) {
					// bind with appropriate dn to give update access
					$r=ldap_bind($ds, $admin, $passwd);
					if(!$r) {
						echo("<p>Unable to connect to directory.bc.edu: ldap_bind failed</p>");
						ldap_close($ds);
						exit();
					}
			} 
			else {
					echo "<p>Unable to connect to directory.bc.edu: ldap_connect() false</p>";
					ldap_close($ds);
					exit();
			}


			$sr = ldap_search($ds,"ou=people,dc=bc,dc=edu", "uid=$bcid", array("courseinstructorof"));
			$info = ldap_get_entries($ds, $sr);
			if(empty($info[0]['courseinstructorof'])){
				echo("<p> No courses found associated with this bcid</p>");
			}
			else{
				$courses = $info[0]['courseinstructorof'];
				$courseQuerySuccess = True;
				echo('<form method="post">
						<label for="course"> Course: </label>
						<select name="course" id="course/">');
				
				for($i = 0; $i < count($courses)-1; $i+=1){
					$course = $courses[$i];
					echo("<option value=$course>$course</option>");
				} 
			
				echo('	</select>	
						</br></br>
						<input type="submit"/>
					 </form>');
			}
			ldap_close($ds);
		}
		
		if(isset($_POST['course'])){

			try
			{
				$folders = $SMClient->getFoldersList(new ListFoldersRequest(new Pagination(200,null), null, false, "Name", false))->getFolders();
				foreach($folders as $folder)
				{
					if( strcmp($_POST['course'], $folder->Name) == 0)
					{
						echo "Folder Found!";
						try
						{
							
							 $startDateAndTime = new DateTime();
							 $startDateAndTime->setDate(2014, 12, 5);
							 $startDateAndTime->setTime(09, 00, 00);
							 $startDateAndTime = $startDateAndTime->format("Y-m-d\TH:i:s");
							 
							 $endDateAndTime = new DateTime();
							 $endDateAndTime->setDate(2014, 12, 5);
							 $endDateAndTime->setTime(10, 00, 00);
							 $endDateAndTime = $endDateAndTime->format("Y-m-d\TH:i:s");
							 
							 $endDateAndTimeOfRecurrence = new DateTime();
							 $endDateAndTimeOfRecurrence->setDate(2014, 12, 30);
							 $endDateAndTimeOfRecurrence->setTime(20, 55, 00);
							 $endDateAndTimeOfRecurrence = $endDateAndTimeOfRecurrence->format("Y-m-d\TH:i:s");

							 $repeatingDaysOfWeek = array();
							 $repeatingDaysOfWeek[] = DayOfWeek::Monday;
							 $recorderSettings = array();
							  //Schedule a recording in HERB.G. CA
							 $recorderSettings[] = new RecorderSettings("a0669e1f-4c9a-4b16-8106-5510ed2f8bf6", false, true);
							 
							 echo "before network call<br>";
							 $guids = $RRMClient->scheduleNewRecurringRecording(
								"TestRecurrance", 
								$folder->Id, 
								$startDateAndTime,
								 $endDateAndTime, 
								 $repeatingDaysOfWeek, 
								 $endDateAndTimeOfRecurrence, 
								 $recorderSettings
							);
							echo "Success!";
							echo "<pre>";print_r($guids);echo "</pre>";
						}
						 catch(Exception $e)
						 {
						 	 echo "error";
							 echo $e->getMessage();
						 }
						exit();
					}
				}
				
				echo "<pre>";print_r("No folder for course found");echo "</pre>";
				echo('<form method="post">
						<input type="hidden" value="' . htmlspecialchars($_POST['course']) .'" name="folderName"/>
						<input value="Make new folder" name="makeNew" type="submit"/>
					 </form>');
			}
			catch(Exception $e)
			{
				echo "ERROR: ".$e->getMessage();
			}
		}
		
		if(isset($_POST['makeNew'])){
			$folder = new Folder();
			try
			{
				$folder = $SMClient->addFolder($_POST['folderName'])->getFolder();
				echo "<pre>";print_r($folder);echo "</pre>";
			}
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
			exit();

		}
		
		
		
	?>
</body>
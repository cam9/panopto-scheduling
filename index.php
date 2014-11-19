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
		$courseQuerySuccess = False;
	
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
						<select id="course/">');
				
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
		
		if($courseQuerySuccess){
			require_once(dirname(__FILE__)."/includes/dataObjects/objects/AuthenticationInfo.php");
			require_once(dirname(__FILE__)."/includes/impl/4.2/client/AccessManagementClient.php");
			require_once(dirname(__FILE__)."/includes/impl/4.2/client/RemoteRecorderManagementClient.php");
			require_once(dirname(__FILE__)."/includes/impl/4.2/client/SessionManagementClient.php");
			error_reporting(E_ALL);
			date_default_timezone_set("Europe/London");

			$server = "bc.hosted.panopto.com";
			$auth = new AuthenticationInfo("walkerjj","bailer720",null);
			$AMClient = new AccessManagementClient($server, $auth);
			$RRMClient = new RemoteRecorderManagementClient($server, $auth);
			$SMClient = new SessionManagementClient($server, $auth);

			/*try
			{
				$folderAccessDetailsResponse = $AMClient->getFolderAccessDetails("a7ed969e-be1d-402d-9a6b-36d637c3cc18");
				$folderAccessDetails = $folderAccessDetailsResponse->getFolderAccessDetails();
				echo "<pre>";print_r($folderAccessDetails);echo "</pre>";
				foreach($folderAccessDetails->UsersWithCreatorAccess->getGuids() as $guid)
				{
					 echo "<pre>";print_r($guid);echo "</pre>";
				}
			}
			catch(Exception $e)
			{
				echo "ERROR: ".$e->getMessage();
			}*/

			try
			{
				$folders = $SMClient->getFoldersList(new ListFoldersRequest(new Pagination(200,null), null, false, "Name", false))->getFolders();
				foreach($folders as $folder)
				{
					echo "<pre>";print_r($folder);echo "</pre>";
				}
			}
			catch(Exception $e)
			{
				echo "ERROR: ".$e->getMessage();
			}
		}
	?>
</body>
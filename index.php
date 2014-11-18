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
				echo('<form method="post">
						<label for="course"> Course: </label>
						<select id="course/">');
				
				for($i = 0; $i < count($courses)-1; $i+=1){
					$course = $courses[$i];
					echo('<option value={$course}>{$course}</option>');
				} 
			
				echo('	</select>	
						</br></br>
						<input type="submit"/>
					 </form>');
			}
			ldap_close($ds);
		}
	?>
</body>
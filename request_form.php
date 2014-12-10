<?php
	$server='directory.bc.edu';
	$admin='uid=adminides,ou=applicationadmins,dc=bc,dc=edu';
	$passwd='R0ckR0ll';
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
		global $courses;
		$courses = $info[0]['courseinstructorof'];
	}
	ldap_close($ds);
?>

<body>
<H4> Register a class to be recorded</H4>
	<form action="/record.php" method="post">
	
		<label for="course"> Course code: </label>
			<br>
			<select name="course">
				<?php
					for($i = 0; $i < count($courses)-1; $i+=1){
						$course = $courses[$i];
						echo("<option value=$course>$course</option>");
					} 
				?>
			</select>	
		<br></br>
		
		<label for="recorder"> Location: </label>
			<select name="recorder">
			  <option value="01e75b5d-b98c-41d1-a10f-0e0e87b9ed2c">Campion 131</option>
			  <option value="a0669e1f-4c9a-4b16-8106-5510ed2f8bf6">O'Neil 253</option>
			</select>
		<br><br>
		
		<label> All date-times in "YYYY-mm-ddTH:i:s" format please where T is the letter T</label>
		<br><br>
		
		<label for="start"> Start Time: </label>
			<br>
			<input required name="start" value="2014-12-25T20:00:00"/>
		<br><br>
		
		<label for="end"> End Time: </label>
			<br>
			<input required name="end" value="2014-12-25T22:00:00"/>
		<br><br>
		
		<label for="endR"> From now until: </label>
			<br>
			<input required name="endR" value="2015-01-25T20:00:00"/>
		<br><br>	
		
		<label for="days[]"> Days of Week -control(Windows) or command(Mac) click to select multiple: </label>
			<br>
			<select required multiple name="days[]">
			  <option value="monday">Monday</option>
			  <option value="tuesday">Tuesday</option>
			  <option value="wednesday">Wednesday</option>
			  <option value="thursday">Thursday</option>
			  <option value="friday">Friday</option>
			  <option value="saturday">Saturday</option>
			  <option value="sunday">Sunday</option>
			</select>
		</br></br>
		
		<input type="submit" value="Schedule recording"/>
	</form>
</body>
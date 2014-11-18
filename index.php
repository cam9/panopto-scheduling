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
	<form method="post">
		<label for="course"> Course: </label>
			<select id="course/"/>
		</br></br>
		<input type="submit"/>
	</form>
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
                if(!$r) die("ldap_bind failed<br>");
        } else {
                $output = "<p>Unable to get current courses</p>";
                return $output;
                exit;
        }


        $sr = ldap_search($ds,"ou=people,dc=bc,dc=edu", "uid=$bcid", array("courseinstructorof"));
        //$sr = ldap_search($ds,"ou=people,dc=bc,dc=edu", "uid=$bcid", array("bcismemberof"));

    	$info = ldap_get_entries($ds, $sr);
        echo "<pre>";
        print_r($info);
        if(!empty($info[0]['courseinstructorof'])){
        print_r($info[0]['courseinstructorof']);
        }else{
                print "No courses found associated with bcid ". $bcid;
        }
        echo "</pre>";



    	ldap_close($ds);
     
			$response = 
			echo $response;
		}
	?>
</body>
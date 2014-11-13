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
			$response = file_get_contents("http://idesdev2.bc.edu/dev/ldap_jw.php?bcid=muller");
			echo $response;
		}
	?>
</body>
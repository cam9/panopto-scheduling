<body>
<H4> Register a class to be recorded</H4>
	<form action="request_form.php" method="post">
		<label for="username"> Username: </label>
			<input required name="username" 
				value= "<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" 
			/>
		</br></br>
		<input type="submit"/>
	</form>
	</br></br>
</body>


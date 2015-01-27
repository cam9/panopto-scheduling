<?php		 
	$configs = include('config.php');
	
	require_once(dirname(__FILE__)."/includes/dataObjects/objects/AuthenticationInfo.php");
	require_once(dirname(__FILE__)."/includes/impl/4.2/client/AccessManagementClient.php");
	require_once(dirname(__FILE__)."/includes/impl/4.2/client/RemoteRecorderManagementClient.php");
	require_once(dirname(__FILE__)."/includes/impl/4.2/client/SessionManagementClient.php");
	error_reporting(E_ALL);
	date_default_timezone_set("America/New_York");

	$server = $configs['panopto-host'];
	$auth = new AuthenticationInfo($configs['panopto-user'],$configs['panopto-pass'],null);
	
	$AMClient = new AccessManagementClient($server, $auth);
	$RRMClient = new RemoteRecorderManagementClient($server, $auth);
	$SMClient = new SessionManagementClient($server, $auth);
	
	try
	{
		$folders = $SMClient->getFoldersList(new ListFoldersRequest(new Pagination(200,null), null, false, "Name", false))->getFolders();
		$folderID;
		$noFolder = true;
		foreach($folders as $folder)
		{
			if( strcmp($_POST['course'], $folder->Name) == 0)
			{
				$noFolder = false;
				$folderID = $folder->Id;
				break;
			}
		}
		if($noFolder){
			$folder = $SMClient->addFolder($_POST['course'])->getFolder();
			$folderID = $folder->Id;
			echo "Folder made for you";
		}
	
		$startDateAndTime = $_POST['start'];
		$endDateAndTime = $_POST['end'];
		$endDateAndTimeOfRecurrence = $_POST['endR'];
	
	
		$repeatingDaysOfWeek = array();
		foreach ($_POST['days'] as $day){
			switch($day){
				case "monday":
					$repeatingDaysOfWeek[] = DayOfWeek::Monday;
					break;
				case "tuesday":
					$repeatingDaysOfWeek[] = DayOfWeek::Tuesday;
					break;
				case "wednesday":
					$repeatingDaysOfWeek[] = DayOfWeek::Wednesday;
					break;
				case "thursday":
					$repeatingDaysOfWeek[] = DayOfWeek::Thursday;
					break;
				case "friday":
					$repeatingDaysOfWeek[] = DayOfWeek::Friday;
					break;
				case "saturday":
					$repeatingDaysOfWeek[] = DayOfWeek::Saturday;
					break;
				case "sunday":
					$repeatingDaysOfWeek[] = DayOfWeek::Sunday;
					break;
				
			}
		}
		
		$recorderSettings = array();
		$recorderSettings[] = new RecorderSettings($_POST['recorder'], false, true);

		$guids = $RRMClient->scheduleNewRecurringRecording(
			$_POST['course'], 
			$folderID, 
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
		echo "ERROR: ".$e->getMessage();
	}
?>
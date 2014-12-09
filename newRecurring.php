<?php
try{
	/*$startDateAndTime = new DateTime();
	$startDateAndTime->setDate(2014, 12, 25);
	$startDateAndTime->setTime(9, 00, 00);
	$startDateAndTime = $startDateAndTime->format("Y-m-d\TH:i:s");


	$endDateAndTime = new DateTime();
	$endDateAndTime->setDate(2014, 12, 25);
	$endDateAndTime->setTime(10, 00, 00);
	$endDateAndTime = $endDateAndTime->format("Y-m-d\TH:i:s");


	$endDateAndTimeOfRecurrence = new DateTime();
	$endDateAndTimeOfRecurrence->setDate(2014, 12, 30);
	$endDateAndTimeOfRecurrence->setTime(20, 55, 00);
	$endDateAndTimeOfRecurrence = $endDateAndTimeOfRecurrence->format("Y-m-d\TH:i:s");
	*/
	
	$startDateAndTime = $_POST['start'];
	$endDateAndTime = $_POST['end'];
	$endDateAndTimeOfRecurrence = $_POST['endR'];
	
	
	$repeatingDaysOfWeek = array();
	foreach ($_POST['days'] as $day){
		switch($day){
			case "Monday":
				$repeatingDaysOfWeek[] = DayOfWeek::Monday;
				break;
			case "Tuesday":
				$repeatingDaysOfWeek[] = DayOfWeek::Tuesday;
				break;
			case "Wednesday":
				$repeatingDaysOfWeek[] = DayOfWeek::Wednesday;
				break;
			case "Thursday":
				$repeatingDaysOfWeek[] = DayOfWeek::Thursday;
				break;
			case "Friday":
				$repeatingDaysOfWeek[] = DayOfWeek::Friday;
				break;
			case "Saturday":
				$repeatingDaysOfWeek[] = DayOfWeek::Saturday;
				break;
			case "Sunday":
				$repeatingDaysOfWeek[] = DayOfWeek::Sunday;
				break;
				
		}
	}
	
	
	$recorderSettings = array();
	$recorderSettings[] = new RecorderSettings($_POST['recorder'], false, true);

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
catch(Exception $e){
	echo "error";
	echo $e->getMessage();
}
?>
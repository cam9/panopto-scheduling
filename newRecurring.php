try
						{

							 $startDateAndTime = new DateTime();
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
							 

							 $repeatingDaysOfWeek = array();
							 $repeatingDaysOfWeek[] = DayOfWeek::Monday;
							 $recorderSettings = array();
							  //Schedule a recording in HERB.G. CA
							 $recorderSettings[] = new RecorderSettings("01e75b5d-b98c-41d1-a10f-0e0e87b9ed2c", false, true);
							 
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
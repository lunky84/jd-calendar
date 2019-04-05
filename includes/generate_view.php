<?php require_once("init.php");

$current_month = date('m');
$current_year = date('Y');
$calendar_position = $_POST['calendar_position'];
$month_number = date("m", strtotime($calendar_position . " months"));
$year = date("Y", strtotime($calendar_position . " months"));
$dateObj   = DateTime::createFromFormat('!m', $month_number );
$month_name = $dateObj->format('F');
$weekday_names = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
$day = '';
$days_in_the_month = cal_days_in_month(CAL_GREGORIAN,$month_number,$year);
$date = $year.'-'.$month_number.'-01';
$first_date_day = date('D', strtotime($date));
$first_date_day_number = array_search($first_date_day, $weekday_names);

$date_of_first_day = $year . '-' . $month_number . '-01';
$date_of_last_day = $year . '-' . $month_number . '-' . $days_in_the_month;

// If the supplied month and year match the current month and year 
if ($month_number == $current_month && $year == $current_year) {
	$day =  date('j');
}

$all_events = Event::find_by_dates($date_of_first_day, $date_of_last_day );

?>


<div class="calendar-header">
	<span class="calendar-title">
		<span class="month-name">
			<?php echo $month_name ?>
		</span>
		<?php echo $year ?>
	</span>
	<div class="navigation">
		<div class="prev icon-chevron-left"></div>
		<div class="today">Today</div>
		<div class="next icon-chevron-right"></div>
	</div>
</div>

<div class="calendar">
	<div class="month-table">

		

		<div class="week day-titles">
			<?php foreach ($weekday_names as $key => $value) { ?>
				<div class="day"><?php echo $value; ?></div>
			<?php } ?>
		</div>


		<?php
		$day_number = 1;
		$prepended_day_count = $first_date_day_number;
		$prepended_days = false;
		$appended_day_count = 0;
		for ($x = 1; $x <= 42; $x++) {

			// Open a new week div if its a new row
			if (($x-1) % 7 == 0) { ?>
				<div class="week">
			<?php }

			$day_type = '';

			// Add a class of "weekend" if the day is a Saturday or a Sunday
			if (($x+7) % 7 == 0 || ($x+8) % 7 == 0) {
				$day_type    .= ' weekend';
			}

			// Add a class of "today" if the day is today
			if ($day == $x) {
				$day_type    .= ' today';
			}

			// reset the day number if the number of days in the month is exceeded
			if ($days_in_the_month == $day_number-1) {
				$day_number    = 1;
				$appended_day_count += 1;
			}

			// add days from the previous month so the first day is always a monday
			if ($prepended_days == false && $first_date_day_number+1 != $x){
				$date_string = date('Y-m-d', strtotime($date_of_first_day . ' -' . $prepended_day_count . ' days'));
				
				$date = DateTime::createFromFormat("Y-m-d", $date_string);
	 			$date_day = $date->format("d");

				$prepended_day_count -= 1;

				?>

				<div class="day<?php echo $day_type; ?> previous-month-day" data-date="<?php echo $date_string ?>">
					<div class="day-number"><?php echo $date_day; ?></div>
				</div>

			<?php } else {

				$prepended_days = true;

				// Add a leading 0 to numbers 1 to 9
				$num_padded = sprintf("%02d", $day_number);

				$date_string = $year . '-' . $month_number . '-' . $num_padded;

				// If the current month has been completed add in additional days from the following month to fill the table
				if ($appended_day_count >= 1) {
					$date_string = date('Y-m-d', strtotime($year . '-' . $month_number . '-' . $days_in_the_month . ' +' . $appended_day_count . ' days'));
					$appended_day_count += 1;
					$next_month_day = ' next-month-day';
				} else{
					$date_string = $year . '-' . $month_number . '-' . $num_padded;
					$next_month_day = '';
				}

				$date = DateTime::createFromFormat("Y-m-d", $date_string);
	 			$date_day = $date->format("j");

	 			// If it is the first day of the month add the month name
				if ($date_day == 01) {
					$add_month_name = ' ' . $date->format("M");
				} else {
					$add_month_name = '';
				}


				// See if there are any events on that date
				$events = '';
				if ($all_events) {
					foreach ($all_events as $key => $event) {
						if ($event->start_date == $date_string) {
							$events = '<br />' . $event->title;
						}
					}
				}

				?>

				<div class="day<?php echo $day_type; ?><?php echo $next_month_day; ?>" data-date="<?php echo $date_string ?>">
					<div class="day-number"><?php echo $date_day; ?><?php echo $add_month_name; ?><?php echo $events; ?></div>
				</div>

				 <?php $day_number += 1;

			}

			if (($x+7) % 7 == 0) { ?>
				</div>
			<?php } ?>
		<?php } ?>

	</div>
</div>
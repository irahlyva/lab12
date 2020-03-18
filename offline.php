<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <title>Бронювання кімнат в готелі</title>
    <script src="/js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="/js/daypilot-all.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/style.css">
    
    <link rel="manifest" href="/manifest.json">

	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="application-name" content="Бронювання кімнат в готелі">
	<meta name="apple-mobile-web-app-title" content="Бронювання кімнат в готелі">
	<meta name="theme-color" content="#e6ecff">
	<meta name="msapplication-navbutton-color" content="#e6ecff">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="msapplication-starturl" content="http://kn-15443.wd.nubip.edu.ua/">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="icon" type="image/png" sizes="16x16" href="icon16.png">
	<link rel="apple-touch-icon" type="image/png" sizes="16x16" href="icon16.png">
</head>

<body>
	<header>
	    <div class="bg-help">
	    	<div class="inBox">
	        	<h1 id="logo">Бронювання кімнат в готелі</h1>
	        	<p id="claim">AJAX'овий Календар-застосунок з JavaScript/HTML5/jQuery</p>
	        	<h2 style="color: black">Дані неможливо завантажити, оскільки відсутнє підключення до мережі!</h2>
	      	</div>
	    </div>
	</header>
	<main>
		Показати кімнати:
		<select id="filter" style="margin: 10px;">
			<option value="0">Всі</option>
			<option value="1">Одномісні</option>
			<option value="2">Двомісні</option>
			<option value="4">Сімейні</option>
		</select>

	    <div style="width: 100%; float:left;">
	    	<div id="dp"></div>
	    </div>

	    <script>
			var dp = new DayPilot.Scheduler("dp"); 
			dp.startDate = DayPilot.Date.today().firstDayOfMonth();
			dp.days = DayPilot.Date.today().daysInMonth();
			dp.scale = "Day"; 
			dp.timeHeaders = [{ groupBy: "Month", format: "MMMM yyyy" }, { groupBy: "Day", format: "d" }];
			dp.rowHeaderColumns = [
				{title: "Room", width: 80},
				{title: "Capacity", width: 80},
				{title: "Status", width: 80}
			];
			dp.allowEventOverlap = false;
		 		 		
			dp.init(); 

		</script>

	</main>
  	<div class="clear"></div>
	<footer>
		<address>(с)Автор лабораторної роботи: студентка спеціальності "Комп'ютерні науки" групи ІУСТ-19001м Глива Ірина</address>
	</footer>
 
</body>
</html> 


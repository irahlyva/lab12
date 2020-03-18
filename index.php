<!DOCTYPE html>
<html lang="uk">

<style>
	.scheduler_default_rowheader_inner {
	      border-right: 1px solid #ccc;
	}
	.scheduler_default_rowheader.scheduler_default_rowheadercol2{
	      background: #fff;
	}
	.scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner{
	    top: 2px;
	    bottom: 2px;
	    left: 2px;
	    background-color: transparent;
	    border-left: 5px solid #1a9d13; /* green */
	    border-right: 0px none;
	}
	.status_dirty.scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner{
	    border-left: 5px solid #ea3624; /* red */
	}
	.status_cleanup.scheduler_default_rowheadercol2 .scheduler_default_rowheader_inner{
	    border-left: 5px solid #f9ba25; /* orange */
	}
</style>

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

		<button id="room_new" style="display: block; float: right; margin: 10px;">Додати кімнату</button>

		<script>
			room_new.onclick = function(event) {
                var modal = new DayPilot.Modal();
                modal.onClosed = function() {
                    loadResources();
                };
                modal.showUrl("room_new.php");
            };
		</script>

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
		 		 		
			dp.onBeforeResHeaderRender = function(args) {
				var beds = function(count) {
				    return count + " bed" + (count > 1 ? "s" : "");
				};
					  
				args.resource.columns[0].html = args.resource.name;
				args.resource.columns[1].html = beds(args.resource.capacity);
				args.resource.columns[2].html = args.resource.status;
				switch (args.resource.status) {
				    case "Dirty":
				        args.resource.cssClass = "status_dirty";
				        break;
				    case "Cleanup":
				        args.resource.cssClass = "status_cleanup";
				        break;
				}
			};

			dp.init(); 
		 	loadResources();
		 	loadEvents();

		 	dp.onTimeRangeSelected = function (args) {
				var modal = new DayPilot.Modal();
				modal.closed = function() {
				    dp.clearSelection();
				    var data = this.result;
				    if (data && data.result === "OK") {
				        loadEvents();
				    }
				};
				modal.showUrl("new.php?start=" + args.start + "&end=" + args.end + "&resource=" + args.resource);
			};

		 	dp.onEventClick = function(args) {
				var modal = new DayPilot.Modal();
				modal.closed = function() {
				    // reload all events
				    var data = this.result;
				    if (data && data.result === "OK") {
				        loadEvents();
				    }
				};
				modal.showUrl("edit.php?id=" + args.e.id());
			};

			dp.onEventMoved = function (args) {
				$.post("backend_move.php", 
				{
				    id: args.e.id(),
				    newStart: args.newStart.toString(),
				    newEnd: args.newEnd.toString(),
				    newResource: args.newResource
				}, 
				function(data) {
				    dp.message(data.message);
				});
			};

			dp.eventDeleteHandling = "Update";

			dp.onEventDeleted = function(args) {
				$.post("backend_delete.php", 
					{
				    	id: args.e.id()
					}, 
				  	function() {
				      	dp.message("Бронювання видалено!");
				  	}
			  	);
			};
			//редагування інформації у блоці бронювання
			dp.onBeforeEventRender = function(args) {
			  	var start = new DayPilot.Date(args.e.start);
			  	var end = new DayPilot.Date(args.e.end);
				var today = DayPilot.Date.today();
				var now = new DayPilot.Date();

				args.e.html = args.e.text + " (" + start.toString("M/d/yyyy") + " - " + end.toString("M/d/yyyy") + ")";

				switch (args.e.status) {
				   	case "New":
				       	var in2days = today.addDays(1);

				          	if (start < in2days) {
				              	args.e.barColor = 'red';
				              	args.e.toolTip = 'Застаріле (не підтверджено вчасно)';
				          	}
				          	else {
				              	args.e.barColor = 'orange';
				              	args.e.toolTip = 'Новий';
				          	}
				         	break;
				      	case "Confirmed":
				          	var arrivalDeadline = today.addHours(18);

				          	if (start < today || (start.getDatePart() === today.getDatePart() && now > arrivalDeadline)) { // must arrive before 6 pm
				              	args.e.barColor = "#f41616";  // red
				              	args.e.toolTip = 'Пізнє прибуття';
				          	}
				          	else {
				              	args.e.barColor = "green";
				              	args.e.toolTip = "Підтверджено";
				          	}
				          	break;
				      	case 'Arrived': // arrived
				          	var checkoutDeadline = today.addHours(10);

				          	if (end < today || (end.getDatePart() === today.getDatePart() && now > checkoutDeadline)) { // must checkout before 10 am
				              	args.e.barColor = "#f41616";  // червоний
				              	args.e.toolTip = "Пізній виїзд";
				          	}
				          	else {
				              	args.e.barColor = "#1691f4";  // блакитний
				              	args.e.toolTip = "Прибув";
				          	}
				          	break;
				      	case 'CheckedOut': // перевірено
					        args.e.barColor = "gray";
					        args.e.toolTip = "Перевірено";
					        break;
				      	default:
				          	args.e.toolTip = "Невизначений стан";
				          	break;
					}

				  	args.e.html = args.e.html + "<br/><span style='color:gray'>" + args.e.toolTip + "</span>";

				  	var paid = args.e.paid;
				  	var paidColor = "#aaaaaa";

				  	args.e.areas = [
				      	{ bottom: 10, right: 4, html: "<div style='color:" + paidColor + "; font-size: 8pt;'>Paid: " + paid + "%</div>", v: "Visible"},
				      	{ left: 4, bottom: 8, right: 4, height: 2, html: "<div style='background-color:" + paidColor + "; height: 100%; width:" + paid + "%'></div>", v: "Visible" }
				  	];

			};


				//функції

			function loadResources() {
				$.post("backend_rooms.php",
					{ capacity: $("#filter").val() }, 
					function(data) {
					    dp.resources = data;
					    dp.update();
					}
				);
			}

			function loadEvents() {
				var start = dp.visibleStart();
				var end = dp.visibleEnd();

				$.post("backend_events.php", 
				    {
				        start: start.toString(),
				        end: end.toString()
				    },
				    function(data) {
				        dp.events.list = data;
				        dp.update();
				    }
				);
			}

			$(document).ready(function() {
			    $("#filter").change(function() {
			        loadResources();
			    });
			});

			</script>

	</main>
  	<div class="clear"></div>
	<footer>
		<address>(с)Автор лабораторної роботи: студентка спеціальності "Комп'ютерні науки" групи ІУСТ-19001м Глива Ірина</address>
	</footer>
    <script>
		if ('serviceWorker' in navigator) {
		    navigator.serviceWorker.register('/sw.js').then((reg) => 
		    	console.log('Service Worker registered successfully.')
		    ).catch((error) => 
		    	console.log('Service Worker registration failed:', error)
		    );
		}
	</script>
</body>
</html> 


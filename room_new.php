<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Нова кімната</title>
    	<link type="text/css" rel="stylesheet" href="css/form.css"/>
        <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
    </head>
    <body>

        <form id="f" action="backend_new_room.php" style="padding:20px;">
            <h1>Додати кімнату</h1>
            <div>Назва: </div>
            <div><input type="text" id="name" name="name" value="" /></div>
            <div>Місткість:</div>
            <div>
                <select id="capacity" name="capacity">
				    <option value='1'>1 ліжко</option>
				    <option value='2'>2 ліжка</option>
				    <option value='4'>4 ліжка</option>
                </select>                
            </div>

            <div class="space">
            	<input type="submit" value="Save" /> 
            	<a href="javascript:close();">Cancel</a>
            </div>
        </form>

        <script type="text/javascript">
            function close(result) {
                if (parent && parent.DayPilot && parent.DayPilot.ModalStatic) {
                    parent.DayPilot.ModalStatic.close(result);
                }
            }
            $("#f").submit(function () {
                var f = $("#f");
                $.post(f.attr("action"), f.serialize(), function (result) {
                    close(eval(result));
                });
                return false;
            });

            $(document).ready(function () {
                $("#name").focus();
            });
        </script>
        
    </body>
</html>
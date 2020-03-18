<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Редагування броні</title>
    	<link type="text/css" rel="stylesheet" href="css/form.css" />    
        <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
    </head>
    <body>
    	<?php
    	    require_once '_db.php';
            $rooms = $db->query('SELECT * FROM rooms');
            
            $stmt = $db->prepare('SELECT * FROM reservations WHERE id = :id');
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();
            $reservation = $stmt->fetch();
        ?>
        <form id="f" action="backend_update.php" style="padding:20px;">
            <h1>Редагування бронювання</h1>
            <div style="display: none;"><input type="text" id="id" name="id" value=<?php echo $_GET['id'] ?>/></div>
            <div>Ім'я: </div>
            <div><input type="text" id="name" name="name" value="<?php echo $reservation['name']?>" /></div>
            <div>Дата початку:</div>
            <div><input type="text" id="start" name="start" value="<?php echo $reservation['start'] ?>" /></div>
            <div>Дата закінчення:</div>
            <div><input type="text" id="end" name="end" value="<?php echo $reservation['end'] ?>" /></div>
            <div>Кімната:</div>
            <div>
                <select id="room" name="room">
                    <?php 
                        foreach ($rooms as $room) {
                            $selected = $reservation['room_id'] == $room['id'] ? ' selected="selected"' : '';
                            $id = $room['id'];
                            $name = $room['name'];
                            print "<option value='$id' $selected>$name</option>";
                        }
                    ?>
                </select>
            </div>
            <div>Статус:</div>
            <div>
                <select id="status" name="status">
                    <?php 
                        $options = array("New", "Confirmed", "Arrived", "CheckedOut");
                        foreach ($options as $option) {
                            $selected = $option == $reservation['status'] ? ' selected="selected"' : '';
                            $id = $option;
                            $name = $option;
                            print "<option value='$id' $selected>$name</option>";
                        }
                    ?>
                </select>                
            </div>
            <div>Оплата:</div>
            <div>
                <select id="paid" name="paid">
                    <?php 
                        $options = array(0, 50, 100);
                        foreach ($options as $option) {
                            $selected = $option == $reservation['paid'] ? ' selected="selected"' : '';
                            $id = $option;
                            $name = $option."%";
                            print "<option value='$id' $selected>$name</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="space"><input type="submit" value="Save" /> <a href="javascript:close();">Cancel</a></div>
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



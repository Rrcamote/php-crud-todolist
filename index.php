<?php
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="main-section">
        <div class="add-section">
            <form action="app/add.php" method="POST" autocomplete="off">
                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>
                    <input type="text" name="title" style="border-color: #ff6666" placeholder="This field is required" />
                    <button type="submit">Add &nbsp; <span>&#43;</span></button>

                <?php } else { ?>
                    <h4 id="text">ENTER TASK</h4>
                    <input type="text" name="title" />
                    <button type="submit">Add</button>
                <?php } ?>
            </form>
        </div>
        <?php
        $task_list = $conn->query("SELECT * FROM task ORDER BY id DESC");
        ?>
        <div class="show-todo-section">
            <?php if ($task_list->rowCount() <= 0) { ?>
                <div class="todo-item">
                </div>
            <?php } ?>

            <?php while ($todo = $task_list->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <?php if ($todo['checked']) { ?>
                        <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>" checked />
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php } else { ?>
                        <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <small>created: <?php echo $todo['date_time'] ?></small>
                    <span id="<?php echo $todo['id']; ?>" class="remove-to-do">DELETE</span>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.remove-to-do').click(function() {
                const id = $(this).attr('id');

                $.post("app/remove.php", {
                        id: id
                    },
                    (data) => {
                        if (data) {
                            $(this).parent().hide(600);
                        }
                    }
                );
            });

            $(".check-box").click(function(e) {
                const id = $(this).attr('data-todo-id');

                $.post('app/check.php', {
                        id: id
                    },
                    (data) => {
                        if (data != 'error') {
                            const h2 = $(this).next();
                            if (data === '1') {
                                h2.removeClass('checked');
                            } else {
                                h2.addClass('checked');
                            }
                        }
                    }
                );
            });
        });
    </script>
</body>

</html>
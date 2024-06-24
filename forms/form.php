<?php
include_once 'InsertForm.php';

$insertForm = new InsertForm();
$fields = $insertForm->handleRequest();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавление пользователя</title>
    <style type="text/css">
        .error, .req:invalid {
            border: 2px solid #ff0000;
        }
        .req:valid {
            border: 2px solid #000000;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input.req {
            outline: none;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<form action="form.php" method="post" id="userForm">
    <?php
    foreach ($fields as $field) {
        $class = $field['isValid'] ? "req" : "error";
        echo '<label for="' . $field['id'] . '">' . $field['label'] . ':</label>';
        echo '<input type="' . $field['type'] . '" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] .'" class="'.$class.'" ><br>';
    }
    ?>
    <input type="submit" name="submit" value="Добавить пользователя" id="button">
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#button').on('click', function() {
            $('input.req').addClass('req');
        });
    });
</script>
</body>
</html>
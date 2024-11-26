<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kcha</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <form method="POST">
        <input type="text" name="vards" placeholder="Vārds"><br>
        <input type="password" name="parole" placeholder="Parole"><br>
        <input type="submit" name="login" value="Ielogoties">
        <input type="submit" name="register" value="Reģistrēties">
    </form>

    <?php
        session_start();
        include("db.php");

        if (isset($_POST["register"])) {
            $username = $_POST['vards'];
            $password = $_POST['parole'];

            $check_sql = "SELECT * FROM accounts WHERE username='$username'";
            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<h1>Lietotājvārds jau ir aizņemts</h1>";
            } else {

                $sql = "INSERT INTO accounts (username, password) VALUES ('$username', '$password')";

                if (mysqli_query($conn, $sql)) {
                    echo "Ieraksts veiksmīgi pievienots <br>";
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Kļūda: " . mysqli_error($conn);
                }
            }
        }

        if (isset($_POST["login"])) {
            $username = $_POST['vards'];
            $password = $_POST['parole'];

            $sql = "SELECT password FROM accounts WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);
                    if ($password == $row['password']) {
                        $_SESSION['username'] = $username;
                        $_SESSION['is_loged_in'] = true;
                        header('Location: chats.php');
                        exit();
                    } else {
                        echo "Nepareiza parole";
                    }
                } else {
                    echo "Nepareizs lietotājvārds";
                }
            } else {
                echo "Kļūda: " . mysqli_error($conn);
            }
        }
    ?>

</body>
</html>

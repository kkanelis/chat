<?php
    include("db.php");

    session_start();

    if (!isset($_SESSION['is_loged_in']) || $_SESSION['is_loged_in'] !== true) {
        header('Location: login.php');
        exit();
    }

    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
        $message = htmlspecialchars($_POST['message']);
        mysqli_query($conn, "INSERT INTO messages (username, message, created_at) VALUES ('$username', '$message', NOW())");
        mysqli_query($conn, "UPDATE accounts SET total_messages = total_messages + 1 WHERE username = '$username'");
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    

    $sql = "SELECT * FROM messages ORDER BY created_at ASC";
    $result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vienkāršs Čats</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="chat-box">
        <div class="messages">
            <?php
            if ($result->num_rows > 0) {
                $last_date = null;
                while ($row = $result->fetch_assoc()) {
                    $current_date = date("Y-m-d", strtotime($row['created_at']));
                    $time = date("H:i", strtotime($row['created_at']));

                    if ($last_date !== $current_date) {
                        echo "<h4>System: " . date("d.m.Y", strtotime($row['created_at'])) . "</h4>";
                        $last_date = $current_date;
                    }

                    echo "<p><strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['message']) . " <span class='time'>" . $time . "</span></p>";
                }
            } else {
                echo "<p>Nav ziņu.</p>";
            }
            ?>
        </div>
        <hr>
        <form method="POST" class="input-area" id="messageForm">
            <textarea name="message" placeholder="Ziņa" required wrap="hard"></textarea>
            <button type="submit">Sūtīt</button>
        </form>
        <hr>
        <form method="POST" class="input-area">
            <input type="submit" value="Izlogoties" id="logoff" name="logoff">
            <input type="submit" value="Kopējās ziņas" id="openPopup">
        </form>

        <div id="popup">
            <div class="popup-content">
                <span class="close-btn" id="closePopup">&times;</span>
                <p><?php echo "Šī ir pop-out ziņa!"; ?></p>
            </div>
        </div>


        <?php
            if(isset($_POST["logoff"])) {
                $_SESSION['is_loged_in'] = false;
                header('Location: login.php');
                exit();
            }
        ?>

        <script>
            document.getElementById("messageForm").addEventListener("keypress", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();
                    this.submit();
                }
            });

            function scrollToBottom() {
                const messagesDiv = document.querySelector('.messages');
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

            window.onload = function() {
                scrollToBottom();
            };

            document.getElementById("messageForm").addEventListener("submit", function(event) {
                setTimeout(scrollToBottom, 100);
            });

            // Get the modal
            var popup = document.getElementById("popup");

            // Get the button that opens the modal
            var btn = document.getElementById("openPopup");

            // Get the <span> element that closes the modal
            var span = document.getElementById("closePopup");

            // When the user clicks the button, open the modal 
            btn.onclick = function() {
                popup.style.display = "flex"; // Use flex to center content
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                popup.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target === popup) {
                    popup.style.display = "none";
                }
            }

        </script>

    </div>
</body>
</html>

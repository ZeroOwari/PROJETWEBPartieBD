<?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) 
        {
            session_start();
            session_unset();
            session_destroy();
            header('Location: ../index.html'); // Redirect to the homepage
            exit;
        }
?>
<form action="" method="post">
    <button type="submit" name="logout">Logout</button>
</form>
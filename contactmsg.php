<?php
    require('connect.php');

    if(!isset($_SESSION['logged']) || $_SESSION['logged']==false){
        header('Location: login.php');
    }

    $sql_msg="SELECT * FROM tbl_contact WHERE ticketID =".$_SESSION['ticketID'];

    $rs_msg = $pdo->query($sql_msg);
    $row_msg = $rs_msg->fetch();
    
?>

<html>
    <head>
        <title>contact</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="header">
            <h1>Contact</h1>
        </div>
        <?php include('nav.php'); ?>
        <main>
            <div class="center">
                <?php
                    echo(
                        $row_msg['contactFN'].' '.$row_msg['contactLN'].'<br>'.
                        $row_msg['contactEmail'].'<br>'.
                        $row_msg['contactSubject'].'<br>'.
                        $row_msg['contactMsg']
                    );
                ?>
            </div>
        </main>
        <?php
            include('foot.html');
        ?>
    </body>
</html>
<?php
    require('connect.php');

    if(!isset($_SESSION['logged']) || $_SESSION['logged']==false){
        header('Location: login.php');
    }

    if(isset($_POST)){
        if(!empty($_POST['action'])){
            if($_POST['action']==='Delete'){
                $tID = filter_var($_POST['ticketID'],FILTER_SANITIZE_STRING);
                $sql_del = "DELETE FROM tbl_contact WHERE ticketID = ".$tID;
                $pdo->exec($sql_del);
            }
            // else{
            //     $_SESSION['ticketID'] = filter_var($_POST['ticketID'],FILTER_SANITIZE_STRING);
            //     header("Location:contactmsg.php");
            // }
        }
    }

    $sql_disp="SELECT * FROM tbl_contact ORDER BY contactLN";

    //rs -> record set
    $rs_disp = $pdo->query($sql_disp);

    // get contact from db
    $sql_get = 'SELECT * FROM tbl_contact';

    // execute
    $r = $pdo->query($sql_get);
    
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
                <table>
                    <tr>
                        <td>First Name</td>
                        <td>Last Name</td>
                        <td>Email</td>
                        <td>Subject</td>
                        <td></td>
                    </tr>
                    <?php
                        while($row = $rs_disp->fetch()){
                            echo('<tr>
                                <td>'.$row['contactFN'].'</td>
                                <td>'.$row['contactLN'].'</td>'
                                .'<td>'.$row['contactEmail'].'</td>'
                                .'<td>'.$row['contactSubject'].'</td>'
                                .'<td>'.'
                                    <form method="POST" action="contactmsg.php">');
                                    $_SESSION['ticketID'] = filter_var($row['ticketID'],FILTER_SANITIZE_STRING);
                                    echo('
                                    <input type="submit" value="More" name="action" class="subbtn">&nbsp;
                                    </form>
                                    <form 
                                        method="POST" 
                                        action="contactadmin.php"
                                        onsubmit="return confirm('."'Are You Sure?'".')">
                                    <input type="hidden" name="ticketID" value="'.$row['ticketID'].'">                                  
                                    <input type="submit" value="Delete" name="action" class="subbtn">&nbsp;
                                    </form>'
                                .'</td>
                                </tr>');
                        }
                    ?>
                </table>
            </div>
        </main>
        <?php
            include('foot.html');
        ?>
    </body>
</html>
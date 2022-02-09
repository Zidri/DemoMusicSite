<?php
    require('connect.php');

    if(!isset($_SESSION['logged']) || $_SESSION['logged']==false){
        header('Location: login.php');
    }

    // get songs from db
    $sql_get = 'SELECT * FROM tbl_songs';

    // execute
    $r = $pdo->query($sql_get);
    
?>

<html>
    <head>
        <title>Songs</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="header">
            <h1>Songs</h1>
        </div>
        <?php include('nav.php'); ?>
        <main>
        <div class="music">
            <?php
                while(($row = $r->fetch())!=null){
            
            
                    try{
                        echo('<div class="musiclabel">'.$row['title'].' - '.$row['artistName'].'</div>');
                        
                        // retrieve song
                        // data url example: 'data:audio/mp3;base64,mypic.jpg'
                        echo('
                        <audio controls>                        
                            <source src="data:'.$row['filetype'].';base64,'.$row['song'].'">
                        </audio>
                        ');
                        
                    }
                    catch(Exception $e){
                        echo('Error: '.$e->getMessage());
                    }
                    
        
                }
            ?>
            <br>
        </div>
        </main>
        <?php
            include('foot.html');
        ?>
    </body>
</html>
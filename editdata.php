<?php
    require('connect.php');

    // print_r($_POST);
    // echo("<br>");
    // print_r($_FILES);
    if(!empty($_POST || $_FILES)){
        try{
            
        // get file 
        $xfile = $_FILES['song']['tmp_name'];
        $filetype = mime_content_type($xfile);  // not required

        // retrieve file + encode as base64
        $song = base64_encode(file_get_contents($_FILES['song']['tmp_name']));

        //sql to updata db
        $sql_update = "UPDATE tbl_songs
                        SET 
                            title = :title,
                            artistName = :artistName,
                            song = :song,
                            filetype = :filetype
                        WHERE songID = :songID";

        // prepare
        $sql_update = $pdo->prepare($sql_update);

        // sanatize
        $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
        $artistName = filter_var($_POST['artistName'],FILTER_SANITIZE_STRING);

        // bind
        $sql_update->bindparam(':title',$title);
        $sql_update->bindparam(':artistName',$artistName);

        // specify long blob -> do this for all blobs regardless of db size
        $sql_update->bindparam(':song',$song,PDO::PARAM_LOB);
        
        $sql_update->bindparam(':filetype',$filetype);
        $sql_update->bindparam(':songID',$_SESSION['songID']);


            // execute
            $sql_update->execute();
            //go to songsadmin
            header("Location: songsadmin.php");
        }
        catch(PDOException $e){
            $msg = '<div class="err">FAIL: '.$e->getMessage().'</div>';
        }
        
    }

    // get data from db
    $sql_edit = "SELECT * FROM tbl_songs WHERE songID = ".$_SESSION['songID'];
    $rs_edit = $pdo->query($sql_edit);
    $row_edit = $rs_edit->fetch();



    echo('
            <html lang="en">
            <head>
                <title>Song Input</title>                
            </head>
            <body>
                <div class="header"><h1>Song Input</h1></div>'
        );
        include('nav.php');
        
        echo('
            <main>
                <div class="center">');
                if(isset($msg)){
                    echo($msg);
                }
                echo('
                <form action="editdata.php" method="POST" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Title: </td>
                            <td><input type="text" name="title" value="'.$row_edit['title'].'" size="40" require></td>
                        </tr>
                        <tr>
                            <td>Artist Name: </td>
                            <td><input type="text" name="artistName" value="'.$row_edit['artistName'].'" require></td>
                        </tr>
                        <tr>
                            <td>Song: </td>
                            <td><input type="file" name="song" accept=".mpeg, .mp3, .ogg" value="'.$row_edit['filetype'].';base64,'.$row_edit['song'].'"require></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" name="submit" class="subbtn" value="Submit"></td>
                        </tr>
                    </table>
                </form>
                <audio controls>                        
                <source src="data:'.$row_edit['filetype'].';base64,'.$row_edit['song'].'">
                </audio>
                </div>
            </main>
            </body>');
            include('foot.html');
            echo('
            </html>
        ');
?>

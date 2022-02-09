<?php
    require('connect.php');
    if(isset($_POST) && !empty($_POST)){
        
        // print_r($_POST);
        // print_r($_FILES);
        // get file (usualy reserved word)
        $xfile = $_FILES['song']['tmp_name'];
        $filetype = mime_content_type($xfile);  // not required

        // retrieve file + encode as base64
        // base64 is standard for encoding data -> ensures bits handled across network correctly
        // looks the same for any file type
        $song = base64_encode(file_get_contents($_FILES['song']['tmp_name']));

        // sql
        $sql = 'INSERT INTO tbl_songs(title,artistName,song,filetype)
                VALUES (:title,:artistName,:song,:filetype)';

        // prepare
        $sql = $pdo->prepare($sql);

        // sanatize
        $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
        $artistName = filter_var($_POST['artistName'],FILTER_SANITIZE_STRING);

        // bind
        $sql->bindparam(':title',$title);
        $sql->bindparam(':artistName',$artistName);

        // specify long blob -> do this for all blobs regardless of db size
        $sql->bindparam(':song',$song,PDO::PARAM_LOB);
        
        $sql->bindparam(':filetype',$filetype);

        try{
            $upcheck = $sql->execute();
            $msg = '<br>File Uploaded<br>';
            header('Location: songsadmin.php');
        }
        catch(PDOException $e){
            $msg = '<div class="err">FAIL: '.$e->getMessage().'</div>';
        }

    }
    // else{
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
                <form action="songinput.php" method="POST" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Title</td>
                            <td><input type="text" name="title" value="Punky" require></td>
                        </tr>
                        <tr>
                            <td>Artist Name</td>
                            <td><input type="text" name="artistName" value="Benjamin Tissot" require></td>
                        </tr>
                        <tr>
                            <td>Song</td>
                            <td><input type="file" name="song" accept=".mpeg, .mp3, .ogg" require></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" name="submit" class="subbtn" value="Submit"></td>
                        </tr>
                    </table>
                </form>
                </div>
            </main>
            </body>');
            include('foot.html');
            echo('
            </html>
        ');
    // }

?>
<?php
    require('connect.php');

    if(!isset($_SESSION['logged']) || $_SESSION['logged']==false || $_SESSION['admin']==false){
        header('Location: login.php');
    }

    if(isset($_POST)){
        if(!empty($_POST['action'])){
            if($_POST['action']==='Delete'){
                $sID = filter_var($_POST['songID'],FILTER_SANITIZE_STRING);
                $sql_del = "DELETE FROM tbl_songs WHERE songID = ".$sID;
                $pdo->exec($sql_del);
            }
            elseif($_POST['action']==='Edit'){
                $_SESSION['songID'] = filter_var($_POST['songID'],FILTER_SANITIZE_STRING);
                header("Location:editdata.php");
            }
        }
    }

    // get songs from db
    $sql_disp="SELECT * FROM tbl_songs ORDER BY artistName";

    //rs -> record set
    $rows_disp = $pdo->query($sql_disp);
    
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
        <a href="songinput.php"><i class="fas fa-plus"></i></a>
            <div class="center">
            <div class="music">
                <table>
                    <tr>
                        <!-- <td><h2>Song ID</h2></td> -->
                        <td><h2>Title</h2></td>
                        <td><h2>Artist Name</h2></td>
                        <td><h2>Song</h2></td>
                        <td><h2>File Type</h2></td>
                        <td><h2>Edit</h2></td>
                    </tr>
                    <?php
                    while($row = $rows_disp->fetch()){
                            echo('
                                    <tr>
                                        <!--<td>'.$row['songID'].'</td>-->
                                        <td>'.$row['title'].'</td>'
                                        .'<td>'.$row['artistName'].'</td>'
                                        .'<td colspan = "2">
                                        <audio controls>                        
                                        <source src="data:'.$row['filetype'].';base64,'.$row['song'].'">
                                        </audio>
                                         </td>
                                         <td>'.
                                            '<form method="POST" action="songsadmin.php"
                                            onsubmit="return confirm('."'Are You Sure?'".')">
                                            <input type="hidden" name="songID" value="'.$row['songID'].'">
                                            <input type="submit" value="Edit" name="action" class="subbtn">&nbsp;
                                            <input type="submit" value="Delete" name="action" class="subbtn">&nbsp;
                                            </form>'
                                        .'</td>
                                    </tr>
                                ');
                    }  
                    ?>
                </table>   
                </div>         
            </div>
        </main>
        <?php
            include('foot.html');
        ?>
    </body>
</html>
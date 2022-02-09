<!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="style.css">
        <script src="https://kit.fontawesome.com/046dd30d2f.js" crossorigin="anonymous"></script>
    </head>

    <nav><ul>  
        <li><a href="default.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="songs.php">Songs</a></li>
        <?php                    
            if(isset($_SESSION['logged']) && $_SESSION['logged'] === true){
                echo('<li><a href="logout.php">Log Out</a></li>');   
                if($_SESSION['admin']){
                    echo('
                    <li><a href="songsadmin.php">Songs Admin</a></li>                    
                    <li><a href="contactadmin.php">Contact Admin</a></li>
                    ');                    
                }
                else{
                    echo('
                    
                    
                    ');
                }           
            }
            else{
                echo('
                <li><a href="register.php">Sign Up</a></li>
                <li><a href="login.php">Log In</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                ');
                  
            }
        ?>
          
    </ul></nav>
    <?php
        if(isset($_SESSION['logged'])){
            echo('<div class = "user">Welcome '.$_SESSION['username'].'</div>');
        }
    ?>
    
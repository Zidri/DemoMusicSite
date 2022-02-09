<?php
    require('connect.php');

    // don't show warnings
    error_reporting(0);

        if(isset($_POST['username'])/* && !empty($_POST)*/){
            $_SESSION['username'] = $_POST['username'];

            $sql_login = "SELECT username, password, admin
                            FROM tbl_user
                            WHERE username = :username";

                // prepare
                $sql_login = $pdo->prepare($sql_login);

                // sanatize
                $un = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
                // don't have to sanitize password (most don't due to special chars and so it's as expected)
                
                // bind param
                $sql_login -> bindparam(':username',$un);
        
                // execute
                $sql_login -> execute();

                // get login data from db
                $dbdata = $sql_login->fetch();

                if($dbdata['password'] == null){
                $_SESSION['logged'] = false;
                $msg ='<br>Invalid Login<br>';
                }
                else{

                //store password
                $dbpass = $dbdata['password'];
        
                // reverse password process + compare
                if(password_verify($_POST['password'],$dbpass)){
                    // echo('Welcome '.$dbdata['username']);
                    $_SESSION['logged'] = true; 
                    $_SESSION['admin'] =  $dbdata['admin'];               
                    header('location: default.php');
                }
                else{
                    $msg = 'Invalid Password';
                    $_SESSION['logged'] = false;
                }          
            }
                 
        }
                
    
    
    

?>

        <html>
            <head>
                <title>Log In</title>
                <link rel="stylesheet" href="style.css">
            </head>
            <body>
                <div class="header">
                    <h1>Log In</h1>
                </div>

                <?php include('nav.php'); ?>
                <main>
                    <div class = "center">
                        <form method="POST" action="login.php">
                            <table>
                                <?php
                                    if (isset($msg)){
                                        echo('
                                        <tr>
                                            <td colspan="2"><div class="err">'.$msg.'</div></td>
                                        </tr>
                                    ');
                                    }
                                ?>                       
                                <tr>
                                    <td>Username:</td>
                                    <td><input type="text" name="username" size="25" value="LauraJ" require></td>
                                </tr>
                                <tr>
                                    <td>Password:</td>
                                    <td><input type="password" name="password" size="25" value="lj1" require></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" class = "subbtn" name="submit" value="Log In"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </main>
                <?php
                    include('foot.html');
                ?>
            </body>
        </html>
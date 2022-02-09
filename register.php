<?php
    require('connect.php');

    echo('
        <div class="header">
            <h1>Sign Up</h1>
        </div>
    ');
    include('nav.php');

    // print_r($_POST);
    if(isset($_POST['username'])){
        // asssume javascript checked

        try{
            $pwd = $_POST['password'];

            // sql statement
            $sql_insert = "INSERT INTO tbl_user(fname,lname,username,password,admin)
                            VALUES (:fname,:lname,:username,:password,:admin)";

            // prepare
            $sql_insert = $pdo->prepare($sql_insert);
            
            // sanatize
            $fn = filter_var($_POST['fname'],FILTER_SANITIZE_STRING);
            $ln = filter_var($_POST['lname'],FILTER_SANITIZE_STRING);
            $un = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
            // don't have to sanitize password (most don't due to special chars and so it's as expected)
            $admin = filter_var($_POST['admin'],FILTER_VALIDATE_BOOLEAN);

            // hash the password (PASSWORD_DEFAULT mixes letters for security)
            $pwd = password_hash($pwd, PASSWORD_DEFAULT);

            // bind param
            $sql_insert -> bindparam(':fname',$fn);
            $sql_insert -> bindparam(':lname',$ln);
            $sql_insert -> bindparam(':username',$un);
            $sql_insert -> bindparam(':password',$pwd);
            $sql_insert -> bindparam(':admin',$admin);

            // try catch for duplicate username error

            // execute
            $sql_insert -> execute();

            // echo('<p>User Entered</p>');

            header('location: register.php');
        }
        catch(PDOException $ee){
            echo($ee->getMessage());
            echo('<br>');
            echo($ee->getCode());
            echo('<br>');
            if($ee->getCode() == 23000){
                // echo('Select a different username!');
                $_SESSION['register_errmsg'] = 'Select a different username!';
                header('location: register.php');
            }
        }
        
    }
    else{
        echo('
        <html>
            <head>
                <title>Sign Up</title>
            </head>
            <body>
            <main>
                <div class="center">
                    <form method="POST" action="register.php">
                        <table>');
                            if(isset($_SESSION['register_errmsg'])){
                                echo('
                                <tr>
                                    <td colspan="2">
                                        <div class="err">'.$_SESSION['register_errmsg'].'</div>
                                    </td>
                                </tr>
                                ');
                                unset($_SESSION['register_errmsg']);
                            }
                            echo('
                            <tr>
                                <td>First Name:</td>
                                <td><input type="text" name="fname" size="25" value="Laura"></td>
                            </tr>
                            <tr>
                                <td>Last Name:</td>
                                <td><input type="text" name="lname" size="25" value="Johnson"></td>
                            </tr>
                            <tr>
                                <td>Username:</td>
                                <td><input type="text" name="username" size="25" value="LauraJ" require></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><input type="password" name="password" size="25" value="lj1" require></td>
                            </tr>
                            <tr>
                                <td>Admin Account:</td>
                                <td><input type="checkbox" name="admin" size="25" value="1"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="submit" class="subbtn" name="submit" value="Sign Up"></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </main>');
                include('foot.html');
        echo('
            </body>
        </html>
        ');
    }
?>
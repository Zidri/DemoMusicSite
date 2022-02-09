<?php
    require('connect.php');

    echo('
    <head>
        <title>Help</title>
    </head>
    <body>
        <div class="header">
            <h1>Help</h1>
        </div>
    ');
    include('nav.php');

    if(isset($_POST['contactEmail'])){
        // asssume javascript checked

        try{

            // sql statement
            $sql_insert = "INSERT INTO tbl_contact(contactFN,contactLN,contactEmail,contactSubject,contactMsg)
                            VALUES (:contactFN,:contactLN,:contactEmail,:contactSubject,:contactMsg)";

            // prepare
            $sql_insert = $pdo->prepare($sql_insert);

            // sanatize
            $fn = filter_var($_POST['contactFN'],FILTER_SANITIZE_STRING);
            $ln = filter_var($_POST['contactLN'],FILTER_SANITIZE_STRING);
            $mail = filter_var($_POST['contactEmail'],FILTER_VALIDATE_EMAIL);
            $sub = filter_var($_POST['contactSubject'],FILTER_SANITIZE_STRING);
            $Msg = filter_var($_POST['contactMsg'],FILTER_SANITIZE_STRING);

            // prevent empty email from entering database
            // try{
                if($mail == ''){                    
                    $_SESSION['contact_errmsg'] = 'Invalid Email';
                    header('location: contact.php');
                }
            // }
            // catch(Exception $e){
            //     $_SESSION['contact_errmsg'] = "Invalid Email";
            // }
            

            // bind param
            $sql_insert -> bindparam(':contactFN',$fn);
            $sql_insert -> bindparam(':contactLN',$ln);
            $sql_insert -> bindparam(':contactEmail',$mail);
            $sql_insert -> bindparam(':contactSubject',$sub);
            $sql_insert -> bindparam(':contactMsg',$Msg);

            // execute
            $sql_insert -> execute();

            header('location: contact.php');
        }
        catch(PDOException $ee){
            echo($ee->getMessage());
            echo('<br>');
            echo($ee->getCode());
            echo('<br>');
            $_SESSION['contact_errmsg'] = $ee;
        }
        
    }
    else{
        echo('
        <main>
            <div class="center">
            <h2>Need Help?</h2>
                <form method="POST">
                    <table>');
                    if(isset($_SESSION['contact_errmsg'])){
                        echo('
                        <tr>
                            <td colspan="2">
                                <div class="err">'.$_SESSION['contact_errmsg'].'</div>
                            </td>
                        </tr>
                        ');
                        unset($_SESSION['contact_errmsg']);
                    }
                    echo('
                        <tr>
                            <td><label for="contactFN">First Name:</label></td>
                            <td>
                                <input type="text" id="contactFN" name="contactFN" size="30" tabindex="1" value="Laci" required>
                            </td>    
                        </tr>
                        <tr>                            
                            <td><label for="contactFN">Last Name:</label></td> 
                            <td>
                                <input type="text" id="contactLN" name="contactLN" size="30" tabindex="2" value="Kane" required>
                            </td>
                        </tr>
                        <tr>                            
                            <td><label for="contactEmail">Email:</label></td>
                            <td>
                                <input type="email" id="contactEmail" name="contactEmail" size="30" tabindex="3" value="lacik@mail.com" required>
                            </td>
                        </tr>
                        <tr>                            
                            <td><label for="contactSubject">Subject:</label></td>
                            <td>
                                <input type="text" id="contactSubject" name="contactSubject" size="30" tabindex="4" value="No Subject" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><label for="contactMsg">Message:</label></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea id="contactMsg" name="contactMsg" tabindex="4" rows="4" cols="40" required>Your Message</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" class="subbtn" name="submit" value="Send"></td>
                        </tr>                    
                    </table>
                </form>
            </div>
        </main>
        ');
        include('foot.html');
        echo('
            </body>
        </html>
        '); 
    }
?>
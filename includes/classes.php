<?php

class User {
    private static $username;
    private static $password;

    public static function userLogin($username, $password) {
        global $db;
        self::$username = $username;
        self::$password = $password;

        if (self::$username . "" <> "") {
            $userSQL = "
                SELECT
                   `UserId`, `UserName`, `FirstName`,
                   `LastName`, `EmailAddress`, `GroupId`,
                   `Password`, `DarkMode`
                 FROM
                   `User`
                 WHERE
                   `UserName` = '" . self::$username . "';
            ";
            $response = mysqli_query($db, $userSQL);
            $row_cnt = mysqli_num_rows($response);
            $userRS = mysqli_fetch_assoc($response);
    
            if ($row_cnt === 0) {
                // ### HANDLER: USER NOT FOUND ###
                $_SESSION["hasAlert"] = true;
                $_SESSION["alertType"] = "danger";
                $_SESSION["alertMessage"] = "User account email or password is incorrect.";
            } elseif (!password_verify(self::$password, $userRS["Password"])) {
                // ### HANDLER: USER FOUND, PASSWORD INCORRECT ###
                $_SESSION["hasAlert"] = true;
                $_SESSION["alertType"] = "danger";
                $_SESSION["alertMessage"] = "User account email or password is incorrect.";
            } else {
                // ### HANDLER: LOGIN SUCCESSFUL - NOW PUT USER DETAILS IN SESSION ###
                $_SESSION["loggedIn"]      = true;
                $_SESSION["userId"]        = $userRS["UserId"];
                $_SESSION["userName"]      = $userRS["UserName"];
                $_SESSION["userFirstName"] = $userRS["FirstName"];
                $_SESSION["userLastName"]  = $userRS["LastName"];
                $_SESSION["userFullName"]  = $userRS["FirstName"] . " " . $userRS["LastName"];
                $_SESSION["userEmail"]     = $userRS["EmailAddress"];
                $_SESSION["userGroup"]     = $userRS["GroupId"];
                $_SESSION["darkMode"]      = $userRS["DarkMode"];
    
                LogReport(4, $_SESSION["userFullName"] . " has logged in", $_SESSION["userId"]);
    
                header("Location: index.php");
            }
        }
    }
}
?>
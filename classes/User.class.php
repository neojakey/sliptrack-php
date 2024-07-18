<?php
class User {
    public static function CreateUser($username, $firstName, $lastName, $email, $password, $groupId, $darkMode) {
        $userColumns = "UserName,FirstName,LastName,EmailAddress,Password,GroupId,DarkMode";
        $userValues = formatDbField($username, "text", false) . ",
                  " . formatDbField($firstName, "text", false) . ",
                  " . formatDbField($lastName, "text", false) . ",
                  " . formatDbField($email, "text", false) . ",
                  " . formatDbField(password_hash($password, PASSWORD_DEFAULT), "text", false) . ",
                  " . formatDbField($groupId, "int", false) . ",
                  " . formatDbField($darkMode, "bit", false);
        $userId = InsertNewRecord("User", $userColumns, $userValues);
        return $userId;
    }

    public static function UpdateUser($userId, $firstName, $lastName, $emailAddress, $groupId, $password, $darkMode) {
        global $db;
            $strSQL = "
                UPDATE `User` SET
                   `DarkMode` = " . formatDbField($darkMode, "bit", false) . ",
                   `FirstName` = " . formatDbField($firstName, "text", false) . ",";
            if (trim($password . "") != "") {
                $strSQL = $strSQL . "   `Password` = " . formatDbField(password_hash($password, PASSWORD_DEFAULT), "text", false) . ",";
            }
            $strSQL = $strSQL . "   `LastName` = " . formatDbField($lastName, "text", false) . ",";
            if (hasPermission("prmGroups", "edit")) {
                $strSQL = $strSQL . "   `GroupId` = " . formatDbField($groupId, "int", false) . ",";
            }
            $strSQL = $strSQL . "   `EmailAddress` = " . formatDbField($emailAddress, "text", false) . " WHERE `UserId` = " . formatDbField($userId, "int", false) . "";
        mysqli_query($db, $strSQL);

        // ### UPDATE SESSION VARIABLES IF ITS ME ###
        if ($_SESSION["userId"] === $userId) {
            $_SESSION["userFirstName"] = $firstName;
            $_SESSION["userLastName"] = $lastName;
            $_SESSION["userFullName"] = $firstName . " " . $lastName;
            $_SESSION["userEmail"] = $emailAddress;
            $_SESSION["userGroup"] = $groupId;
            $_SESSION["darkMode"] = $darkMode;
        }
    }

    public static function UpdateUserColorMode($userId, $darkMode) {
        global $db;
        $strSQL = "
            UPDATE `User` SET
                `DarkMode` = " . formatDbField($darkMode, "bit", false) . "
            WHERE
                `UserId` = " . formatDbField($userId, "int", false);
        mysqli_query($db, $strSQL);

        $_SESSION["darkMode"] = $darkMode;
    }

    public static function UpdatePassword($username, $password) {
        global $db;
        $strSQL = "
            UPDATE `User` SET
                `Password` = " . formatDbField(password_hash($password, PASSWORD_DEFAULT), "text", false) . "
            WHERE
                `UserId` = " . formatDbField($username, "int", false);
        mysqli_query($db, $strSQL);
    }

    public static function DeleteUser($userId) {
        global $db;
        mysqli_query($db, "DELETE FROM `User` WHERE `UserId` = " . formatDbField($userId, "int", false));
    }

    public static function GetUserFullName($userId) {
        global $db;
        $userSQL = "SELECT `FirstName`, `LastName` FROM `User` WHERE `UserId` = " . formatDbField($userId, "int", false);
        $response = mysqli_query($db, $userSQL);
        $row_cnt = mysqli_num_rows($response);
        if ($row_cnt !== 0) {
            $row = mysqli_fetch_row($response);
            return $row["FirstName"] . " " . $row["LastName"];
        } else {
            return "";
        }
   }

    public static function UserLogin($username, $password) {
        global $db;

        if ($username . "" <> "") {
            $userSQL = "
                SELECT
                   `UserId`, `UserName`, `FirstName`,
                   `LastName`, `EmailAddress`, `GroupId`,
                   `Password`, `DarkMode`
                 FROM
                   `User`
                 WHERE
                   `UserName` = '" . $username . "';
            ";
            $response = mysqli_query($db, $userSQL);
            $row_cnt = mysqli_num_rows($response);
            $userRS = mysqli_fetch_assoc($response);
    
            if ($row_cnt === 0) {
                // ### HANDLER: USER NOT FOUND ###
                SystemAlert::SetAlert("danger", "User account email or password is incorrect.");
            } elseif (!password_verify($password, $userRS["Password"])) {
                // ### HANDLER: USER FOUND, PASSWORD INCORRECT ###
                SystemAlert::SetAlert("danger", "User account email or password is incorrect.");
            } else {
                // ### HANDLER: LOGIN SUCCESSFUL - NOW PUT USER DETAILS IN SESSION ###
                $_SESSION["loggedIn"] = true;
                $_SESSION["userId"] = $userRS["UserId"];
                $_SESSION["userName"] = $userRS["UserName"];
                $_SESSION["userFirstName"] = $userRS["FirstName"];
                $_SESSION["userLastName"] = $userRS["LastName"];
                $_SESSION["userFullName"] = $userRS["FirstName"] . " " . $userRS["LastName"];
                $_SESSION["userEmail"] = $userRS["EmailAddress"];
                $_SESSION["userGroup"] = $userRS["GroupId"];
                $_SESSION["darkMode"] = $userRS["DarkMode"];
    
                SystemLog::LogReport(4, $_SESSION["userFullName"] . " has logged in", $_SESSION["userId"]);
    
                header("Location: index.php");
            }
        }
    }

    public static function UserLogout() {
        if (isset($_SESSION["loggedIn"])) {
            SystemLog::LogReport(4, $_SESSION["userFullName"] . " has logged out", $_SESSION["userId"]);
            session_unset();
            session_destroy();
        }
        header("Location: " . BASE_URL . "/login.php");
    }
}
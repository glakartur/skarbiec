<?php
    session_start();

// echo "#:\n";
//     var_dump($_GET);
//     echo "#:\n";
//     var_dump($_POST);
//     echo "#:\n";
//     var_dump($_SESSION);
//     echo "#:\n";
//     echo "#:\n";
//     header("Content-type: application/json");
// die("Koniec");

    require_once('../domain/permissions.php');

    require_once('../config.php');
    $cfg = new Config('../');

    require_once('../domain/auth.php');
    $auth = new Auth($cfg);

    if (!empty($_SESSION['user']))
    {
        $auth->SwitchToUser($_SESSION['user']);
    }

    if (!$auth->IsLoggedIn())
    {
        header("403 Authorization needed");
        die("403 Authorization needed");
    }

    include_once("../domain/accounts.php");
    $accounts = new Accounts($cfg);
    $accounts->Load();

    $operation = $_GET['op'];
    

    switch  ($operation)
    {
        case "add":
            if (!$auth->HasPermission(Permission::EditAccount))
            {
                echo "403 No add-account permission";
                header("403 Forbidden");
                return;
            }
            $name = $_GET['name'];
            $accounts->Add($name);
            break;
        case "close":
            if (!$auth->HasPermission(Permission::EditAccount))
            {
                echo "403 No " . Permission::EditAccount . " permission";
                header("403 Forbidden");
                return;
            }
            $accountId = (int)$_GET['account'];
            $accounts->Close($accountId);
            break;
        default:
            die("Bad request");
    }

    $accounts->Save();
?>

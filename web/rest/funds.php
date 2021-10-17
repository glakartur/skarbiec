<?php
    session_start();

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

    include_once("../domain/funds.php");
    $funds = new Funds($cfg);
    $funds->Load();

    $operation = $_GET['op'];
    

    switch  ($operation)
    {
        case "add":
            if (!$auth->HasPermission(Permission::EditFund))
            {
                echo "403 No " . Permission::EditFund . " permission";
                header("403 Forbidden");
                return;
            }
            $name = $_GET['name'];
            $accountId = (int)$_GET['account'];
            $money = (float) str_replace(",",".",$_GET['money']);
            $required = (boolean)$_GET['required'];
            $description = (int)$_GET['description'];
            $funds->Add($name, $money, $accountId, $required, $description);
            break;
        case "close":
            if (!$auth->HasPermission(Permission::EditFund))
            {
                echo "403 No " . Permission::EditFund . " permission";
                header("403 Forbidden");
                return;
            }
            $fundId = (int)$_GET['fund'];
            $funds->Close($fundId);
            break;
        default:
            die("Bad request");
    }

    $funds->Save();
?>

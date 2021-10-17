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

    include_once("../domain/expenses.php");
    $expenses = new Expenses($cfg);
    $expenses->Load();

    $operation = $_GET['op'];
    

    switch  ($operation)
    {
        case "add":
            if (!$auth->HasPermission(Permission::EditExpense))
            {
                echo "403 No " . Permission::EditExpense . " permission";
                header("403 Forbidden");
                return;
            }
            $name = $_GET['name'];
            $accountId = (int)$_GET['account'];
            $expenses->Add($name, $accountId);
            break;
        case "close":
            if (!$auth->HasPermission(Permission::EditExpense))
            {
                echo "403 No " . Permission::EditExpense . " permission";
                header("403 Forbidden");
                return;
            }
            $expenseId = (int)$_GET['expense'];
            $expenses->Close($expenseId);
            break;
        default:
            die("Bad request");
    }

    $expenses->Save();
?>

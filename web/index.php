<?php
    session_start();

    unset($event);

    require_once('config.php');
    $cfg = new Config();

    require_once('domain/auth.php');
    $auth = new Auth($cfg);

    $needsAuth = true;

    $page = $_GET['p'];
    switch ($page)
    {
        case "logout":
            unset($_SESSION["user"]);
            header("Location:/?p=login");
            return;
        case "auth":
            $login = $_POST['login'];
            $password = $_POST['password'];
            switch ($auth->Verify($login, $password))
            {
                case true:
                    $_SESSION['user'] = $login;
                    $page = "none";
                    break;
                case false:
                    $event = "Logowanie nieudane :-(";
                    $page = "login";
                    break;
            }
            break;
        case "regac":
            require_once('domain/registration.php');
            $reg = new Registration($cfg);
            $reg->Register($_POST['login'], $_POST['phone'], $_POST['student']);

            if ($reg->IsRegistered())
            {
                $page = "registered";
            }
            else
            {
                $event = "Coś się nie udało :-(";
                $page = "reg";
            }
            $needsAuth = false;
            break;
        case "reg":
            $needsAuth = false;
            break;
        }

    if ($needsAuth)
    {
        if (empty($_SESSION['user']))
        {
            $page = "login";
        }
        else
        {
            $auth->SwitchToUser($_SESSION['user']);
        }
    }

    include("views/main.php");
?>

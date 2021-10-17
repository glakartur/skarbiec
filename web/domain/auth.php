<?php

class Auth
{
    private $cfg;
    private $user;

    public function __construct($cfg)
    {
        $this->cfg = $cfg;
    }

    public function Verify($login, $password)
    {
        if (empty($login))
        {
            return false;
        }    

        if (empty($password))
        {
            return false;
        }    

        $usersJson = file_get_contents($this->cfg->usersFileName);
        if (!isset($usersJson) || empty($usersJson))
        {
            return false;
        }
        
        $users = json_decode($usersJson);
        if (!isset($users) || empty($users))
        {
            return false;
        }

        foreach ($users as $user)
        {
            if ($user->login == $login)
            {
                $hashed_password = hash('sha512', "{$password}.{$user->salt}");
                if ($hashed_password != $user->password)
                {
                    return false;
                }

                $this->user = $user;
                return true;
            }
        }

        return false;
    }

    public function IsLoggedIn()
    {
        if (!isset($this->user))
            return false;

        return true;
    }

    public function SwitchToUser($login)
    {
        if (empty($login))
        {
            return;
        }

        $usersJson = file_get_contents($this->cfg->usersFileName);
        if (!isset($usersJson) || empty($usersJson))
        {
            return;
        }
        
        $users = json_decode($usersJson);
        if (!isset($users) || empty($users))
        {
            return;
        }

        foreach ($users as $user)
        {
            if ($user->login == $login)
            {
                $this->user = $user;
                return;
            }
        }
    }

    public function HasPermission($permission)
    {
        if (!isset($this->user))
            return false;

        return in_array($permission, $this->user->permissions, $strict = false);
    }
}
/*
[Wed Sep 22 00:07:12.477299 2021] [proxy_fcgi:error] [pid 29539:tid 140176814896896] [client 77.253.97.127:42740] AH01071: 
    Got error 'PHP message: PHP Fatal error:  Uncaught Error: 
        Cannot use object of type stdClass as array in /home/skarbiec/domains/skarbiec8e.online/public_html/domain/auth.php:61\n
        Stack trace:\n
            #0 /home/skarbiec/domains/skarbiec8e.online/public_html/index.php(63): Auth->SwitchToUser()\n
            #1 {main}\n
              thrown in /home/skarbiec/domains/skarbiec8e.online/public_html/domain/auth.php on line 61', referer: https://skarbiec8e.online/?p=ex


[Wed Sep 22 00:10:51.741395 2021] [proxy_fcgi:error] [pid 29539:tid 140176697399040] [client 77.253.97.127:42742] AH01071: 
    Got error 'PHP message: PHP Fatal error:  Uncaught Error:
        Cannot use object of type stdClass as array in /home/skarbiec/domains/skarbiec8e.online/public_html/domain/auth.php:66\n
        Stack trace:\n#0 /home/skarbiec/domains/skarbiec8e.online/public_html/index.php(63): Auth->SwitchToUser()\n
            #1 {main}\n  thrown in /home/skarbiec/domains/skarbiec8e.online/public_html/domain/auth.php on line 66', referer: https://skarbiec8e.online/?p=ex
              */

// if (!empty($_POST['login']) && !empty($_POST['password']))
// {
//     if ($_POST['login'] == 'USERNAME')
//     {
//         if (password_verify($_POST['password'], 'PASSWORD'))
//         {
//             $_SESSION['user'] = htmlspecialchars($_POST['login']);  
//         }
//     }
// }

//header("Location: #");    
/*

            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
				
               if ($_POST['username'] == 'tutorialspoint' && 
                  $_POST['password'] == '1234') {
                  $_SESSION['valid'] = true;
                  $_SESSION['timeout'] = time();
                  $_SESSION['username'] = 'tutorialspoint';
                  
                  echo 'You have entered valid use name and password';
               }else {
                  $msg = 'Wrong username or password';
               }

*/
?>
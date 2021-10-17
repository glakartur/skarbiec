<?php

class Registration
{
    private $cfg;
    private $registered = false;

    function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    public function Register($login, $phone, $student)
    {
        if (empty($login))
            return;

        if (empty($phone))
            return;

        if (empty($student))
            return;

        file_put_contents($this->cfg->registrationsFileName, 
            $login . "\n"
            . $phone . "\n"
            . $student . "\n" . "\n",
            FILE_APPEND);

        $this->registered = true;

        mail("artur.gawrylak@wp.pl, artur.gawrylak@assecobs.pl","Skarbiec 8e - rejestracja","Nastąpiła rejestracja nowego użytkownika");
    }

    public function IsRegistered()
    {
        return $this->registered;
    }
}

?>
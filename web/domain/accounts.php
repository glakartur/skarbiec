<?php

class Accounts {
    private $accounts = array();
    private $cfg;

    function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    public function Load()
    {
        $accountsJson = file_get_contents($this->cfg->accountsFileName);
        if ($accountsJson == null)
        {
            return;
        }
        
        $this->accounts = json_decode($accountsJson);
        usort($this->accounts, function($a, $b) {
            $ba = (bool)(isset($a->isActive) ? $a->isActive : true);
            $bb = (bool)(isset($b->isActive) ? $b->isActive : true);

            if ($ba == $bb)
                return strcmp($a->name, $b->name);
            
            if ($ba)
                return -1;

            return 1;
        });
    }

    public function Save()
    {
        echo "Saving...";
        $accountsJson = json_encode($this->accounts, $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents($this->cfg->accountsFileName, $accountsJson);
        echo "...Done.";        
    }

    public function Get($accountId)
    {
        foreach ($this->accounts as $account)
        {
            if ($account->id == $accountId)
                return $account;
        }
        return null;
    }

    public function List()
    {
        return $this->accounts;
    }

    public function ListActiveOrdered()
    {
        $list = array();
        foreach ($this->accounts as $account)
        {
            if ($this->IsActive($account))
                array_push($list, $account);
        }

        return $list;
    }

    public function IsActive($account) //HACK: póki flaga isActive nie jest przypisana do wszystkich kont
    {
        if (!isset($account))
            return false;

        if (!isset($account->isActive))
            return true;

        return $account->isActive == true;
    }

    public function Add($name)
    {
        $maxId = 0;
        foreach ($this->accounts as $account)
        {
            if ($account->id > $maxId)
                $maxId = $account->id;
            
            if ($account->name == $name)
                return;
        }

        $maxId += 1;

        array_push($this->accounts, [ "id" => $maxId, "name" => $name, "isActive" => True ]);
    }

    public function GetPerson($id)
    {
        foreach ($this->accounts as $account)
        {
            if ($account->id == $id)
                return $account;
        }

        return null;
    }

    public function Close($accountId)
    {
        foreach ($this->accounts as $account)
        {
            if ($account->id == $accountId)
            {
                $account->isActive = false;
            
                echo "Closed.";
                return;
            }
        }
        echo "Not found. Bye.";
    }

}

?>
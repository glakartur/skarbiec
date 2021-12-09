<?php

class Funds {
    private $funds = array();
    private $cfg;

    function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    public function Load()
    {
        $fundsJson = file_get_contents($this->cfg->fundsFileName);
        if ($fundsJson == null)
        {
            return;
        }
        
        $this->funds = json_decode($fundsJson);
        $this->funds = $this->OrderByName($this->funds);
        // usort($this->funds, function($a, $b) {
        //     $ba = (bool)(isset($a->isActive) ? $a->isActive : true);
        //     $bb = (bool)(isset($b->isActive) ? $b->isActive : true);

        //     if ($ba == $bb)
        //         return strcmp($a->name, $b->name);
            
        //     if ($ba)
        //         return -1;

        //     return 1;
        // });
    }

    function OrderByName($list)
    {
        usort($list, function($a, $b) {
            $ba = (bool)(isset($a->isActive) ? $a->isActive : true);
            $bb = (bool)(isset($b->isActive) ? $b->isActive : true);
            
            if ($ba != $bb)
                return ($ba) ? -1 : 1;

            $ca = (bool)(isset($a->required) ? $a->required : false);
            $cb = (bool)(isset($b->required) ? $b->required : false);

            if ($ca != $cb)
                return ($ca) ? -1 : 1;

            return strcmp($a->name, $b->name);
        });

        return $list;
    }

    public function Save()
    {
        echo "Saving...";
        $fundsJson = json_encode($this->funds, $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents($this->cfg->fundsFileName, $fundsJson);

        echo $fundsJson;
        echo "...Done.";
    }

    public function List()
    {
        return $this->funds;
    }

    public function ListActiveSorted()
    {
        $list = [];

        foreach ($this->funds as $fund)
        {
            if (!$this->IsActive($fund))
                continue;

            array_push($list, $fund);
        }

        $list = $this->OrderByName($list);

        return $list;
    }

    public function ListActiveForAccount($accountId)
    {
        $list = [];

        foreach ($this->funds as $fund)
        {
            if (!$this->IsActive($fund))
                continue;

            if ($fund->accountId != $accountId)
                continue;
            
            array_push($list, $fund);
        }

        return $list;
    }

    public function GetFund($id)
    {
        foreach ($this->funds as $fund)
        {
            if ($fund->id == $id)
                return $fund;
        }

        return null;
    }

    public function IsActive($fund) //HACK: póki flaga isActive nie jest przypisana do wszystkich kont
    {
        if (!isset($fund))
            return false;

        if (!isset($fund->isActive))
            return true;

        return $fund->isActive != false;
    }

    public function Close($fundId)
    {
        foreach ($this->funds as $fund)
        {
            if ($fund->id == $fundId)
            {
                $fund->isActive = false;
            
                echo "Closed.";
                return;
            }
        }
        echo "Not found. Bye.";
    }

    public function Add($name, $money, $accountId, $required, $description)
    {
        if (empty($name) || !isset($accountId) || empty($accountId) || empty($required) || empty($money))
        {
            die ("Invalid request data: NAME: " . $name 
                    . " MONEY: ". $money
                    . " REQUIRED: ". $required
                    . " ACCOUNT: ". $accountId);
        }

        $maxId = 0;
        foreach ($this->funds as $fund)
        {
            if ($fund->id > $maxId)
                $maxId = $fund->id;
            
            if ($fund->name == $name)
                return;
        }

        $maxId += 1;

        array_push($this->funds, 
            [ 
                "id" => $maxId, 
                "name" => $name, 
                "value" => $money, 
                "accountId" => $accountId, 
                "description" => $description, 
                "required" => $required,
                "isActive" => True ]);
    }
}

?>
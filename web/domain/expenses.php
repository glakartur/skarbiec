<?php

class Expenses {
    private $expenses = array();
    private $cfg;

    function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    public function Load()
    {
        $expensesJson = file_get_contents($this->cfg->expensesFileName);
        if ($expensesJson == null)
        {
            return;
        }
        
        $this->expenses = json_decode($expensesJson);
        usort($this->expenses, function($a, $b) {
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
        $expensesJson = json_encode($this->expenses, $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents($this->cfg->expensesFileName, $expensesJson);
        echo "...Done.";        
    }

    public function Get($expenseId)
    {
        foreach ($this->expenses as $expense)
        {
            if ($expense->id == $expenseId)
                return $expense;
        }
        return null;
    }

    public function List()
    {
        return $this->expenses;
    }

    public function ListActive()
    {
        $list = [];

        foreach ($this->expenses as $expense)
        {
            if (!$this->IsActive($expense))
                continue;

            array_push($list, $expense);
        }

        return $list;

    }

    public function ListActiveForAccount($accountId)
    {
        $list = [];

        foreach ($this->expenses as $expense)
        {
            if (!$this->IsActive($expense))
                continue;

            if ($expense->accountId != $accountId)
                continue;
            
            array_push($list, $expense);
        }

        return $list;

    }

    public function IsActive($expense) //HACK: póki flaga isActive nie jest przypisana do wszystkich kont
    {
        if (!isset($expense))
            return false;

        if (!isset($expense->isActive))
            return true;

        return $expense->isActive != false;
    }

    public function Add($name, $accountId)
    {
        if (empty($name) || !isset($accountId) || empty($accountId))
        {
            die ("Invalid request data: NAME: " . $name . " ACCOUNT: ". $accountId);
        }

        $maxId = 0;
        foreach ($this->expenses as $expense)
        {
            if ($expense->id > $maxId)
                $maxId = $expense->id;
            
            if ($expense->name == $name)
                return;
        }

        $maxId += 1;

        array_push($this->expenses, [ "id" => $maxId, "name" => $name, "accountId" => $accountId, "isActive" => True ]);
    }

    public function Close($expenseId)
    {
        foreach ($this->expenses as $expense)
        {
            if ($expense->id == $expenseId)
            {
                $expense->isActive = false;
            
                echo "Closed.";
                return;
            }
        }
        echo "Not found. Bye.";
    }

}

?>
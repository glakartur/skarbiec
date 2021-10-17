<?php

class Transfers {
    private $transfers = array();
    private $cfg;

    function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    public function Load()
    {
        $transfersJson = file_get_contents($this->cfg->transfersFileName);
        if ($transfersJson == null)
        {
            return;
        }
        
        $this->transfers = json_decode($transfersJson);
    }

    public function Save()
    {
        echo "Saving...";
        $transfersJson = json_encode($this->transfers, $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents($this->cfg->transfersFileName, $transfersJson);
        echo "...Done.";        
    }

    public function Sumarize($accountId)
    {
        $sum = 0;
        foreach ($this->transfers as $transfer)
        {
            if ($transfer->accountId == $accountId)
                $sum += $transfer->money;
        }

        return $sum;
    }

    public function SumarizeFundIncomes($fundId)
    {
        $sum = 0;
        foreach ($this->transfers as $transfer)
        {
            if (!isset($transfer->fundId))
                continue;

            if ($transfer->fundId != $fundId)
                continue;
            
            $sum += $transfer->money;
        }

        return $sum;
    }

    public function SumarizeAccountFunds($accountId)
    {
        $sum = 0;
        foreach ($this->transfers as $transfer)
        {
            if ($transfer->accountId != $accountId)
                continue;
            
            if (!isset($transfer->fundId))
                continue;

            $sum += $transfer->money;
        }

        return $sum;
    }

    public function SumarizeAccountExpenses($accountId)
    {
        $sum = 0;
        foreach ($this->transfers as $transfer)
        {
            if ($transfer->accountId != $accountId)
                continue;
            
            if (isset($transfer->fundId))
                continue;

            $sum += $transfer->money;
        }

        return $sum;
    }

    public function AddExpenseTransfer($accountId, $expenseId, $money, $description, $uuid)
    {
        array_push($this->transfers, [ "accountId" => $accountId, "expenseId" => $expenseId, "money" => $money, "description" => $description, "uuid" => $uuid ]);
    }

    public function AddFundTransfer($accountId, $fundId, $money, $description, $uuid)
    {
        array_push($this->transfers, [ "accountId" => $accountId, "fundId" => $fundId, "money" => $money, "description" => $description, "uuid" => $uuid ]);
    }
}

?>
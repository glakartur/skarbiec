<?php

class Bookings {
    private $bookings = array();
    private $cfg;

    function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    public function Load()
    {
        $bookingsJson = file_get_contents($this->cfg->bookingsFileName);
        if ($bookingsJson == null)
        {
            return;
        }
        
        $this->bookings = json_decode($bookingsJson, true);
    }

    public function SetPerson($transactionId, $personId)
    {
        if (isset($this->bookings[$transactionId]))
        {
            $this->bookings[$transactionId]['senderId'] = $personId;
        }
        else 
        {
            $this->bookings[$transactionId] = array('senderId' => $personId);
        }
    }

    public function IsConfirmed($transactionId)
    {
        $tran = $this->bookings[$transactionId];
        if (!isset($tran))
            return false;

        return $tran["isCommited"] === true;
    }

    public function Confirm($transactionId)
    {
        if (!isset($this->bookings[$transactionId]))
            $this->bookings[$transactionId] = [];

        $this->bookings[$transactionId]['isCommited'] = True;
    }

    public function AddFund($transactionId, $personId, $fundId, $accountId, $money, $incomeGuid)
    {
        if (!isset($this->bookings[$transactionId]))
            $this->bookings[$transactionId] = [];
        if (!isset($this->bookings[$transactionId]['funds']))
            $this->bookings[$transactionId]['funds'] = [];

        foreach ($this->bookings[$transactionId]["funds"] as $idx => $book)
        {
            if ($book["fundId"] != $fundId)
                continue;
            if ($book["personId"] != $personId)
                continue;
            $book["money"] += $money;

            $this->bookings[$transactionId]["funds"] = array_replace($this->bookings[$transactionId]["funds"], [$idx => $book]);
            return;
        }
    
        array_push($this->bookings[$transactionId]['funds'], [ 
            "fundId" => $fundId, 
            "personId" => $personId, 
            "accountId" => $accountId, 
            "money" => $money,
            "uuid" => $incomeGuid
        ]);
    }

    public function AddExpense($transactionId, $expenseId, $accountId, $money, $description, $expenseGuid)
    {
        if (!isset($this->bookings[$transactionId]))
            $this->bookings[$transactionId] = [];
        if (!isset($this->bookings[$transactionId]['expenses']))
            $this->bookings[$transactionId]['expenses'] = [];

        array_push($this->bookings[$transactionId]['expenses'], [ 
            "expenseId" => $expenseId, 
            "accountId" => $accountId, 
            "money" => $money,
            "description" => $description, 
            "uuid" => $expenseGuid
        ]);
    }

    public function SumarizeTransactionBookings($transactionId)
    {
        if (!isset($this->bookings[$transactionId]))
            return 0;
    
        $sum = 0;
        if (isset($this->bookings[$transactionId]["funds"]))
        {
            foreach ($this->bookings[$transactionId]["funds"] as $book)
            {
                $sum += $book["money"];
            }
        }

        if (isset($this->bookings[$transactionId]["expenses"]))
        {
            foreach ($this->bookings[$transactionId]["expenses"] as $book)
            {
                $sum += $book["money"];
            }
        }
        return $sum;
    }

    public function SumarizePersonFund($personId, $fundId)
    {
        $sum = 0;

        foreach ($this->bookings as $transaction)
        {
            foreach ($transaction["funds"] as $f)
            {
                if (($f["fundId"] == $fundId) && ($f["personId"] == $personId))
                {
                    $sum += $f["money"];
                }
            }
        }
        return $sum;
    }

    public function ListFunds($transactionId)
    {
        if (!isset($this->bookings[$transactionId]["funds"]))
            return null;
    
        return $this->bookings[$transactionId]["funds"];
    }

    public function ListExpenses($transactionId)
    {
        if (!isset($this->bookings[$transactionId]["expenses"]))
            return null;
    
        return $this->bookings[$transactionId]["expenses"];
    }

    public function GetSenderId($transactionId)
    {
        if (isset($this->bookings[$transactionId]))
        {
            return $this->bookings[$transactionId]['senderId'];
        }
        return null;
    }

    public function List()
    {
        return $this->bookings;
    }

    public function Save()
    {
        echo "Saving...";

        $bookingsJson = json_encode($this->bookings, $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        echo $bookingsJson;

        file_put_contents($this->cfg->bookingsFileName, $bookingsJson);

        echo "...Done.";
    }
}

?>
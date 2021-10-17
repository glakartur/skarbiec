<?php
    class Config {
        public $transactionsFileName = "data/transactions.json";
        public $personsFileName = "data/persons.json";
        public $fundsFileName = "data/funds.json";
        public $transactionsStateFileName = "data/transactionsState.json";
        public $bookingsFileName = "data/bookings.json";
        public $accountsFileName = "data/accounts.json";
        public $usersFileName = "data/users.json";
        public $transfersFileName = "data/transfers.json";
        public $expensesFileName = "data/expenses.json";
        public $registrationsFileName = "data/registrations.txt";

        public function __construct($rootPath = '')
        {
            $this->transactionsFileName = $rootPath . $this->transactionsFileName;
            $this->personsFileName = $rootPath . $this->personsFileName;
            $this->fundsFileName = $rootPath . $this->fundsFileName;
            $this->transactionsStateFileName = $rootPath . $this->transactionsStateFileName;
            $this->bookingsFileName = $rootPath . $this->bookingsFileName;
            $this->accountsFileName = $rootPath . $this->accountsFileName;
            $this->usersFileName = $rootPath . $this->usersFileName;
            $this->transfersFileName = $rootPath . $this->transfersFileName;
            $this->expensesFileName = $rootPath . $this->expensesFileName;
            $this->registrationsFileName = $rootPath . $this->registrationsFileName;
        }
    }
?>
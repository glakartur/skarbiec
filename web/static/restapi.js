function assign_transaction_sender(transactionId, senderId, onSuccess)
{
    console.log("assign_transaction_sender");
    axios.get('/rest/bookings.php?op=setPerson&tran=' + encodeURIComponent(transactionId) + "&sender=" + senderId)
        .then(function (response) {
            console.log("OK - assign_transaction_sender");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - assign_transaction_sender");

            console.log(error);
            return false;
        });

    return "ole";
}

function assign_fund_transaction_sender(transactionId, personId, fundId, money, onSuccess)
{
    console.log("assign_fund_transaction_sender");
    axios.get('/rest/bookings.php?op=assignFund&tran=' + encodeURIComponent(transactionId) + "&person=" + personId + "&fund=" + fundId + "&money=" + money)
        .then(function (response) {
            console.log("OK - assign_fund_transaction_sender");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - assign_fund_transaction_sender");

            console.log(error);
            return false;
        });

}

function assign_expense_transaction_sender(transactionId, expenseId, money, description, onSuccess)
{
    console.log("assign_expense_transaction_sender");
    axios.get('/rest/bookings.php?op=assignExpense&tran=' + encodeURIComponent(transactionId) + "&expense=" + expenseId + "&descr=" + encodeURIComponent(description) + "&money=" + money)
        .then(function (response) {
            console.log("OK - assign_expense_transaction_sender");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - assign_expense_transaction_sender");

            console.log(error);
            return false;
        });

}

function commit_transaction_sender(transactionId, onSuccess)
{
    console.log("commit_transaction_sender");
    axios.get('/rest/bookings.php?op=finish&tran=' + encodeURIComponent(transactionId))
        .then(function (response) {
            console.log("OK - commit_transaction_sender");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - commit_transaction_sender");

            console.log(error);
            return false;
        });

}

function rest_add_account(name, onSuccess)
{
    console.log("rest_add_account");
    axios.get('/rest/accounts.php?op=add&name=' + encodeURIComponent(name))
        .then(function (response) {
            console.log("OK - rest_add_account");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - rest_add_account");

            console.log(error);
            return false;
        });

}

function rest_add_expense(name, accountId, onSuccess)
{
    console.log("rest_add_expense");
    axios.get('/rest/expenses.php?op=add&name=' + encodeURIComponent(name) + '&account=' + accountId)
        .then(function (response) {
            console.log("OK - rest_add_expense");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - rest_add_expense");

            console.log(error);
            return false;
        });

}

function rest_close_fund(fundId, onSuccess)
{
    axios.get('/rest/funds.php?op=close&fund=' + fundId)
        .then(function (response) {
            console.log("OK - rest_close_fund");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - rest_close_fund");
            console.log(error);
            return false;
        });

}

function rest_close_expense(expenseId, onSuccess)
{
    axios.get('/rest/expenses.php?op=close&expense=' + expenseId)
        .then(function (response) {
            console.log("OK - rest_close_expense");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - rest_close_expense");
            console.log(error);
            return false;
        });

}

function rest_close_account(accountId, onSuccess)
{
    axios.get('/rest/accounts.php?op=close&account=' + accountId)
        .then(function (response) {
            console.log("OK - rest_close_account");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - rest_close_account");
            console.log(error);
            return false;
        });

}

function rest_add_fund(name, money, accountId, required, description, onSuccess)
{
    axios.get('/rest/funds.php?op=add&name=' + encodeURIComponent(name) + '&account=' + accountId + 
            '&money=' + money + '&required=' + required + '&description=' + encodeURIComponent(description))
        .then(function (response) {
            console.log("OK - rest_add_fund");
            console.log(response);
            onSuccess();
        })
        .catch(function (error) {
            console.log("ERROR - rest_add_fund");
            console.log(error);
            return false;
        });

}


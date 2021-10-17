function activate_incomes_forms()
{
    var addFundForm = document.getElementById("addFundForm");
    var closeFundForm = document.getElementById("closeFundForm");
    
    addFundForm.querySelector("#addFundForm-commit").addEventListener("click", function (event) 
    {
        var name = document.getElementById("addFundForm-name").value;
        var accountId = document.getElementById("addFundForm-account").value;
        var money = document.getElementById("addFundForm-money").value;
        var required = document.getElementById("addFundForm-required").value;
        var description = document.getElementById("addFundForm-description").value;
        
        var restresult = rest_add_fund(name, money, accountId, required, description, () => {location.reload();});
        console.log(restresult);
    });

    closeFundForm.addEventListener("show.bs.modal", function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        var name = button.getAttribute("data-bs-name");
        var fundId = button.getAttribute("data-bs-id");

        // Update the modal's content.
        closeFundForm.querySelector("#closeFundForm-name").innerText = name;
        closeFundForm.querySelector("#closeFundForm-fundId").value = fundId;
    });
    
    closeFundForm.querySelector("#closeFundForm-commit").addEventListener("click", function (event) 
    {
        var fundId = document.getElementById("closeFundForm-fundId").value;
        var restresult = rest_close_fund(fundId, () => {location.reload();});
        console.log(restresult);
    });
}

function activate_expenses_forms()
{
    var closeExpenseForm = document.getElementById("closeExpenseForm");

    closeExpenseForm.addEventListener("show.bs.modal", function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        var name = button.getAttribute("data-bs-name");
        var expenseId = button.getAttribute("data-bs-id");

        // Update the modal's content.
        closeExpenseForm.querySelector("#closeExpenseForm-name").innerText = name;
        closeExpenseForm.querySelector("#closeExpenseForm-id").value = expenseId;
    });
    
    closeExpenseForm.querySelector("#closeExpenseForm-commit").addEventListener("click", function (event) 
    {
        var expenseId = closeExpenseForm.querySelector("#closeExpenseForm-id").value;
        var restresult = rest_close_expense(expenseId, () => {location.reload();});
        console.log(restresult);
    });
}

function activate_accounts_forms()
{
    var closeAccountForm = document.getElementById("closeAccountForm");

    closeAccountForm.addEventListener("show.bs.modal", function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;
        var name = button.getAttribute("data-bs-name");
        var id = button.getAttribute("data-bs-id");

        // Update the modal's content.
        closeAccountForm.querySelector("#closeAccountForm-name").innerText = name;
        closeAccountForm.querySelector("#closeAccountForm-id").value = id;
    });
    
    closeAccountForm.querySelector("#closeAccountForm-commit").addEventListener("click", function (event) 
    {
        var id = closeAccountForm.querySelector("#closeAccountForm-id").value;
        var restresult = rest_close_account(id, () => {location.reload();});
        console.log(restresult);
    });
}
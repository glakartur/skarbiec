<div class="row mt-5 pt-5">
  <div class="col">

    <div class="card bg-success text-light mt-0 container">
      <div class="card-body pt-5 pb-5">
        <h3 class="card-title fw-bold mb-5 text-white">Rejestracja konta</h3>
        <p>
          Rejestracja konta odbywa się w następujących krokach:
        <ul>
          <li>Wypełniasz formularz</li>
          <li>Dostajesz SMS z hasłem</li>
          <li>Logujesz się przy pomocy podanego adresu email i otrzymanego hasła</li>
          <li>Zmieniasz hasło na własne</li>
        </ul>
        </p>
        <h5>Do dzieła ;-)</h5>
      </div>
    </div>
  </div>

  <div class="col">


    <div class="card text-dark bg-light container border-success mt-5">
      <div class="card-body pt-5 pb-5">

        <div class="text-center"><i data-feather="user-plus" width="48" height="48"></i></div>

        <form action="/?p=regac" class="mt-5 mb-2" method="post">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="login" name="login" placeholder="" />
            <label for="floatingInput">E-mail</label>
          </div>
          <div class="form-floating mb-3">
            <input type="phone" class="form-control" id="phone" name="phone" placeholder="" />
            <label for="floatingInput">Telefon</label>
          </div>
          <div class="form-floating mb-3">
            <input type="student" class="form-control" id="student" name="student" placeholder="" />
            <label for="floatingInput">Imię i nazwisko ucznia</label>
          </div>
          <button type="submit" class="mt-3 btn btn-dark text-light" id="auth" name="auth">Zarejestruj</button>
        </form>

      </div>

    </div>
  </div>

</div>
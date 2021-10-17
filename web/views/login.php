  <div class="row mt-5 pt-5">
    <div class="col">

      <div class="card bg-success text-light mt-5 container">
        <div class="card-body pt-5 pb-5">
          <h2 class="card-title fw-bold mb-5 text-white">Witaj w skarbcu klasy 8e</h2>
          <p>
            Znajdziesz tu informacje o finansach klasy, aktualnych zbiórkach oraz stanie Twoich wpłat.
          </p>

        </div>
      </div>
    </div>

    <div class="col">


      <div class="card text-dark bg-light container border-success">
        <div class="card-body pt-5 pb-5">

          <div class="text-center"><i data-feather="user" width="48" height="48"></i></div>

          <form action="/?p=auth" class="mt-5 mb-2" method="post">
            <div class="form-floating mb-3">
              <input type="email" class="form-control" id="login" name="login" placeholder="" />
              <label for="floatingInput">E-mail</label>
            </div>
            <div class="form-floating">
              <input type="password" class="form-control" id="password" name="password" placeholder="" />
              <label for="floatingPassword">Hasło</label>
            </div>
            <button type="submit" class="mt-3 btn btn-dark text-light" id="auth" name="auth">Zaloguj się</button>
          </form>

          <p class="fs-6">
            <span class="mt-2 opacity-50">Nie masz jeszcze konta? </span>
            <a class="link-primary opacity-75" href="/?p=reg">Zarejestruj się</a>
          </p>
        </div>

      </div>
    </div>

  </div>
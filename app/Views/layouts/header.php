<header class="shadow-sm bg-dark">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
    <div class="container-fluid">

      <!-- LOGO -->
      <a class="navbar-brand fw-bold text-light text-decoration-none" href="/">
        <svg xmlns="http://www.w3.org/2000/svg"
          width="30" height="30"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          aria-hidden="true">
          <path d="M4 11L12 4L20 11" />
          <path d="M6 11V20H18V11" />
          <path d="M10 20V15H14V20" />
        </svg>
      </a>
      <a class="navbar-brand fw-bold text-light text-decoration-none">DropBrasil</a>

      <!-- Botão hambúrguer -->
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Itens da navbar -->
      <div class="collapse navbar-collapse" id="navbarContent">

        <!-- Barra de pesquisa -->
        <form 
          class="d-flex flex-grow-1 mx-lg-3 my-3 my-lg-0"
          action="/buscar"
          method="GET"
        >
          <input 
            class="form-control me-2 rounded-pill" 
            type="search" 
            name="q"
            placeholder="Pesquisar produto..."
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
          >
          <button class="btn btn-outline-light rounded-pill" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
            </svg>
          </button>
        </form>

        <!-- Links e botões -->
        <div class="d-flex align-items-center flex-column flex-lg-row ms-lg-3">

          <a href="/contato" class="nav-link text-light mb-2 mb-lg-0 me-lg-3">Contato</a>

          <a href="/carrinho" class="nav-link text-light mb-2 mb-lg-0 me-lg-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
              <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0" />
            </svg>
          </a>

          <?php if ($isLoggedIn): ?>

            <!-- 🔽 Menu de administração -->
            <?php if ($user->role == 'admin'): ?>
              <div class="dropdown mb-2 mb-lg-0 me-lg-3">
                <button class="btn btn-outline-light rounded-pill dropdown-toggle px-3 w-100 w-lg-auto" type="button" id="adminMenu" data-bs-toggle="dropdown" aria-expanded="false">
                  Administração
                </button>
                <ul class="dropdown-menu dropdown-menu-dark bg-dark shadow" aria-labelledby="adminMenu">
                  <li>
                    <a class="dropdown-item text-light" href="/produto/adicionar">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                      </svg>
                      Adicionar produto
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item text-light" href="/produto/editar">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                      </svg>
                      Editar produto
                    </a>
                  </li>
                </ul>
              </div>
            <?php endif; ?>

            <a href="/logout" class="btn btn-outline-light rounded-pill px-3 w-100 w-lg-auto mb-2 mb-lg-0">Sair</a>

          <?php else: ?>
            <a href="#" id="openLoginBtn" class="btn btn-outline-light rounded-pill px-3 w-100 w-lg-auto mb-2 me-1 mb-lg-0">Entrar</a>
            <a href="#" id="openCadastroBtn" class="btn btn-light rounded-pill text-dark px-3 w-100 w-lg-auto">Cadastrar</a>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </nav>
</header>

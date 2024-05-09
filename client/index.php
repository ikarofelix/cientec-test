<link rel="stylesheet" href="index.css" type="text/css" />

<main>
  <div class="header">
    <button
      onclick="renderCreateUser()"
      id="createUserButton"
    >Criar usuário</button>
    <button 
      onclick="renderSearchUser()"
      id="searchUserButton"
    >Buscar usuário</button>
  </div>
  <form id="createUserForm">
    <input type="text" id="name" name="name" placeholder="Nome:">
    <div class="user_created_container">
      <span class="user_created_title">Usuário criado com sucesso</span>
      <div>
        <div>
          <span>Nome:</span>
          <span id="user_created_name"></span>
        </div>
        <div>
          <span>Nis:</span>
          <span id="user_created_nis"></span>
        </div>
      </div>
    </div>
    <div class="error_container">
      <span class="error_title"></span>
    </div>
    <button onclick="createNewUser(event)" type="button">
      Criar
    </button>
  </form>
  <form id="searchUserForm">
    <input type="text" id="nis" name="nis" placeholder="NIS:">
    <div class="user_found_container">
      <span class="user_found_title">Usuário encontrado com sucesso</span>
      <div>
        <div>
          <span>Nome:</span>
          <span id="user_found_name"></span>
        </div>
        <div>
          <span>Nis:</span>
          <span id="user_found_nis"></span>
        </div>
      </div>
    </div>
    <div class="user_not_found_container">
      <span class="user_not_found_title"></span>
    </div>
    <button onclick="searchUser(event)" type="button">
      Buscar
    </button>
  </form>
</main>

<script>
  const serverURL = "http://localhost:8081/";

  window.onload = function() {
    document.getElementById("createUserForm").onsubmit = function(e) {
      e.preventDefault();
      createNewUser();
    };

    document.getElementById("searchUserForm").onsubmit = function(e) {
      e.preventDefault();
      searchUser();
    };
  };

  function createNewUser() {
    const xhr = new XMLHttpRequest();
    const name = document.getElementById('name').value;

    xhr.open("POST", serverURL, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        console.log(xhr.responseText);
        const json = xhr.responseText ? JSON.parse(xhr.responseText) : {};
        const userCreatedContainer = document.querySelector('.user_created_container');
        const userCreatedName = document.getElementById('user_created_name');
        const userCreatedNis = document.getElementById('user_created_nis');
        const errorContainer = document.querySelector('.error_container');
        const errorTitle = document.querySelector('.error_title');
        if (xhr.status === 200) {
          errorContainer.style.display = 'none';
          userCreatedContainer.style.display = 'flex';
          userCreatedName.textContent = json.name;
          userCreatedNis.textContent = json.nis;
        } else if (xhr.status === 400) {
          userCreatedContainer.style.display = 'none';
          errorContainer.style.display = 'block';
          errorTitle.textContent = json.error;
        }
      }
    };

    xhr.send(name);
  }

  function searchUser() {
    const xhr = new XMLHttpRequest();
    const nis = document.getElementById('nis').value;
    const url = `${serverURL}?nis=${encodeURIComponent(nis)}`;

    xhr.open("GET", url, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        const json = xhr.responseText ? JSON.parse(xhr.responseText) : {};
        const userFoundContainer = document.querySelector('.user_found_container');
        const userFoundName = document.getElementById('user_found_name');
        const userFoundNis = document.getElementById('user_found_nis');
        const userNotFoundContainer = document.querySelector('.user_not_found_container');
        const userNotFoundTitle = document.querySelector('.user_not_found_title');

        if (xhr.status === 404 || xhr.status === 400) {
          userFoundContainer.style.display = 'none';
          userNotFoundContainer.style.display = 'flex';
          userNotFoundTitle.textContent = json.error;
        } else if (xhr.status === 200) {
          userFoundContainer.style.display = 'flex';
          userFoundName.textContent = json.name;
          userFoundNis.textContent = json.nis;
          userNotFoundContainer.style.display = 'none';
        }
      }
    }

    xhr.send();
  }

  function renderCreateUser() {
    const createUserForm = document.getElementById('createUserForm');
    const searchUserForm = document.getElementById('searchUserForm');
    const createUserButton = document.getElementById('createUserButton');
    const searchUserButton = document.getElementById('searchUserButton');
    createUserButton.style.backgroundColor = '#1D90F5';
    createUserForm.style.display = 'flex';
    searchUserButton.style.backgroundColor = '#3D404B';
    searchUserForm.style.display = 'none';
  }

  function renderSearchUser() {
    const createUserForm = document.getElementById('createUserForm');
    const searchUserForm = document.getElementById('searchUserForm');
    const createUserButton = document.getElementById('createUserButton');
    const searchUserButton = document.getElementById('searchUserButton');
    createUserButton.style.backgroundColor = '#3D404B';
    createUserForm.style.display = 'none';
    searchUserButton.style.backgroundColor = '#1D90F5';
    searchUserForm.style.display = 'flex';
  }
</script>
# Gerenciador de Livros

Aplicação Full Stack (Laravel + Vue.js) containerizada com Docker.

## 🚀 Como Rodar a Aplicação

Siga os passos abaixo para executar o projeto em seu ambiente local.

### Pré-requisitos
* **Docker** e **Docker Compose** instalados e em execução.
* **WSL2** (recomendado se estiver usando Windows).

### 1. Instalação e Ambiente

Clone o repositório definindo o nome da pasta e entre nela:
```bash
git clone [https://github.com/lucasotardin/teste-dev.git](https://github.com/lucasotardin/teste-dev.git) laravel-livros-final
cd laravel-livros-final
```

Suba os containers utilizando o Laravel Sail (na primeira execução, o Docker baixará as imagens necessárias automaticamente):
```bash
./vendor/bin/sail up -d
```

### 2. Banco de Dados

Execute as migrações para criar a estrutura do banco de dados e o *seeder* para popular com dados iniciais de teste:
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

### 3. Frontend (Vue.js)

Instale as dependências do Node.js e inicie o servidor de desenvolvimento para compilar os ativos em tempo real:
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### 4. Acessando a Aplicação

Abra o seu navegador e acesse a URL principal:
* http://localhost

---
Desenvolvido por **Lucas Oliveira Tardin**
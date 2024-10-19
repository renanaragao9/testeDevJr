Instruções de Instalação
1. Clonando o Projeto
Primeiramente, faça o clone do projeto utilizando o comando:


git clone <url-do-repositório>
2. Abrindo o Projeto
Após clonar o projeto, abra a pasta raiz no Visual Studio Code (VSCode) ou em sua IDE de preferência.

3. Configuração do Backend
Navegue até a pasta do backend:
cd backend/
Dentro da pasta backend, instale as dependências do Laravel:
composer install
Em seguida, gere a chave da aplicação Laravel:
php artisan key:generate

Com as configurações básicas prontas, edite o arquivo .env e configure a estrutura do banco de dados.

Após configurar o banco de dados, execute as migrations para criar as tabelas no banco:
php artisan migrate

Crie o link simbólico para armazenar as imagens:
php artisan storage:link

(Opcional) Populando o Banco de Dados
Para popular as tabelas do banco de dados utilizando seeders, use o comando:

php artisan db:seed

Por fim, inicie o servidor do backend:
php artisan serve

4. Configuração do Frontend
Navegue até a pasta do frontend:
cd frontend/

Dentro da pasta frontend, instale as dependências do Vue.js:

npm install
Depois, execute:
npm run dev

Nota: Os comandos php artisan serve e npm run dev devem ser executados em terminais separados. Recomenda-se usar três terminais, dois para iniciar os servidores e um para instalar as dependências.

5. Configuração das URLs no Frontend
Verifique os arquivos .env na pasta frontend e ajuste as URLs caso a porta padrão (8000) seja diferente. As URLs estão localizadas nos seguintes caminhos:
Frontend URL base: frontend/.env

Rota base para o sistema: src/axios.js

Configuração adicional da URL:
src/components/Products/ProductsList.vue
src/Categories/CategoriesList.vue

Após todas as configurações, o sistema deve funcionar normalmente.

Rota para Testes
Criei uma rota para testar as APIs do servidor:
http://localhost:5173/aBcDeFgHiJkLmNoPqRsTuVwXyZ

Descrição das Funcionalidades Implementadas:

CRUD Padrão: Inserção, atualização, visualização e exclusão de dados.
Dashboard: Exibe estatísticas de produtos e categorias, permitindo download em PDF.
Gerenciamento de Produtos e Categorias: Criação, atualização e listagem com opções de download de imagens.
Middlewares: Proteção contra ataques em massa e bloqueio de IP em caso de tentativas de login falhas.
Possíveis Melhorias Futuras
Controle de estoque dinâmico.
Utilização de cookies para aumentar a segurança.
Implementação de middlewares de segurança.
Testes automatizados para simular ataques.
Tecnologias Utilizadas
Laravel 9: Framework estável e robusto.
Vue 3: Versão atualizada para desenvolvimento frontend.
Bootstrap: Utilizado para estilização CSS.
Tradução Laravel pt-BR: Facilita mensagens de erro e sucesso no sistema.

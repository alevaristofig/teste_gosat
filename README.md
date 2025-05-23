É preciso ter o docker e o git instaldo.

Clonar os projetos do repositorios.
Executar o comando docker-compose up -d --build dentro da pasta financeiro_credito.
Obs: Se mudar os nomes e caminhos das pastas será preciso altera os mesmos no arquivo docker-compose.yml

Para executar localmente, sem o docker, é preciso ter o php, composer, node, e o banco de dados mariadb instalados na máquina.

Baixar os projetos do git, entrar no financeiro_credito e executar o comando composer install, depois os comandos 
php artisan key:generate e php artisan migrate.
Entrar no projeto financeiro_credito_front e executar o comando npm install.

Urls git
https://github.com/alevaristofig/teste_gosat.git
https://github.com/alevaristofig/teste_gosat_frontend.git

Obs: subi a aplicação back-end para uma instancia do aws, as rotas estão no postman, não fiz para o frontend,
estava pedindo para gerar um certificado ssl para funcionar no https.

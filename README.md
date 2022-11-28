#SISTEMA DE GERAÇÃO DE LEADS PARA FEIRA DAS PROFISSÕES ATRAVÉS DA GAMIFICAÇÃO 


## Build Setup

```bash
# Rodar o comando para subir os containers
$ docker compose up -d --build

# Acessar o workspace no docker
$ docker compose exec app sh

# Rodar o comando o passport
$ php artisan passport:install
$ npm run start

# Rodar as migrations
$ php artisan migrate
```

#SISTEMA DE GERAÇÃO DE LEADS PARA FEIRA DAS PROFISSÕES ATRAVÉS DA GAMIFICAÇÃO 


## Build Setup

```bash
# Rodar o comando para subir os containers
$ docker compose up -d --build

# Acessar o workspace no docker
$ docker compose exec app sh

# Instalar as dependências do laravel 
$ composer install

# Gerar a chave de criptografia do laravel
$ php artisan key:generate

# Rodar o comando do passport
$ php artisan passport:install

# Rodar as migrations
$ php artisan migrate
```

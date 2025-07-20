# Personal Record PHP


## Setup inicial

Antes de realizar qualquer ação, realizar o setup inicial do projeto

```sh
$ docker compose up --build
$ docker exec -it personal_record_php composer install
```

Para inserir os dados iniciais apos subir o container, execute o comando abaixo:
```sh
$ cat seed.sql | docker exec -i personal_record_db mysql -u root -proot db
```

## Execução local

Para executar local caso ja tenha instalado as dependências, rodar o seguinte comando abaixo:

```sh
$ docker compose up
```

## Execução de testes

Para executar os testes, rodar o seguinte comando abaixo:

```sh
$ docker exec -it personal_record_php ./vendor/bin/phpunit
```
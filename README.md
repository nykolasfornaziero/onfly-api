# Teste Onfly Backend

Este é o CRUD para o teste técnico da Onfly construído com PHP, Laravel, MySQL a partir de containeres Docker. A API gerência autenticação de usuários e gestão de solicitações de viagem;

## Funcionalidades

- **Autenticação de Usuários**: Autenticação de usuários via JWT, Roles e Permissions.
- **Gerenciamento de Solicitação de Viagens**: Cadastra e Alterar uma solicitação de viagem.

## Tecnologias
![PHP Badge](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel Badge](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2CA5E0?style=for-the-badge&logo=docker&logoColor=white)
![Insomnia Version](https://img.shields.io/badge/Insomnia%20Version-v1.0.0-blue)

## Primeiros Passos

### Pré-requisitos

- PHP (v8.2 ou superior)
- Composer
- Docker
- Docker composer (Opcional)
- Insomnia 

### Instalação

1. Clone o repositório:

```bash
git@github.com:nykolasfornaziero/onfly-api.git
cd onfly-api
```
Caso queira utilizar o projeto localmente na máquina precisará seguir os passos abaixo:
1. Instale as dependências:
```bash
composer install
```

2. Rode as migrations:
```bash
php artisan migrate
php artisan db:seed
```

3. Configure as variáveis de ambiente com o env example. Rode o comando para gerar chave aleatoria de criptografia
```bash
php artisan key:generate
```

O projeto possui um docker composer. Pode ser executado através dele para utilizar as configurações definidas.
Utilizanod o Docker:
1. Suba o Container Docker já ajustado nos arquivos:
```bash
docker-compose up --build
```
2. Verifique se os containeres Docker (aplicação e DB) está configurados corretamente
```bash
docker exec -it laravel_app /bin/bash
```
3. Acesse o container Docker para executar os comandos
```bash
docker exec -it laravel_app /bin/bash
```
4. Execute os commandos do Laravel:
```bash
php artisan migrate
php artisan db:seed
```

### Operações
Consulte a collection deixado na raiz do projeto que foi utilizado na ferramenta Insomnia e podem ser acessadas no link abaixo documentado:
https://thunder-clipper-cd8.notion.site/Documenta-o-18886c8755608027a76ff0b6963d3a9e?pvs=4

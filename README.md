# php-underconstruction
Páginas "Em construção" para novos sites
## Descrição
Este projeto foi feito sobre a Slim Framework 3.
A intenção é ser uma página muito simples de "Em construção", mas com a 
funcionalidade dos visitantes poderem deixar o seu endereço de email e 
serem informados quando o site estiver no ar.
Também, é importante criar um ponto de contato. Para isso, um formulário de contato
também foi incluído.
Você pode tirar vantagem deste projeto para muitos projetos simples.

## Instalação
Primeiro, atualize o composer para que os códigos distribuídos de vendor sejam carregados:
```bash
composer update
```

## comandos artesao
Artesao é como o artisan, no Laravel.
Você pode criar controllers e tabelas.

Para ver a lista de comandos disponíveis, escreva:
```bash
php artesao list
```

## Criando Controllers
Para criar um controller, simplesmente execute o comando ```create:controller```:
```bash
php artesao create:controller Admin
```
Este comando irá criar um ficheiro AdminController sobre ```app/Controllers/``` e também irá criar uma
entrada nos controladores da app, em ```src/config/controllers.php```.

Para apagar um Controller, faça:
```bash
php artesao drop:controller Admin
```

## Criando Tabelas
Para criar uma tabela, simplesmente execute o comando ```create:table```:
```bash
php artesao create:table Users
```
Este comando irá criar um ficheiro UsersTable.php sobre a pasta ```app/Db/```.
Altere este ficheiro para ficar de acordo com as colunas da sua tabela, e com 
os dados que queira popular para dentro da tabela.

Isto irá criar também o UsersModel.php dentro da pasta ```app/Models/```.
Esta é a classe que você utilizará no seu sistema.

Você pode popular dados para a tabela através da linha de comandos.
Simplesmente altere a variável ```$this->seed``` no ficheiro UsersTable.php.

Para realizar alterações na base de dados, faça um:
```bash
php artesao make:table Users
```
Este comando irá criar a tabela na base de dados.

Para popular os dados na tabela, faça um:
```bash
php artesao seed:table Users
```

Ou, para apagar a tabela:
```bash
php artesao drop:table Users
```

O comando drop apenas apaga a tabela da base de dados, permitindo que você
crie novamente a tabela (make:table) e insira novamente dados (seed:table).
Se você quiser eliminar também as referências da tabela do seu sistema, 
utilize:
```bash
php artesao remove:table Users
```

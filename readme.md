# Laraform - Clone do Google Forms em Laravel

Este é um aplicativo Laravel que é um "clone" do Google Forms. Ele inclui a maioria dos recursos do Google Forms, como criação de formulários, compartilhamento de formulários, adição de colaboradores, visualização de análises de respostas de formulários por usuários e muito mais. Também possui o recurso onde o criador de um formulário pode escolher definir o formulário como aberto ou fechado para respostas dos usuários.

### Tecnologias Utilizadas

* [Laravel 10.x](https://laravel.com)
* [Bootstrap 3](https://getbootstrap.com/docs/3.3/)
* Banco de dados [MySQL](https://www.mysql.com/)
* [Google Chart](https://developers.google.com/chart)

### Instruções de Instalação

1. Execute `git clone https://github.com/seu-usuario/laraform.git` para clonar o repositório
2. Execute `composer install` na pasta raiz do projeto
3. Na pasta raiz do projeto, execute `cp .env.example .env`
4. Execute `php artisan key:generate`
5. Configure o arquivo `.env` e os arquivos na pasta config de acordo com suas necessidades
6. Execute `php artisan migrate` para configurar as tabelas do banco de dados MySQL após o banco de dados ter sido criado para a aplicação e configurado no arquivo `.env`.
7. Configure o supervisor usando as instruções do Laravel ([https://laravel.com/docs/10.x/queues#supervisor-configuration](https://laravel.com/docs/10.x/queues#supervisor-configuration))
8. Adicione `worker.log` à pasta `/storage/logs`
9. Adicione a seguinte entrada cron ao seu servidor: `* * * * * php /caminho-para-seu-app-laraform/artisan schedule:run >> /dev/null 2>&1`

### Recursos

* Criação de formulários com vários tipos de campos:
  * Resposta curta
  * Resposta longa
  * Múltipla escolha
  * Caixas de seleção
  * Lista suspensa
  * Escala linear
  * Data
  * Hora
* Compartilhamento de formulários via link ou e-mail
* Adição de colaboradores aos formulários
* Definição de disponibilidade de formulários (datas de início e término)
* Visualização e exportação de respostas
* Análise de respostas com gráficos

### Atualizações na Versão 10

* Atualização do Laravel 5.7 para Laravel 10.x
* Atualização do PHP para versão 8.1+
* Tradução completa do sistema para português do Brasil
* Atualização das dependências para versões compatíveis
* Modernização da estrutura de código seguindo as melhores práticas do Laravel 10

### Licença

Este projeto está licenciado sob a [Licença MIT](https://opensource.org/licenses/MIT).

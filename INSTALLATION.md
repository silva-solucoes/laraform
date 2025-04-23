# Guia de Instalação e Atualização do Laraform

## Requisitos do Sistema

- PHP 8.1 ou superior
- Composer
- MySQL ou outro banco de dados compatível
- Extensões PHP necessárias: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Instalação

1. Clone o repositório:
   ```
   git clone https://github.com/seu-usuario/laraform.git
   ```

2. Entre no diretório do projeto:
   ```
   cd laraform
   ```

3. Instale as dependências:
   ```
   composer install
   ```

4. Copie o arquivo de ambiente:
   ```
   cp .env.example .env
   ```

5. Gere a chave da aplicação:
   ```
   php artisan key:generate
   ```

6. Configure o banco de dados no arquivo `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laraform
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

7. Execute as migrações:
   ```
   php artisan migrate
   ```

8. Configure o supervisor para filas (opcional, mas recomendado para envio de emails):
   - Siga as instruções em: https://laravel.com/docs/10.x/queues#supervisor-configuration
   - Adicione `worker.log` à pasta `/storage/logs`

9. Adicione a seguinte entrada cron ao seu servidor para tarefas agendadas:
   ```
   * * * * * php /caminho-para-seu-app-laraform/artisan schedule:run >> /dev/null 2>&1
   ```

10. Inicie o servidor de desenvolvimento:
    ```
    php artisan serve
    ```

## Notas da Atualização para Laravel 10

### Principais Mudanças

1. **Atualização de Dependências**:
   - Laravel Framework atualizado para versão 10.x
   - PHP atualizado para requisito mínimo 8.1+
   - Pacotes de desenvolvimento atualizados para versões compatíveis

2. **Mudanças Estruturais**:
   - Modelos movidos para namespace `App\Models`
   - Tipagem de retorno adicionada em métodos
   - Facades utilizadas em vez de helpers globais

3. **Tradução para Português**:
   - Sistema completamente traduzido para português do Brasil
   - Arquivos de tradução adicionados em `resources/lang/pt-BR`
   - Idioma padrão configurado como português do Brasil

### Possíveis Problemas

- Se encontrar erros relacionados a classes não encontradas, verifique se todos os namespaces estão corretos
- Certifique-se de que o PHP 8.1+ está instalado e configurado corretamente
- Em caso de problemas com as migrações, tente executar `php artisan migrate:fresh`

## Funcionalidades Disponíveis

- Criação de formulários com vários tipos de campos
- Compartilhamento de formulários via link ou e-mail
- Adição de colaboradores aos formulários
- Definição de disponibilidade de formulários
- Visualização e exportação de respostas
- Análise de respostas com gráficos

## Suporte

Para problemas ou dúvidas, abra uma issue no repositório do GitHub.

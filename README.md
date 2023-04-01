Este projeto é uma aplicação que utiliza o banco de dados Firebird 4.0 para armazenar informações e realizar consultas. A seguir, você encontrará instruções para configurar o ambiente e começar a utilizar o sistema.

É NECESSÁRIO TER INSTALADO E CONFIGURADO O XAMPP

Configuração do Ambiente
Para utilizar o sistema, é necessário instalar os seguintes componentes:

Firebird 4.0
IBExpert ou Flamerobin
ODBC
Composer
Firebird 4.0
Após o download do pacote psel-shinier-2023-20230327T185606Z-001.zip, é necessário instalar o Firebird 4.0.

IBExpert ou Flamerobin
Para acessar o banco de dados, é necessário utilizar o IBExpert ou o Flamerobin. No Flamerobin, siga o passo a passo a seguir:

Acesse a opção Database > Restore backup into new Database.
Escolha um nome de exibição e selecione o local onde salvar o arquivo.
Insira o nome de usuário e senha (SYSDBA, masterkey) e defina o Charset como WIN1252.
Clique em Save.
Selecione o arquivo .FBK para restaurar os dados e clique em Start Backup.
Aguarde a mensagem "finished".
ODBC
Para vincular o banco de dados com o Excel, é necessário configurar o ODBC. Siga o passo a passo a seguir:

Instale o ODBC pelo site do Firebird.
Abra o executar com WIN + R e digite "obdcad32".
Clique em "Adicionar" e selecione o "Firebird/InterBase".
Preencha os campos necessários e selecione o fbclient.dll em Client.
Defina o Charset como WIN1252.
No campo Database, coloque o IP de onde seu banco de dados está iniciado.
Clique em Test Connection e, se as informações estiverem corretas, clique em OK.
Para vincular no Excel, abra o Excel e selecione Dados > Obter dados > De outras fontes > ODBC > Selecionar o que acabamos de criar > Escolher as tabelas que quiser e "Continuar".

Composer
Instale o Composer no seu sistema operacional e adicione a biblioteca PhpSpreadsheet ao projeto.

Abra o terminal do VS Code na raiz do projeto e execute o comando composer install.

Utilização do Sistema
Para utilizar o sistema, siga os passos a seguir:
É necessário preencher no arquivo de nome "conexao_modelo.php" os campos: host, database, username e password. Após preenchido, alterar o nome do arquivo para "conexao.php"

É necessário preencher também, no arquivo de nome "chaves_requisicao_modelo.php" os campos: email e senha. Após preenchido, alterar o nome do arquivo para "chaves_requisicao.php".

Coloque a pasta do projeto no diretório: "C:/xampp/htdocs".
Execute o servidor apache do xampp
Abra o arquivo "localhost/psel-shinier-2023/consulta.php"

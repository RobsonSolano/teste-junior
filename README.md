# Projeto Desafio Nano, 2º etapa processo seletivo.

<img src="https://upload.wikimedia.org/wikipedia/commons/d/db/Challenge_Logo.png" width="80px"/>

O presente projeto foi desenvolvido utilizando: 

<ul type="circle">
  <li>HTML5</li> 
  <li>CSS3</li>
  <li>PHP7</li>
  <li>PDO</li>
  <li>JavaScript</li>
  <li>Composer</li>
  <li>Bootstrap 4</li>
  <li>chart.js</li>
</ul>


<h4>Instruções do projeto</h4

1 - Crie um banco de dados com o nome: db_challenge<br>

2 - Importe o arquivo tb_challenge.sql que está na pasta sql<br>

3 - <b>Para rodar a aplicação</b><br>
3.1 - Altere as informações do arquivo Connection.php com as do seu servidor do Banco de Dados
3.2 - Abra o projeto usando seu apache apontando para pasta raiz do projeto, onde está o arquivo index.php<br>
3.3 - Se estiver dentro do htdocs do xampp, utilize o caminho localhost:ProjetoDesafio<br>
3.4 - Caso utilize variaveis de ambiente do sistema, basta apontar a raiz do diretório, como: Localhost:8089<br>
3.5 - Ao executar a aplicação, utilize o login: admin@desafio.com e a senha: admin para acessar a aplicação<br>

4 - <b>Página inícial</b><br>
4.1 - Na página inícial, esta sendo exibido a quantidade de funcionário, quantidade de movimentações do tipo Entrada e também do tipo Saída e um gráfico estático<br>
4.2 - Ao clicar no menu, você terá as opções: Nome do administrador (Pego através do login), Funcionários e Movimentações<br>
4.3 - Ao clicar em Nome do administrador, você pode acessar o perfil para ver as informações do administrador ou Sair (Logout)<br>

5 - <b>Funcionários</b><br>
5.1 - Ao clicar em Funcionários, será exibido todos os funcionários cadastrados do banco<br>
5.2 - Para cada funcionário, existe 3 botões, Editar, Extrato, Excluir<br>
5.3 - Ao clicar em Editar, será exibido as informações do funcionário selecionado, podendo alterar as informações e salvar<br>
5.4 - Clicando em Extrado, será exibido todas as movimentações correspondente ao funcionário selecionado<br>
5.5 - Nesta página você pode cadastrar uma nova movimentação para esse funcionário, e ao cadastrar, o valor dessa movimentação causará impacto no saldo deste funcionário<br>
5.6 - Clicando em excluir, será exibido um alerta pedindo uma confirmação de que realmente quer excluir o funcionário<br>
5.7 - Na parte superior extá sendo exibido um filtro, para caso queira procurar um usuário específico, utilizando nome do funcionário ou data de criação<br>

6 - <b>Movimentações</b><br>
6.1 - No menu, ao clicar em movimentações, será exibido uma lista com todas as movimentações encontradas no banco<br>
6.2 - Nesta página, na parte superior, estará sendo exibido uma opção de filtro, que pode ser feito por filtro, pelo nome do funcionário ou pela data de criação.

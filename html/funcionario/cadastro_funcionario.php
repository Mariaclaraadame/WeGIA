<?php

include_once("conexao.php");
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: ../index.php");
}

$config_path = "config.php";
if (file_exists($config_path)) {
  require_once($config_path);
} else {
  while (true) {
    $config_path = "../" . $config_path;
    if (file_exists($config_path)) break;
  }
  require_once($config_path);
}
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$situacao = $mysqli->query("SELECT * FROM situacao");
$cargo = $mysqli->query("SELECT * FROM cargo");
$conexao = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$id_pessoa = $_SESSION['id_pessoa'];
$resultado = mysqli_query($conexao, "SELECT * FROM funcionario WHERE id_pessoa=$id_pessoa");
if (!is_null($resultado)) {
  $id_cargo = mysqli_fetch_array($resultado);
  if (!is_null($id_cargo)) {
    $id_cargo = $id_cargo['id_cargo'];
  }
  $resultado = mysqli_query($conexao, "SELECT * FROM permissao WHERE id_cargo=$id_cargo and id_recurso=11");
  if (!is_bool($resultado) and mysqli_num_rows($resultado)) {
    $permissao = mysqli_fetch_array($resultado);
    if ($permissao['id_acao'] < 3) {
      $msg = "Você não tem as permissões necessárias para essa página.";
      header("Location: ../home.php?msg_c=$msg");
    }
    $permissao = $permissao['id_acao'];
  } else {
    $permissao = 1;
    $msg = "Você não tem as permissões necessárias para essa página.";
    header("Location: ../home.php?msg_c=$msg");
  }
} else {
  $permissao = 1;
  $msg = "Você não tem as permissões necessárias para essa página.";
  header("Location: ../home.php?msg_c=$msg");
}

require_once ROOT . "/controle/FuncionarioControle.php";
$listaCPF = new FuncionarioControle;
$listaCPF->listarCpf();

require_once ROOT . "/controle/AtendidoControle.php";
$listaCPF2 = new AtendidoControle;
$listaCPF2->listarCpf();
$cpf = $_GET['cpf'];
$funcionario = new FuncionarioDAO;
$informacoesFunc = $funcionario->listarPessoaExistente($cpf);


// Inclui display de Campos
require_once "../personalizacao_display.php";

?>
<!DOCTYPE html>
<html class="fixed">

<head>
  <!-- Basic -->
  <meta charset="UTF-8">
  <title>Cadastro de Funcionário</title>
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

  <!-- Web Fonts  -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

  <!-- Vendor CSS -->
  <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.css" />
  <link rel="stylesheet" href="../../assets/vendor/font-awesome/css/font-awesome.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="stylesheet" href="../../assets/vendor/magnific-popup/magnific-popup.css" />
  <link rel="stylesheet" href="../../assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
<link rel="icon" href="<?php display_campo("Logo",'file');?>" type="image/x-icon">

  <!-- Theme CSS -->
  <link rel="stylesheet" href="../../assets/stylesheets/theme.css" />

  <!-- Skin CSS -->
  <link rel="stylesheet" href="../../assets/stylesheets/skins/default.css" />

  <!-- Theme Custom CSS -->
  <link rel="stylesheet" href="../../assets/stylesheets/theme-custom.css">


  <!-- <script>

    console.log("oi");
    $(function(){

      
      
      var funcionario = <?php echo $informacoesFunc ?>; 
      console.log(funcionario);
      console.log("oi");
      $.each(funcionario, function(i, item) {
        
        $("#cpf").val(item.cpf).prop('disabled', true);
        }
    

  </script> -->
</head>

<body>
  <!-- start: header -->
  <div id="header"></div>
  <!-- end: header -->
  <div class="inner-wrapper">
    <!-- start: sidebar -->
    <aside id="sidebar-left" class="sidebar-left menuu"></aside>

    <section role="main" class="content-body">
      <header class="page-header">
        <h2>Cadastro</h2>
        <div class="right-wrapper pull-right">
          <ol class="breadcrumbs">
            <li>
              <a href="./home.php">
                <i class="fa fa-home"></i>
              </a>
            </li>
            <li><span>Cadastros</span></li>
            <li><span>Funcionário</span></li>
          </ol>
          <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
      </header>
      <!-- start: page -->
      <div class="row" id="formulario">
        <div class="col-md-4 col-lg-3">
          <section class="panel">
            <div class="panel-body">
              <div class="thumb-info mb-md">
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  if (isset($_FILES['imgperfil'])) {
                    $image = file_get_contents($_FILES['imgperfil']['tmp_name']);
                    $_SESSION['imagem'] = $image;
                    echo '<img src="data:image/gif;base64,' . base64_encode($image) . '" class="rounded img-responsive" alt="John Doe">';
                  }
                } else {
                ?>
                  <img src="../../img/semfoto.png" class="rounded img-responsive" alt="John Doe">
                <?php
                }
                ?>
                <i class="fas fa-camera-retro btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"></i>
                <div class="container">
                  <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Adicionar uma Foto</h4>
                        </div>
                        <div class="modal-body">
                          <form action="#" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="imgperfil">Carregue uma imagem de perfil:</label>
                              <div class="col-md-8">
                                <input type="file" name="imgperfil" size="60" id="imgform" class="form-control">
                              </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <input type="submit" id="formsubmit" value="Ok">
                        </div>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="widget-toggle-expand mb-md">
                <div class="widget-header">
                  <div class="widget-content-expanded">
                    <ul class="simple-todo-list">
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="col-md-8 col-lg-8">
          <div class="tabs">
            <ul class="nav nav-tabs tabs-primary">
              <li class="active">
                <a href="#overview" data-toggle="tab">Cadastro de Funcionário</a>
              </li>
            </ul>
            <div class="tab-content">
              <div id="overview" class="tab-pane active">
                <form class="form-horizontal" method="GET" action="../../controle/control.php">
                  <h4 class="mb-xlg">Informações Pessoais</h4>
                  <h5 class="obrig">Campos Obrigatórios(*)</h5>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="profileFirstName">Nome<sup class="obrig">*</sup></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="nome" id="profileFirstName" id="nome" onkeypress="return Onlychars(event)" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Sobrenome<sup class="obrig">*</sup></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" name="sobrenome" id="sobrenome" onkeypress="return Onlychars(event)" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="profileLastName">Sexo<sup class="obrig">*</sup></label>
                    <div class="col-md-8">
                      <label><input type="radio" name="gender" id="radio" id="M" value="m" style="margin-top: 10px; margin-left: 15px;" onclick="return exibir_reservista()" required><i class="fa fa-male" style="font-size: 20px;"></i></label>
                      <label><input type="radio" name="gender" id="radio" id="F" value="f" style="margin-top: 10px; margin-left: 15px;" onclick="return esconder_reservista()"><i class="fa fa-female" style="font-size: 20px;"></i> </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="telefone">Telefone<sup class="obrig">*</sup></label>
                    <div class="col-md-8">
                      <input type="text" class="form-control" maxlength="14" minlength="14" name="telefone" id="telefone" id="telefone" placeholder="Ex: (22)99999-9999" onkeypress="return Onlynumbers(event)" onkeyup="mascara('(##)#####-####',this,event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="profileCompany">Nascimento<sup class="obrig">*</sup></label>
                    <div class="col-md-8">
                      <input type="date" placeholder="dd/mm/aaaa" maxlength="10" class="form-control" name="nascimento" id="nascimento" max=<?php echo date('Y-m-d'); ?> required>
                    </div>
                  </div>
                  <hr class="dotted short">
                  <h4 class="mb-xlg doch4">Documentação</h4>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="profileCompany">Número do RG<sup class="obrig">*</sup></label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="rg" id="rg" onkeypress="return Onlynumbers(event)" placeholder="Ex: 22.222.222-2" onkeyup="mascara('##.###.###-#',this,event)" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="profileCompany">Órgão Emissor<sup class="obrig">*</sup></label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="orgao_emissor" id="profileCompany" id="orgao_emissor" onkeypress="return Onlychars(event)" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="profileCompany">Data de expedição<sup class="obrig">*</sup></label>
                    <div class="col-md-6">
                      <input type="date" class="form-control" maxlength="10" placeholder="dd/mm/aaaa" id="profileCompany" name="data_expedicao" id="data_expedicao" max=<?php echo date('Y-m-d'); ?> required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="cpf">Número do CPF<sup class="obrig">*</sup></label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" id="cpf" id="cpf" name="cpf" placeholder="Ex: 222.222.222-22" maxlength="14" value= <?php echo $cpf ?> disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="profileCompany"></label>
                    <div class="col-md-6">
                      <p id="cpfInvalido" style="display: none; color: #b30000">CPF INVÁLIDO!</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="profileCompany">Data de Admissão<sup class="obrig">*</sup></label>
                    <div class="col-md-8">
                      <input type="date" placeholder="dd/mm/aaaa" maxlength="10" class="form-control" name="data_admissao" id="profileCompany" id="data_admissao" max=<?php echo date('Y-m-d'); ?> required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="inputSuccess">Situação<sup class="obrig">*</sup></label>
                    <a onclick="adicionar_situacao()"><i class="fas fa-plus w3-xlarge" style="margin-top: 0.75vw"></i></a>
                    <div class="col-md-6">
                      <select class="form-control input-lg mb-md" name="situacao" id="situacao" required>
                        <option selected disabled>Selecionar</option>
                        <?php
                        while ($row = $situacao->fetch_array(MYSQLI_NUM)) {
                          echo "<option value=" . $row[0] . ">" . $row[1] . "</option>";
                        }                            ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label" for="inputSuccess">Cargo<sup class="obrig">*</sup></label>
                    <a onclick="adicionar_cargo()"><i class="fas fa-plus w3-xlarge" style="margin-top: 0.75vw"></i></a>
                    <div class="col-md-6">
                      <select class="form-control input-lg mb-md" name="cargo" id="cargo" required>
                        <option selected disabled>Selecionar</option>
                        <?php
                        while ($row = $cargo->fetch_array(MYSQLI_NUM)) {
                          echo "<option value=" . $row[0] . ">" . $row[1] . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label">Escala<sup class="obrig">*</sup></label>
                    <div class="col-md-6">
                      <select class="form-control input-lg mb-md" name="escala" id="escala_input" required>
                        <option selected disabled value="">Selecionar</option>
                        <?php
                        $pdo = Conexao::connect();
                        $escala = $pdo->query("SELECT * FROM escala_quadro_horario;")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($escala as $key => $value) {
                          echo ("<option value=" . $value["id_escala"] . ">" . $value["descricao"] . "</option>");
                        }
                        ?>
                      </select>
                    </div>
                    <a href="../../quadro_horario/adicionar_escala.php"><i class="fas fa-plus w3-xlarge"></i></a>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Tipo<sup class="obrig">*</sup></label>
                    <div class="col-md-6">
                      <select class="form-control input-lg mb-md" name="tipoCargaHoraria" id="tipoCargaHoraria_input" required>
                        <option selected disabled value="">Selecionar</option>
                        <?php
                        $pdo = Conexao::connect();
                        $tipo = $pdo->query("SELECT * FROM tipo_quadro_horario;")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($tipo as $key => $value) {
                          echo ("<option value=" . $value["id_tipo"] . ">" . $value["descricao"] . "</option>");
                        }
                        ?>
                      </select>
                    </div>
                    <a href="../../quadro_horario/adicionar_tipo_quadro_horario.php"><i class="fas fa-plus w3-xlarge"></i></a>
                  </div>
                  <div class="form-group" id="reservista1" style="display: none">
                    <label class="col-md-3 control-label">Número do certificado reservista</label>
                    <div class="col-md-6">
                      <input type="text" name="certificado_reservista_numero" class="form-control num_reservista">
                    </div>
                  </div>
                  <div class="form-group" id="reservista2" style="display: none">
                    <label class="col-md-3 control-label">Série do certificado reservista</label>
                    <div class="col-md-6">
                      <input type="text" name="certificado_reservista_serie" class="form-control serie_reservista">
                    </div>
                  </div>

                  <div class="panel-footer">
                    <div class="row">
                      <div class="col-md-9 col-md-offset-3">
                        <input type="hidden" name="nomeClasse" value="FuncionarioControle">
                        <input type="hidden" name="cpf" value="<?php  echo $cpf ?>">
                        <input type="hidden" name="metodo" value="incluir">
                        <input id="enviar" type="submit" class="btn btn-primary"  value="Salvar" onclick="validarFuncionario()">
                        <input type="reset" class="btn btn-default">
                      </div>
                    </div>
                  </div>
                </form>
              <!-- end: page -->
    </section>
  </div>
  </section>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

  <!-- JQuery Online -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <!-- JQuery Local -->
  <script src="../../assets/vendor/jquery/jquery.min.js"></script>
  <script src="https://requirejs.org/docs/release/2.3.6/r.js"></script>
  <style type="text/css">
    .btn span.fa-check {
      opacity: 0;
    }

    .btn.active span.fa-check {
      opacity: 1;
    }

    .obrig {
      color: rgb(255, 0, 0);
    }
  </style>
  <script type="text/javascript">
    function funcao1() {
      var send = $("#enviar");
      var cpfs = <?php echo $_SESSION['cpf_funcionario']; ?>;
      var cpf_funcionario = $("#cpf").val();
      var cpf_funcionario_correto = cpf_funcionario.replace(".", "");
      var cpf_funcionario_correto1 = cpf_funcionario_correto.replace(".", "");
      var cpf_funcionario_correto2 = cpf_funcionario_correto1.replace(".", "");
      var cpf_funcionario_correto3 = cpf_funcionario_correto2.replace("-", "");
      var apoio = 0;
      //var cpfs1 = <?php echo $_SESSION['cpf_interno']; ?>;
      $.each(cpfs, function(i, item) {
        if (item.cpf == cpf_funcionario_correto3) {
          alert("Cadastro não realizado! O CPF informado já está cadastrado no sistema");
          apoio = 1;
          send.attr('disabled', 'disabled');
        }
      });
      
      if (apoio == 0) {
        alert("Cadastrado com sucesso!");
      }
    }

    function validarFuncionario(){
      var btn = $("#enviar");
      /*var cpf_cadastrado = (<?php echo $_SESSION['cpf_funcionario']; ?>).concat(<?php echo $_SESSION['cpf_interno']; ?>);*/
      var cpf_cadastrado = (<?php echo $_SESSION['cpf_funcionario']; ?>);
      var cpf = (($("#cpf").val()).replaceAll(".", "")).replaceAll("-", "");
      console.log(this);
      $.each(cpf_cadastrado, function(i, item) {
        if (item.cpf == cpf) {
          alert("Cadastro não realizado! O CPF informado já está cadastrado no sistema");
          btn.attr('disabled', 'disabled');
          return false;
        }
      });
      alert("Cadastrado com sucesso!");
    }

    function numero_residencial() {

      if ($("#numResidencial").prop('checked')) {

        document.getElementById("numero_residencia").disabled = true;

      } else {

        document.getElementById("numero_residencia").disabled = false;

      }
    }

    function exibir_reservista() {

      $("#reservista1").show();
      $("#reservista2").show();
    }

    function esconder_reservista() {

      $('.num_reservista').val("");
      $('.serie_reservista').val("");

      $("#reservista1").hide();
      $("#reservista2").hide();
    }

    function limpa_formulário_cep() {
      //Limpa valores do formulário de cep.
      document.getElementById('rua').value = ("");
      document.getElementById('bairro').value = ("");
      document.getElementById('cidade').value = ("");
      document.getElementById('uf').value = ("");
      document.getElementById('ibge').value = ("");
    }

    function meu_callback(conteudo) {
      if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById('rua').value = (conteudo.logradouro);
        document.getElementById('bairro').value = (conteudo.bairro);
        document.getElementById('cidade').value = (conteudo.localidade);
        document.getElementById('uf').value = (conteudo.uf);
        document.getElementById('ibge').value = (conteudo.ibge);
      } //end if.
      else {
        //CEP não Encontrado.
        limpa_formulário_cep();
        alert("CEP não encontrado.");
      }
    }

    function pesquisacep(valor) {

      //Nova variável "cep" somente com dígitos.
      var cep = valor.replace(/\D/g, '');

      //Verifica se campo cep possui valor informado.
      if (cep != "") {

        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if (validacep.test(cep)) {

          //Preenche os campos com "..." enquanto consulta webservice.
          document.getElementById('rua').value = "...";
          document.getElementById('bairro').value = "...";
          document.getElementById('cidade').value = "...";
          document.getElementById('uf').value = "...";
          document.getElementById('ibge').value = "...";

          //Cria um elemento javascript.
          var script = document.createElement('script');

          //Sincroniza com o callback.
          script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

          //Insere script no documento e carrega o conteúdo.
          document.body.appendChild(script);

        } //end if.
        else {
          //cep é inválido.
          limpa_formulário_cep();
          alert("Formato de CEP inválido.");
        }
      } //end if.
      else {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
      }

    };

    function testaCPF(strCPF) { //strCPF é o cpf que será validado. Ele deve vir em formato string e sem nenhum tipo de pontuação.
      var strCPF = strCPF.replace(/[^\d]+/g, ''); // Limpa a string do CPF removendo espaços em branco e caracteres especiais.
      var Soma;
      var Resto;
      Soma = 0;
      if (strCPF == "00000000000") return false;

      for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
      Resto = (Soma * 10) % 11;

      if ((Resto == 10) || (Resto == 11)) Resto = 0;
      if (Resto != parseInt(strCPF.substring(9, 10))) return false;

      Soma = 0;
      for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
      Resto = (Soma * 10) % 11;

      if ((Resto == 10) || (Resto == 11)) Resto = 0;
      if (Resto != parseInt(strCPF.substring(10, 11))) return false;
      return true;
    }

    function validarCPF(strCPF) {

      if (!testaCPF(strCPF)) {
        $('#cpfInvalido').show();
        document.getElementById("enviar").disabled = true;

      } else {
        $('#cpfInvalido').hide();

        document.getElementById("enviar").disabled = false;
      }
    }

    function gerarSituacao() {
      url = '../../dao/exibir_situacao.php';
      $.ajax({
        data: '',
        type: "POST",
        url: url,
        async: true,
        success: function(response) {
          var situacoes = response;
          $('#situacao').empty();
          $('#situacao').append('<option selected disabled>Selecionar</option>');
          $.each(situacoes, function(i, item) {
            $('#situacao').append('<option value="' + item.id_situacao + '">' + item.situacoes + '</option>');
          });
        },
        dataType: 'json'
      });
    }

    function adicionar_situacao() {
      url = '../../dao/adicionar_situacao.php';
      var situacao = window.prompt("Cadastre uma Nova Situação:");
      if (!situacao) {
        return
      }
      situacao = situacao.trim();
      if (situacao == '') {
        return
      }

      data = 'situacao=' + situacao;

      console.log(data);
      $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(response) {
          gerarSituacao();
        },
        dataType: 'text'
      })
    }

    function gerarCargo() {
      url = '../../dao/exibir_cargo.php';
      $.ajax({
        data: '',
        type: "POST",
        url: url,
        success: function(response) {
          var cargo = response;
          $('#cargo').empty();
          $('#cargo').append('<option selected disabled>Selecionar</option>');
          $.each(cargo, function(i, item) {
            $('#cargo').append('<option value="' + item.id_cargo + '">' + item.cargo + '</option>');
          });
        },
        dataType: 'json'
      });
    }

    function adicionar_cargo() {
      url = '../../dao/adicionar_cargo.php';
      var cargo = window.prompt("Cadastre um Novo Cargo:");
      if (!cargo) {
        return
      }
      situacao = cargo.trim();
      if (cargo == '') {
        return
      }

      data = 'cargo=' + cargo;
      console.log(data);
      $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(response) {
          gerarCargo();
        },
        dataType: 'text'
      })
    }

    $(function() {
     
      $("#header").load("../header.php");
      $(".menuu").load("../menu.php");
    });
  </script>
  <!-- Head Libs -->
  <script src="../../assets/vendor/modernizr/modernizr.js"></script>

  <!-- javascript functions -->
  <script src="../../Functions/onlyNumbers.js"></script>
  <script src="../../Functions/onlyChars.js"></script>
  <script src="../../Functions/mascara.js"></script>
  <script src="../../Functions/lista.js"></script>
  <script language="JavaScript">
    var numValidos = "0123456789-()";
    var num1invalido = "78";
    var i;

    function validarTelefone() {
      //Verificando quantos dígitos existem no campo, para controlarmos o looping;
      digitos = document.form1.telefone.value.length;

      for (i = 0; i < digitos; i++) {
        if (numValidos.indexOf(document.form1.telefone.value.charAt(i), 0) == -1) {
          alert("Apenas números são permitidos no campo Telefone!");
          document.form1.telefone.select();
          return false;
        }
        if (i == 0) {
          if (num1invalido.indexOf(document.form1.telefone.value.charAt(i), 0) != -1) {
            alert("Número de telefone inválido!");
            document.form1.telefone.select();
            return false;
          }
        }
      }
    }
  </script>
  <!-- Vendor -->
  <script src="../../assets/vendor/jquery/jquery.js"></script>
  <script src="../../assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.js"></script>
  <script src="../../assets/vendor/nanoscroller/nanoscroller.js"></script>
  <script src="../../assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script src="../../assets/vendor/magnific-popup/magnific-popup.js"></script>
  <script src="../../assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
</body>

</html>
  

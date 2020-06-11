<?php

$config_path = "config.php";
if(file_exists($config_path)){
    require_once($config_path);
}else{
    while(true){
        $config_path = "../" . $config_path;
        if(file_exists($config_path)) break;
    }
    require_once($config_path);
}

session_start();
if(!isset($_SESSION['usuario'])){
	header ("Location: ".ROOT2."index.php");
}

require_once ROOT."/controle/memorando/MemorandoControle.php";

$memorandos = new MemorandoControle;
$memorandos->listarTodosInativos();
	
// Adiciona a Função display_campo($nome_campo, $tipo_campo)
require_once ROOT."/html/personalizacao_display.php";
?>

<!DOCTYPE html>
<html class="fixed">
<head>
	<!-- Basic -->
	<meta charset="UTF-8">

	<title>Memorandos despachados</title>
		
	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
  	<!-- Vendor CSS -->
  	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/vendor/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/vendor/font-awesome/css/font-awesome.css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/vendor/magnific-popup/magnific-popup.css" />
	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
	<link rel="icon" href="<?php display_campo("Logo",'file');?>" type="image/x-icon" id="logo-icon">

	<!-- Specific Page Vendor CSS -->
	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/vendor/select2/select2.css" />
	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />

	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/stylesheets/theme.css" />

	<!-- Skin CSS -->
	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/stylesheets/skins/default.css" />

	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="<?php echo ROOT2;?>assets/stylesheets/theme-custom.css">

	<!-- Head Libs -->
	<script src="<?php echo ROOT2;?>assets/vendor/modernizr/modernizr.js"></script>
		
	<!-- Vendor -->
	<script src="<?php echo ROOT2;?>assets/vendor/jquery/jquery.min.js"></script>
	<script src="<?php echo ROOT2;?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
	<script src="<?php echo ROOT2;?>assets/vendor/bootstrap/js/bootstrap.js"></script>
	<script src="<?php echo ROOT2;?>assets/vendor/nanoscroller/nanoscroller.js"></script>
	<script src="<?php echo ROOT2;?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="<?php echo ROOT2;?>assets/vendor/magnific-popup/magnific-popup.js"></script>
	<script src="<?php echo ROOT2;?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
	<!-- Specific Page Vendor -->
	<script src="<?php echo ROOT2;?>assets/vendor/jquery-autosize/jquery.autosize.js"></script>
		
	<!-- Theme Base, Components and Settings -->
	<script src="<?php echo ROOT2;?>assets/javascripts/theme.js"></script>
		
	<!-- Theme Custom -->
	<script src="<?php echo ROOT2;?>assets/javascripts/theme.custom.js"></script>
		
	<!-- Theme Initialization Files -->
	<script src="<?php echo ROOT2;?>assets/javascripts/theme.init.js"></script>


	<!-- javascript functions -->
	<script src="<?php echo ROOT2;?>Functions/onlyNumbers.js"></script>
	<script src="<?php echo ROOT2;?>Functions/onlyChars.js"></script>
	<script src="<?php echo ROOT2;?>Functions/enviar_dados.js"></script>
	<script src="<?php echo ROOT2;?>Functions/mascara.js"></script>
		
	<!-- jquery functions -->

   	<script>
	$(function(){
		var memorando=<?php echo $_SESSION['memorandoInativo']?>;
		console.log(memorando);
		$.each(memorando,function(i,item){
			$("#tabela")
				.append($("<tr>")
					.append($("<td>")
						.text(item.id_memorando))
					.append($("<td>")
						.html("<a href=<?php echo ROOT2;?>html/memorando/listar_despachos.php?id_memorando="+item.id_memorando+"&arq=1>"+item.titulo+"</a>")));
		});
	
        $("#header").load("<?php echo ROOT2;?>html/header.php");
        $(".menuu").load("<?php echo ROOT2;?>html/menu.html");
    });
	</script>
	
	<style type="text/css">
		.select{
			/*z-index: 2;*/
			/*float: left;*/
			position: absolute;
			width: 235px;
		}
		.select-table-filter{
			width: 140px;
			float: left;
		}
	</style>
</head>
<body>
	<section class="body">
		<!-- start: header -->
		<div id="header"></div>
      	<!-- end: header -->
      	<div class="inner-wrapper">
         	<!-- start: sidebar -->
         	<aside id="sidebar-left" class="sidebar-left menuu"></aside>
			<!-- end: sidebar -->
			<section role="main" class="content-body">
				<header class="page-header">
					<h2>Memorando despachado</h2>
					<div class="right-wrapper pull-right">
						<ol class="breadcrumbs">
							<li>
								<a href="<?php echo $ROOT2;?>home.php">
									<i class="fa fa-home"></i>
								</a>
							</li>
							<li><span>Memorando despachado</span></li>
						</ol>
						<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
					</div>
				</header>
				<!-- start: page -->
				<section class="panel" >
					<header class="panel-heading">
						<h2 class="panel-title">Memorando despachado</h2>
					</header>
					<div class="panel-body" >
						<div class="select" >
							<select class="select-table-filter form-control mb-md" data-table="order-table">
								<option selected disabled>Memorando despachado</option>
							</select></h5>
	  					</div>
	  					<button style="float: right;" class="mb-xs mt-xs mr-xs btn btn-default">Imprimir</button>
	  					<br><br>
		  					
						<table class="table table-bordered table-striped mb-none" id="datatable-default">
							<thead>
								<tr>
									<th>codigo</th>
									<th>titulo</th>
								</tr>
							</thead>
							<tbody id="tabela">
							</tbody>
						</table>
					</div>
				</section>
			</section>
		</div>
	</section>
	
	<!-- end: page -->
	<!-- Vendor -->
		<script src="<?php echo $ROOT;?>assets/vendor/select2/select2.js"></script>
		<script src="<?php echo $ROOT;?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="<?php echo $ROOT;?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="<?php echo $ROOT;?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo $ROOT;?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo $ROOT;?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo $ROOT;?>assets/javascripts/theme.init.js"></script>
		<!-- Examples -->
		<script src="<?php echo $ROOT;?>assets/javascripts/tables/examples.datatables.default.js"></script>
		<script src="<?php echo $ROOT;?>assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
		<script src="<?php echo $ROOT;?>assets/javascripts/tables/examples.datatables.tabletools.js"></script>
	</body>
</html>
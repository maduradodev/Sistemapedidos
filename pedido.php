
<?php
  require_once 'includes/common.php';
  require_once 'includes/database.php';

  $orderid= $_GET['orderid'];
  $result = $conn->query("SELECT cadastro.nome, cadastro.id, estoque.combo_name from cadastro left join estoque on estoque.id = cadastro.pedidoo WHERE cadastro.id = {$orderid}");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>IMPRESSÃO PEDIDO</title>

</head>


<body style="background-color:white">

<div id="print">
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()) : ?>
      <center style="margin-top:-18%;padding:12%">
      <p style="font-size:28px;font-weight:900;text-transform: uppercase;font-family:Segoe UI;"><?= $row["nome"] ?></p>
      <p style="font-size:19px;font-family:Segoe UI;" ><?= $row["combo_name"] ?></p>
      <p style="font-size:19px;font-family:Segoe UI;"> Obrigado por visitar o estande da MSD! Aproveite sua refeição!</p>
    </center>
    <?php endwhile ?>
<?php endif; ?>

</div>

</body>

</html>


<script>

  window.print(document.getElementById('print'));

</script>
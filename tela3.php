<?php
	require_once 'includes/common.php';
  require_once 'includes/database.php';

  $url1=$_SERVER['REQUEST_URI'];
  header("Refresh: 10; URL=$url1");


  $error = FALSE;
  
  // Get combos information
  $combos_list = [];
  $result = $conn->query("SELECT * from estoque order by position ASC");
  
  if($conn->error)
  {
    $error = $conn->error;
  } else if ($result->num_rows > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
      $combos_list[] = [
        'name' => $row['combo_name'],
        'quantity' => $row['quantity'],
        'image' => $row['combo_photo'],
        'availability' => [$row['display_start'], $row['display_end']]
      ];
    }
  }

  // Get last 4 orders information
  $recent_orders_list = [];
  $result = $conn->query("SELECT cadastro.nome, cadastro.id, estoque.combo_name, estoque.combo_photo from cadastro left join estoque on estoque.id = cadastro.pedidoo order by cadastro.id DESC LIMIT 4");
  
  if($conn->error)
  {
    $error = $conn->error;
  } else if ($result->num_rows > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
      $recent_orders_list[] = [
        'order_id' => $row['id'],
        'client_name' => $row['nome'],
        'combo_name' => $row['combo_name'],
        'combo_photo' => $row['combo_photo'],
      ];
    }
  }

  // Get all orders information
  $orders_list = [];
  $result = $conn->query("SELECT cadastro.nome, cadastro.id, estoque.combo_name, estoque.combo_photo from cadastro left join estoque on estoque.id = cadastro.pedidoo order by cadastro.id DESC");
  
  if($conn->error)
  {
    $error = $conn->error;
  } else if ($result->num_rows > 0) {

    while ($row = mysqli_fetch_assoc($result)) {
      $orders_list[] = [
        'order_id' => $row['id'],
        'client_name' => $row['nome'],
        'combo_name' => $row['combo_name'],
        'combo_photo' => $row['combo_photo'],
      ];
    }
  }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Formulário de contato</title>
	<link rel="stylesheet" href="css/bulma.min.css">
	<link rel="stylesheet" href="css/estilo.css">
  <link rel="stylesheet" href="css/estilo2.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">

</script>

  <nav class="navbar">
      <a class="mx-auto">
      <img src="img/logo.png" width="200" height="300" style="align:middle;" alt="">
      </a>
  </nav>

  <div class="bg">

  <div class="cards">
    <?php if($error): ?>
      <div class="notification is-danger">
        <p>Ocorreu um erro ao processar seu pedido.</p>
        <?php print_r($error); ?>
      </div>
    <?php endif; ?>

    <div id="refresh" style="font-family:'Segoe UI';margin-left:600px;"></div>

    <div class="columns">
      <div class="column estoque">

        <div class="column-estoque">

          <h5 class="card-title">ESTOQUE</h5>
          
          <div class="scroll">
          <?php foreach($combos_list as $combo): ?>

            <div class="info1">
            <img class="comboimg" src="<?= BASE_URL . $combo['image'] ?>" alt="Combo"/>

            <div class="info2"> 
              <h5 class="comboname"><?= $combo['name'] ?></h5>
              <h5 class="comboquantity text-muted"><?= $combo['quantity'] ?> RESTANTES</h5>
          </div>      

            </div>
          <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="column pedidos">
        <div class="column-pedidos">
        <h5 class="card-title">PEDIDOS</h5>
          <?php foreach($recent_orders_list as $order): ?>
            <div>
            <img class="comboimg" src="<?= BASE_URL . $order['combo_photo'] ?>" alt="<?= $order['combo_name'] ?>"/>  
              <h5 class="client_name"><?= $order['client_name'] ?></h5>
              <h5 class="combo_name"><?= $order['combo_name'] ?></h5>
              <input class="order_check" type="radio" name="pedido" value="<?= $order["order_id"] ?>"  />
              <h5 class="combo_id">Id: <?= $order['order_id'] ?></h5>
              
            </div>                
          <?php endforeach; ?>
        </div>
      </div>

      <div class="column historico" >
          <div class="column-historico">
          <h5 class="card-title">HISTÓRICO</h5>
            <?php foreach($orders_list as $order): ?>
              <div>                
              <img class="comboimg" src="<?= BASE_URL . $order['combo_photo'] ?>" alt="<?= $order['combo_name'] ?>"/>    
              <h5 class="client_name"><?= $order['client_name'] ?></h5>
              <h5 class="combo_name"><?= $order['combo_name'] ?></h5>
              <input class="order_check" type="radio" name="pedido" value="<?= $order["order_id"] ?>"  />
                <h5 class="combo_id">Id: <?= $order['order_id'] ?></h5>
                
              </div>
            <?php endforeach; ?>
          </div>
      </div> 
    </div>

    <div class="field is-grouped" style="justify-content: center;">
      <div class="control">
        <button id="print" class="button">IMPRIMIR</button>
      </div>		
    </div>
  </div>



</div>
  <script>
    document.getElementById('print').addEventListener('click', function(){
    var selectedorders = document.querySelectorAll('input[type="radio"]:checked');
    for(var order of selectedorders){
      window.open('http://madurado.tech/pedido.php?orderid='+order.value)
    }
    })
    
    var timeleft = 9;
var downloadTimer = setInterval(function(){
  if(timeleft <= 0){
    clearInterval(downloadTimer);
    document.getElementById("refresh").innerHTML = "";
  } else {
    document.getElementById("refresh").innerHTML = "Atualizando em " + timeleft + " segundos";
  }
  timeleft -= 1;
}, 1000);

</script>

</body>
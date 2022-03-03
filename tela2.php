<?php





  $error = FALSE;



  // List of available combos for time of day

  $available_combos = [];



  // Get combo list from database




  if($conn->error)

  {

    $error = $conn->error;

  }

  else if ($result->num_rows > 0) {

    $combos_list = [];

    $current_time = date('G:i:s', strtotime('now')); // Horario atual no formato 24 horas



    while ($row = mysqli_fetch_assoc($result)) {

      $combos_list[] = [
        'name' => $row['combo_name'],
        'image' => $row['combo_photo'],
        'quantity' => $row['quantity'],
        'order' => $row['position'],
        'description' => $row['description'],
        'id' => $row['id'],
        'availability' => [$row['display_start'], $row['display_end']]
      ];

    }

    // Determine available products for the current time of day

    for($i = 0; $i < count($combos_list); $i++)

    {

        $combo = $combos_list[$i];

        

        $start_time_availability = strtotime($combo['availability'][0]); // Horario inicial de disponibilidade

        $end_time_availability =  strtotime($combo['availability'][1]); // Horario final de disponibilidade

        

        // Calculando a diferença entre os horários para saber se o produto está disponivel agora

        $start_time_diff = (strtotime($current_time) >= $start_time_availability ? 1 : 0);

        $end_time_diff = (strtotime($current_time) <= $end_time_availability ? 1 : 0);

        

        $is_available_for_display = ($start_time_diff === 1 && $end_time_diff === 1); // Produto disponivel neste horario

        $is_available_in_stock = $combo['quantity'] > 0; // Produto em estoque

        

        if($is_available_for_display && $is_available_in_stock){

            $available_combos[] = $combo;

        }

    }

  }



  // Process form submit

  if ($_SERVER["REQUEST_METHOD"] == "POST") {



    $selected_lunch_box = NULL;

    if(isset($_POST['lunchs']) && count($_POST['lunchs']) > 0){

      $selected_lunch_box = $_POST['lunchs'][0]; 

      $user_id = $_SESSION['user_id'];



      if ($conn->query("UPDATE cadastro SET pedidoo = {$selected_lunch_box} WHERE id = {$user_id}") === TRUE) {

        

        // Update product inventory quantity

        $conn->query("UPDATE estoque set quantity = CASE WHEN quantity > 0 THEN quantity -1 ELSE 0 END WHERE id = {$selected_lunch_box}");



        // End session

        session_destroy();

        // Redirect to home

        header("Location:" . BASE_URL);

        exit();

      } else {

        // Store error msg

        $error = $conn->error;

      }

    } else {

      $error = "Selecione um produto para continuar.";

    }

  }

?>

<!DOCTYPE html>

<html lang="pt-br">

    <head>

        <meta charset="UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Formulário de contato</title>

        <link rel="stylesheet" href="css/bulma.min.css" />

        <link rel="stylesheet" href="css/estilo.css" />
        <link rel="stylesheet" href="css/estilo2.css" />

        <style>

            /* html {

                overflow: hidden;

            } */

        </style>

    </head>



    <body>

        <nav class="navbar">

            <a class="mx-auto">

                <img src="img/logo.png" width="200" height="300" style="align: middle;" alt="" />

            </a>

        </nav>



        <div class="cards" style="overflow: hidden; padding: 1%;">

              <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="lunchbox-form">

                <div class="columns">

                    <?php foreach($available_combos as $combo): ?>

                      <div class="column">

                          <div class="lunchbox-card">

                              <h5><?= $combo['name'] ?></h5>

                              <img width="150px" height="150px" src="<?= BASE_URL . $combo['image'] ?>" alt="<?= $combo['name'] ?>" />

                              <div class="container">

                                  <?= $combo['description'] ?>

                                  <input type="radio" class="form-check-input" id="checkLunch1" name="lunchs[]" value="<?= $combo['id'] ?>" />

                              </div>

                          </div>

                      </div>

                    <?php endforeach; ?>

                </div>

                <?php if($error): ?>

                  <div class="notification is-danger">

                    <p>Ocorreu um erro ao processar seu pedido.</p>

                    <?php print_r($error); ?>

                  </div>

                <?php endif; ?>

                <div class="field is-grouped" style="justify-content: center;">

                    <div class="control">

                        <button type="submit" class="button is-link is-medium" value="Submit" name="submit"  style="color:#71bf49">

                            <img src="./img/shpbg.svg" width="30" height="30" style="color:#71bf49"/> CONFIRMAR

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </body>

</html>


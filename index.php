<?php


	// Form was submitted 
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$nome = $_POST['nome'];
		$crm = $_POST['crm'];
		$email = $_POST['email'];
        if(isset($_POST['autoriza']))
        {
            $autoriza = "SIM";
        }
        else
        {
            $autoriza = "NAO";
        }
		// Insert user data into the database
		$sql = "INSERT INTO cadastro (nome, email, crm, autoriza) VALUES ('{$nome}', '{$email}', '{$crm}', '{$autoriza}')";
		if ($conn->query($sql) === TRUE) {
			// Set session data
			$_SESSION['user_id'] = $conn->insert_id;
			$_SESSION['nome'] = $_POST['nome'];
			$_SESSION['crm'] = $_POST['crm'];
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['autoriza'] = $autoriza;
			$_SESSION['is_logged'] = TRUE;

			// Redirect
			header("Location:" . BASE_URL . "tela2.php");
			exit();
		} else {
			// Store error msg
			$error = $conn->error;
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    </head>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">

</script>

    <body>
        <nav class="navbar">
            <a class="mx-auto">
                <img src="img/logo.png" width="200" height="300" style="align: middle;" alt="" />
            </a>
        </nav>
      
<div style="background-color:#71bf49">
  <br>
        <section class="section">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-half">
                        <h1 class="title has-text-centered">Insira suas informações</h1>
						

		


                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="field">
                                <label class="label" color="#363636" for="nome">Nome *</label>
                                <div class="control">
                                    <input id="nome" name="nome" class="input" type="text" placeholder="Seu nome" maxlength="30" required />
                                </div>
                            </div>

                            <div class="field">
                                <label class="label" color="#363636" for="email">Email *</label>
                                <div class="control">
                                    <input id="email" name="email" class="input" type="email" placeholder="Seu email" required />
                                </div>
                            </div>

                            <div class="field">
                                <label class="label" color="#363636" for="crm">CRM *</label>
                                <div class="control">
                                    <input id="crm" name="crm" class="input" type="text" placeholder="Seu CRM" required />
                                </div>
                            </div>
                            <label class="title" style="font-size: 110%;">
                                Autorizo que os dados recolhidos sejam processados e armazenados informaticamente pela Merck Sharp & Dohme Farm. Ltda. (MSD) e suas associadas.
                            </label>
                            <label class="title" style="font-size: 110%;">
                                A MSD, entidade responsável pelo tratamento dos seus Dados Pessoais, poderá compartilhar alguns desses dados a empresas do Grupo MSD (dentro ou fora do Brasil) ou entidades/empresas terceiras no(s) âmbito(s)
                                abaixo referidos:
                            </label>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkAutorizo" name="autoriza" value="SIM" />
                                <label class="title" style="font-size: 110%;" for="checkAutorizo">
                                    Autorizo a MSD a me enviar comunicações para difundir informações científicas, promocionais, de eventos e serviços, através de e-mail, contato telefônico e mensagens de celular (p. ex: SMS e WhatsApp®).
                                </label>
                            </div>
                            <label class="title" style="font-size: 110%;">Termos de Utilização e Política de Privacidade</label>
                            <label class="title" style="font-size: 110%;">
                                Para efetuar o seu registo é necessário que aceite a nossa
                                <a href="https://www.msd.com.br/global/privacy/" target="_blank">Política de Privacidade</a> e os <a href="https://www.msd.com.br/global/terms/" target="_blank">Termos de Uso</a>.
                            </label>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkAutorizo" name="checkAutorizo" required />
                                <label class="title" style="font-size: 110%;" for="checkAutorizo"> Confirmo que li, compreendi e concordo com os Termos de Uso.</label>
                            </div>
                            <br />
                            <input type="checkbox" class="form-check-input" id="checkAutorizo" required />
                            <label class="title" style="font-size: 110%;" for="exampleCheck1">Confirmo que li, compreendi e concordo com a Política de Privacidade.</label>
                            <label class="title" style="font-size: 110%;">
                                O(a) interessado(a) pode requerer o acesso, a atualização, a retificação e a eliminação dos seus dados pessoais a todo o tempo, mediante pedido enviado para a sede da Merck Sharp & Dohme Farm. Ltda., ao
                                cuidado do Responsável pela Privacidade, ou para o endereço de e-mail:<a href="mailto:BRprivacy@merck.com"> BRprivacy@merck.com </a>
                            </label>
                            <br />
                            <br />
                            <div class="field is-grouped" style="justify-content: center;">
                                <div class="control">
                                    <input value="CONFIRMAR" type="submit" class="button is-link is-medium" onclick="assinar()" style="color:#71bf49"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</div>

        </section>
        <script>
			 // Recomendo validar o form antes do submit
            function assinar() {
                // window.location.href = "tela2.php";
            }
        </script>
    </body>
</html>

<html>
<head> <title> Entrada de dados com forms </title>
<body>
<?php
$script = "ad2q1-form.php";



$nomeError="";$cpfError="";$enderError="";$emailError="";


if (empty($_GET["cpf"])&&empty($_GET["nome"])&&empty($_GET["endereco"])&&empty($_GET["email"]))
{
    ?>

    <form action="<?=$script;?>" method="GET">
        <br> CPF:
        <input type="text" name="cpf" >
        <br> Nome:
        <input type="text" name="nome" >
        <br> Endereço:
        <input type="text" name="endereco" >
        <br> Email:
        <input type="text" name="email" >
        <br>
        <input type="submit" value="Cadastrar">
    </form><br>
    <?php
}else {

//Valida Nome
    if ((preg_match("/^[A-Z].*$/", $_GET["nome"])) == 0)
        $nomeError = "O nome deve conter apenas letras!";
//Valida Email
    if ((preg_match("/^[A-Za-z0-9]+\@[A-Za-z0-9]+\.[A-Za-z0-9]+/", $_GET["email"])) == 0)
        $emailError = "Email inválido!";
//Valida CPF
    $cpf = $_GET["cpf"];
    $num = "";

    if (((preg_match("/^[0-9]{3}(\.[0-9]{3})(\.[0-9]{3})(\-[0-9]{2})?$/", $cpf)) == 0) && ((preg_match("/^[0-9]{11}?$/", $cpf) == 0))) {
        $cpfError = "CPF Inválido!";

    } else {
        //Remove acentos:
        for ($i = 0; $i < strlen($cpf); $i++) {
            if (($cpf[$i] != ".") && ($cpf[$i] != "-")) {
                $num .= $cpf[$i];
            }
        }

        $cpf = $num;

        //Verifica formato

        $primDigit = intval($cpf[9], 10);
        $segDigit = intval($cpf[10], 10);
        $soma = 0;
        $cont = 0;
        $cont1 = 0;

        $soma = (((int)$cpf[0] * 10) + ((int)$cpf[1] * 9) + ((int)$cpf[2] * 8) + ((int)$cpf[3] * 7) + ((int)$cpf[4] * 6) + ((int)$cpf[5] * 5) + ((int)$cpf[6] * 4) + ((int)$cpf[7] * 3) + ((int)$cpf[8] * 2));

        $resto = $soma % 11;

        if ($resto < 2) {
            if ($primDigit == 0) ;
            $cont++;
        } elseif ($resto >= 2) {
            if ($primDigit == (11 - $resto)) {
                $cont++;
            }
        }

        $soma = (((int)$cpf[0] * 11) + ((int)$cpf[1] * 10) + ((int)$cpf[2] * 9) + ((int)$cpf[3] * 8) + ((int)$cpf[4] * 7) + ((int)$cpf[5] * 6) + ((int)$cpf[6] * 5) + ((int)$cpf[7] * 4) + ((int)$cpf[8] * 3) + ((int)$cpf[9] * 2));
        $resto = $soma % 11;

        if ($resto < 2) {
            if ($segDigit == 0) ;
            $cont1++;
        } elseif ($resto >= 2) {
            if ($segDigit == (11 - $resto)) {
                $cont1++;
            }
        }

        if ($cont == 1 && $cont1 == 1) {
            $cpfError = "";

        } else {
            $cpfError = "CPF Inválido!!";
        }

    }

    if (($cpfError != "") || ($nomeError != "") || ($emailError != "") || ($enderError != "")) {
        ?>

        <form action="<?= $script; ?>" method="GET">
            <br> CPF:
            <input type="text" name="cpf"> <?php echo $cpfError; ?>
            <br> Nome:
            <input type="text" name="nome"> <?php echo $nomeError; ?>
            <br> Endereço:
            <input type="text" name="endereco"> <?php echo $enderError; ?>
            <br> Email:
            <input type="text" name="email"><?php echo $emailError; ?>
            <br>
            <input type="submit" value="Cadastrar">
        </form><br>
        <?php

    } else {//Questão 2 :

        define("DB_SERVER", "localhost");
        define("DB_USER", "root");
        define("DB_PASSWORD", "");
        define("DB_DATABASE", "cadastro");

        $con = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);
        if (!$con) {
            die("Conexão falhou! " . mysqli_connect_error());
        }
    $sql = "INSERT INTO Cadastro (cpf,nome,endereco, email) VALUES ('$cpf','$_GET[nome]','$_GET[endereco]','$_GET[email]')";

        if (mysqli_query($con, $sql)) {
            echo "Dados cadastrados com sucesso!";
        } else {

            ?>
            <form action="<?= $script; ?>" method="GET">
                <br> CPF:
                <input type="text" name="cpf"> <?php echo "Error: ". mysqli_error($con); ?>
                <br> Nome:
                <input type="text" name="nome">
                <br> Endereço:
                <input type="text" name="endereco">
                <br> Email:
                <input type="text" name="email">
                <br>
                <input type="submit" value="Cadastrar">
            </form><br>


            <?php
        }
        mysqli_close($con);


    }
}
?>

    </body>
    </html>
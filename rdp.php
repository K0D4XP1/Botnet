<?php
    $hwid = $_GET['hwid'];
    if (is_file("Users/".$hwid."/cmd.txt")) {
        $fopen = fopen("Users/".$hwid."/cmd.txt", "a");
        fwrite($fopen, "rdp");
        fclose($fopen);
    }
?>
<html>
    <head>
        <title></title>
        <style>
            body {
                margin:0px;
            }
            img {
                width:100%;
                height:100%;
            }
        </style>
    </head>
    <body>
        <img src="Users/<?php echo $_GET['hwid']; ?>/rdp.png">
    </body>
</html>
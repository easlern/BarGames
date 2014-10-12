<?php
    require_once ('code/startup.php');
?>


<html>
    <body>
        <h1>
            Diagnostic stuff
        </h1>
        <p>
            <?php
                print ("Checking mode. . . ");
                print ("domain is " . Utils::GetDomain() . ". ");
                if (Utils::GetMode() === Utils::MODE_DEV) print ("Mode is dev");
                else print ("Mode is prod");
            ?>
        </p>
    </body>
</html>

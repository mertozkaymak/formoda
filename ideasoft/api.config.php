<?php

    //Database Info
    define("HOST", "localhost");
    define("DBNAME", "formoda");
    define("USER", "formoda");
    define("PASS", "Digital1357*c");

    //Shop Info
    define("URL", "https://www.formoda.com.tr");
    define("CLIENTID", "7_1qwi65z4fqlccsc0s408gcowwo88ocww4cww4csk04g0880o44");
    define("SECRETID", "x7asn5azzm88ckg80gc8gco8g0oco0g4osk8o04g88ws8gwgg");
    define("REDIRECT", "https://dev.digitalfikirler.com/formoda/controller.php");
    define("ACCESS", URL . "/admin/user/auth?client_id=" . CLIENTID . "&response_type=code&state=d12b2a61b81a52be4a4cac7e610f62f8&redirect_uri=" . REDIRECT);

?>
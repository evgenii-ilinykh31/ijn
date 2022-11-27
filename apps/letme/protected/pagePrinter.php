<?php

require_once 'helpers/sessionExtractor.php';
require_once 'helpers/appPage.php';


use Helpers\SessionExtractor;
use Helpers\AppPage;



class PagePrinter {

    public static function main()
    {
        $sessionId = SessionExtractor::getId();

        if ($sessionId)
        {
            print AppPage::getJobMode($sessionId);
        }
        else
        {
            print AppPage::getLoginMode();
        }
    }

}

PagePrinter::main();

?>
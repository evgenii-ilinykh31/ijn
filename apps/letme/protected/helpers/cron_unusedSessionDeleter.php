<?php


require_once '/home/ijn/letme/protected/helpers/organizers/constants.php';
require_once '/home/ijn/letme/protected/helpers/models/sessions.php';

use Helpers\Organizers\Constants;
use Helpers\Models\Sessions;

Sessions::deleteUnused(Constants::unusedSessionLive);


?>
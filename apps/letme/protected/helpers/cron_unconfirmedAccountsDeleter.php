<?php

require_once '/home/ijn/apps/letme/protected/helpers/organizers/constants.php';
require_once '/home/ijn/apps/letme/protected/helpers/models/users.php';

use Helpers\Organizers\Constants;
use Helpers\Models\Users;

Users::deleteUnconfirmed(Constants::unconfirmedAccountLive);

?>
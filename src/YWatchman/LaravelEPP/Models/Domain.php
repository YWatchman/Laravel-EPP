<?php

namespace YWatchman\LaravelEPP\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    /** @var $name string Domain name */

    /** @var $registrant integer Registrant contact id */

    /** @var $admin integer Admin contact id */

    /** @var $tech integer Tech contact id */

    /** @var $billing integer Billing contact id */

    /** @var $nameservers array Nameservers in array */
}

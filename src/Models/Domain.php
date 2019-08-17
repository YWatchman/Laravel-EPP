<?php

namespace YWatchman\LaravelEPP\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{

    /** @var $name string Domain name */
    public $name;

    /** @var $registrant integer Registrant contact id */
    public $registrant;

    /** @var $admin integer Admin contact id */
    public $admin;

    /** @var $tech integer Tech contact id */
    public $tech;

    /** @var $billing integer Billing contact id */
    public $billing;

    /** @var $nameservers array Nameservers in array */
    public $nameservers;

}

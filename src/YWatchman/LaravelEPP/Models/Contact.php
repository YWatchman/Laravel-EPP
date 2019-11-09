<?php

namespace YWatchman\LaravelEPP\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /* @var $id integer Registrant id */

    /* @var $contact_id integer Epp service contact id */

    /* @var $handle string Epp service contact handle */

    /* @var $email string Contact Email Address */

    /* @var $phone string ITU E.164 Phone number */

    /* @var $name string Person's full name */

    /* @var $organization string|null Organization name, not required */

    /* @var $address string Street address including number */

    /* @var $postal string Postal code */

    /* @var string City */

    /* @var string Country ISO 3166-1 2 char */
}

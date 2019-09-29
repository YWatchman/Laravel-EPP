<?php

namespace YWatchman\LaravelEPP\Models\Domain;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /** @var $id integer Registrant id */
    public $id;

    /** @var $contactId integer Epp service contact id */
    public $contactId;

    /** @var $email string Contact Email Address */
    public $email;

    /** @var $phone string ITU E.164 Phone number */
    public $phone;

    /** @var $name string Person's full name */
    public $name;

    /** @var $organization string|null Organization name, not required */
    public $organization;

    /** @var $address string Street address including number */
    public $address;

    /** @var $postal string Postal code */
    public $postal;

    /** @var string City */
    public $city;

    /** @var string Country ISO 3166-1 2 char */
    public $country;
}

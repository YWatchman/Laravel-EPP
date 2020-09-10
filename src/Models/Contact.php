<?php

namespace YWatchman\LaravelEPP\Models;

use YWatchman\LaravelEPP\Contracts\IsContact;
use YWatchman\LaravelEPP\Contracts\Transformable;

class Contact extends Model implements Transformable, IsContact
{
    protected $columns = [
        'street',
        'number',
        'suffix',
        'city',
        'state',
        'phone',
        'fax',
        'email',
        'handle',
        'name',
        'organization',
        'postal',
        'country',
        'legalForm',
        'legalFormNo',
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        $data = $this->attributes;

        if (!empty($this->legalFormNo)) {
            $data['legalFormNo'] = $this->legalFormNo;
        }

        return $data;
    }
}

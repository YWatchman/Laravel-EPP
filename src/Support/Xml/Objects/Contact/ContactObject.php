<?php

namespace YWatchman\LaravelEPP\Support\Xml\Objects\Contact;

use YWatchman\LaravelEPP\Exceptions\EppException;
use YWatchman\LaravelEPP\Models\Contact;

abstract class ContactObject
{
    /**
     * Possible contact types to use.
     */
    public const CONTACT_TYPES = [
        self::CONTACT_ADMIN,
        self::CONTACT_TECH,
        self::CONTACT_BILLING,
        self::CONTACT_REGISTRANT,
    ];

    public const CONTACT_ADMIN = 'admin';

    public const CONTACT_TECH = 'tech';

    public const CONTACT_BILLING = 'billing';

    public const CONTACT_REGISTRANT = 'registrant';

    /** @var string */
    public $type;

    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $org;

    /** @var string */
    public $street;

    /** @var string */
    public $city;

    /** @var string */
    public $sp;

    /** @var string */
    public $pc;

    /** @var string */
    public $cc;

    /** @var string */
    public $voice;

    /** @var string */
    public $fax;

    /** @var string */
    public $email;

    /** @var bool */
    public $disclose;

    /**
     * ContactObject constructor.
     *
     *
     * @throws EppException
     */
    public function __construct(string $type)
    {
        if (! in_array($type, self::CONTACT_TYPES)) {
            throw EppException::contactTypeDoesNotExist($type);
        }
        $this->setType($type);
    }

    /**
     * Return ContactObject from Contact modal.
     */
    public static function createFromModel(Contact $contact)
    {
        //        return new self($contact->)
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOrg(): string
    {
        return $this->org;
    }

    public function setOrg(string $org): void
    {
        $this->org = $org;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getSp(): string
    {
        return $this->sp;
    }

    public function setSp(string $sp): void
    {
        $this->sp = $sp;
    }

    public function getPc(): string
    {
        return $this->pc;
    }

    public function setPc(string $pc): void
    {
        $this->pc = $pc;
    }

    public function getCc(): string
    {
        return $this->cc;
    }

    public function setCc(string $cc): void
    {
        $this->cc = $cc;
    }

    public function getVoice(): string
    {
        return $this->voice;
    }

    public function setVoice(string $voice): void
    {
        $this->voice = $voice;
    }

    public function getFax(): string
    {
        return $this->fax;
    }

    public function setFax(string $fax): void
    {
        $this->fax = $fax;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function isDisclose(): bool
    {
        return $this->disclose;
    }

    public function setDisclose(bool $disclose): void
    {
        $this->disclose = $disclose;
    }
}

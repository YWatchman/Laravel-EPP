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
        self::CONTACT_REGISTRANT
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

    /** @var boolean */
    public $disclose;

    /**
     * ContactObject constructor.
     * @param string $type
     * @throws EppException
     */
    public function __construct(string $type)
    {
        if (!in_array($type, self::CONTACT_TYPES)) {
            throw EppException::contactTypeDoesNotExist($type);
        }
        $this->setType($type);
    }

    /**
     * Return ContactObject from Contact modal.
     *
     * @param Contact $contact
     */
    public static function createFromModel(Contact $contact)
    {
//        return new self($contact->)
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getOrg(): string
    {
        return $this->org;
    }

    /**
     * @param string $org
     */
    public function setOrg(string $org): void
    {
        $this->org = $org;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getSp(): string
    {
        return $this->sp;
    }

    /**
     * @param string $sp
     */
    public function setSp(string $sp): void
    {
        $this->sp = $sp;
    }

    /**
     * @return string
     */
    public function getPc(): string
    {
        return $this->pc;
    }

    /**
     * @param string $pc
     */
    public function setPc(string $pc): void
    {
        $this->pc = $pc;
    }

    /**
     * @return string
     */
    public function getCc(): string
    {
        return $this->cc;
    }

    /**
     * @param string $cc
     */
    public function setCc(string $cc): void
    {
        $this->cc = $cc;
    }

    /**
     * @return string
     */
    public function getVoice(): string
    {
        return $this->voice;
    }

    /**
     * @param string $voice
     */
    public function setVoice(string $voice): void
    {
        $this->voice = $voice;
    }

    /**
     * @return string
     */
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax(string $fax): void
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isDisclose(): bool
    {
        return $this->disclose;
    }

    /**
     * @param bool $disclose
     */
    public function setDisclose(bool $disclose): void
    {
        $this->disclose = $disclose;
    }
}

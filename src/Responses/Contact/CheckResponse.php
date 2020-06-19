<?php


namespace YWatchman\LaravelEPP\Responses\Contact;

use Symfony\Component\DomCrawler\Crawler;
use YWatchman\LaravelEPP\Responses\Response;

class CheckResponse extends Response
{

    /** @var string[] */
    protected $existingContacts = [];

    /**
     * CheckResponse constructor.
     *
     * @param string $rawXml
     */
    public function __construct(string $rawXml)
    {
        parent::__construct($rawXml);
        $data = $this->response->filter('response > resData > chkData > cd');

        $data->each(function (Crawler $contact) {
            $contact = $contact->filter('cd > id');
            if ($contact->attr('avail') === 'false') {
                $this->addExistentContact($contact->text());
            }
        });
    }

    /**
     * Check if contact exists.
     * @param string $contact
     * @return bool
     */
    public function contactExists(string $contact): bool
    {
        return in_array($contact, $this->existingContacts);
    }

    /**
     * Inverse of CheckResponse::contactExists().
     *
     * @param string $contact
     * @return bool
     */
    public function contactDoesNotExist(string $contact): bool
    {
        return ! $this->contactExists($contact);
    }

    /**
     * @param $contact
     */
    private function addExistentContact(string $contact)
    {
        $this->existingContacts[] = $contact;
    }
}

<?php


namespace YWatchman\LaravelEPP\Support\Traits\Commands;

trait HasDnssec
{

    /**
     * DNSSEC status.
     * @var bool
     */
    protected $dnssec = false;
    
    /** @var string */
    protected $pubKey;
    
    /** @var int */
    protected $protocol = 3;
    
    /** @var int */
    protected $flag = 257;
    
    /** @var int */
    protected $algorithm = 13;

    /**
     * Enable DNSSEC for request.
     */
    public function enableDNSSEC()
    {
        $this->dnssec = true;
        if (!in_array('dnssec', $this->extensions)) {
            $this->extensions[] = 'dnssec';
        }
    }


    /**
     * Set public dnskey.
     *
     * @param string $pubKey
     */
    public function setPublicKey(string $pubKey)
    {
        $this->pubKey = $pubKey;
    }

    /**
     * Set DNSSEC algorithm.
     *
     * @param int $algorithm
     */
    public function setAlgorithm(int $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Set DNSSEC RR flag.
     *
     * @param int $flag
     */
    public function setFlag(int $flag)
    {
        $this->flag = $flag;
    }

    /**
     * Set signing protocol.
     *
     * @param int $protocol
     */
    public function setProtocol(int $protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * DNSSEC node for extensions.
     *
     * @return mixed
     */
    private function dnssecNode()
    {
        $keyNode = $this->createElement('secDNS:keyData');
        $keyOptNode = [];
        $keyOptNode[] = $this->createElement('secDNS:flags', $this->flag);
        $keyOptNode[] = $this->createElement('secDNS:protocol', $this->protocol);
        $keyOptNode[] = $this->createElement('secDNS:alg', $this->algorithm);
        $keyOptNode[] = $this->createElement('secDNS:pubKey', base64_encode($this->pubKey));

        foreach ($keyOptNode as $node) {
            $keyNode->appendChild($node);
        }

        return $keyNode;
    }

    private function createDnssecExtension()
    {
        $node = $this->createElement('secDNS:create');
        $node->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');
        $node->appendChild($this->dnssecNode());

        return $node;
    }
}

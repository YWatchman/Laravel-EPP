<?php

namespace YWatchman\LaravelEPP\Support\Traits\Commands;

trait HasDnssec
{
    /**
     * DNSSEC status.
     */
    protected bool $dnssec = false;

    protected string $pubKey;

    protected int $protocol = 3;

    protected int $flag = 257;

    protected int $algorithm = 13;

    public function getPublicKey(): ?string
    {
        return $this->pubKey;
    }

    /**
     * Enable DNSSEC for request.
     */
    public function enableDNSSEC(): void
    {
        $this->dnssec = true;
    }

    /**
     * Set public dnskey.
     */
    public function setPublicKey(?string $pubKey): void
    {
        $this->pubKey = $pubKey;
    }

    /**
     * Set DNSSEC algorithm.
     */
    public function setAlgorithm(int $algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Set DNSSEC RR flag.
     */
    public function setFlag(int $flag): void
    {
        $this->flag = $flag;
    }

    /**
     * Set signing protocol.
     */
    public function setProtocol(int $protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * DNSSEC node for extensions.
     */
    private function dnssecNode(): mixed
    {
        $keyNode = $this->createElement('secDNS:keyData');
        $keyOptNode = [];
        $keyOptNode[] = $this->createElement('secDNS:flags', $this->flag);
        $keyOptNode[] = $this->createElement('secDNS:protocol', $this->protocol);
        $keyOptNode[] = $this->createElement('secDNS:alg', $this->algorithm);
        $keyOptNode[] = $this->createElement('secDNS:pubKey', $this->pubKey);

        foreach ($keyOptNode as $node) {
            $keyNode->appendChild($node);
        }

        return $keyNode;
    }

    private function createDnssecExtension(bool $update = false)
    {
        $pubKey = $this->extensions['dnssec']['pubKey'] ?? null;
        $this->setPublicKey($pubKey);

        if ($update) {
            $node = $this->createElement('secDNS:update');
            $node->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');

            $rem = $this->createElement('secDNS:rem');
            $rem->appendChild($this->createElement('secDNS:all'));
            $node->appendChild($rem);

            if ($this->getPublicKey() !== null) {
                $add = $this->createElement('secDNS:add');
                $add->appendChild($this->dnssecNode());
                $node->appendChild($add);
            }
        } else {
            $node = $this->createElement('secDNS:create');
            $node->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');
            $node->appendChild($this->dnssecNode());
        }

        return $node;
    }
}

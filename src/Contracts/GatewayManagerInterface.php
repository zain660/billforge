<?php

namespace Zain\LaravelSubscriptions\Contracts;

interface GatewayManagerInterface
{
    /**
     * Get the active payment gateway
     *
     * @return PaymentGatewayInterface|null
     */
    public function getActiveGateway();

    /**
     * Set the active payment gateway by key
     *
     * @param string $key
     */
    public function setActiveGateway(string $key);

    /**
     * Get instance of a specific gateway
     *
     * @param string $key
     * @return PaymentGatewayInterface|null
     */
    public function driver(string $key = null);
}

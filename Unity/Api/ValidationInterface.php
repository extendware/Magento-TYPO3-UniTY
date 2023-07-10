<?php
namespace WebVision\Unity\Api;

interface ValidationInterface
{
    /**
     * Validates the username and token.
     *
     * @api
     *
     * @param string $username
     * @param string $token
     *
     * @return string If the cache could be cleared or not.
     */
    public function validateToken($username, $token);
}

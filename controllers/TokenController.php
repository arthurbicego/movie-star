<?php

class TokenController
{

    public function generateToken()
    {
        return bin2hex(random_bytes(50));
    }
}

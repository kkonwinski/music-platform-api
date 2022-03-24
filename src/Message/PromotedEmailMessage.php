<?php

namespace App\Message;

class PromotedEmailMessage
{
    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */

     private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}

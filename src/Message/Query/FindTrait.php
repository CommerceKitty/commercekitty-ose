<?php declare(strict_types=1);

namespace CommerceKitty\Message\Query;

trait FindTrait
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}

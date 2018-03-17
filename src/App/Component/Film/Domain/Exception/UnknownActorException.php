<?php
namespace App\Component\Film\Domain\Exception;

use Throwable;

class UnknownActorException extends BadOperationException
{
    public $actorId;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withActorId(int $id):self
    {
        $e = new static("Actor with id [$id] doesn't exist");
        $e->actorId = $id;
        return $e;
    }
}
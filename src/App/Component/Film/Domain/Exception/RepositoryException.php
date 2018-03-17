<?php
namespace App\Component\Film\Domain\Exception;

use Exception;
use Throwable;

class RepositoryException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public static function withError(Throwable $repositoryException): self
    {
        return new static(
            "There has being an error while using the repository",
            0,
            $repositoryException
        );
    }
}
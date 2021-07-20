<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Communication\Client;

use Psr\Http\Client\ClientExceptionInterface;

class ClientException extends \RuntimeException implements ClientExceptionInterface
{
}

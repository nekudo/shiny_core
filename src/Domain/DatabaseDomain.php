<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Domain;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\Factory as DatabaseFactory;
use Nekudo\ShinyCore\Logger\LoggerInterface;

class DatabaseDomain extends Domain
{
    /**
     * @var DatabaseFactory $db
     */
    protected $db;

    public function __construct(Config $config, LoggerInterface $logger)
    {
        parent::__construct($config, $logger);
        $this->db = new DatabaseFactory($config);
    }
}

<?php

declare(strict_types=1);

namespace BitBag\SyliusAdyenPlugin\Logging\Monolog;

use BitBag\SyliusAdyenPlugin\Factory\LogFactoryInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;

final class DoctrineHandler extends AbstractProcessingHandler
{
    /** @var LogFactoryInterface */
    private $logFactory;

    /** @var RepositoryInterface */
    private $repository;

    /** @var RequestStack  */
    private $requestStack;

    public function __construct(
        LogFactoryInterface $logFactory,
        RepositoryInterface $repository,
        RequestStack $requestStack,
    ) {
        $this->logFactory = $logFactory;
        $this->repository = $repository;
        $this->requestStack = $requestStack;

        parent::__construct();
    }

    protected function write(array $record): void
    {
        $log = $this->logFactory->create($record['message'], $record['level'], 0, $this->addSessionToken());

        $this->repository->add($log);
    }

    private function addSessionToken(): string
    {
        try {
            $session = $this->requestStack->getSession();
        } catch (SessionNotFoundException $e) {
            $session = '';
        }
        if (!$session->isStarted()) {
            $session = '';
        }

        $sessionId = substr($session->getId(), 0, 8) ?: '????????';
        $sessionId = $sessionId . '-' . substr(uniqid('', true), -8);

        return $sessionId;
    }

}

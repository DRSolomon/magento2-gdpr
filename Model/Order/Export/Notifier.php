<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Model\Order\Export;

use Magento\Sales\Api\OrderRepositoryInterface;
use Opengento\Gdpr\Api\Data\ExportEntityInterface;
use Opengento\Gdpr\Model\Export\NotifierInterface;
use Opengento\Gdpr\Model\Order\Notifier\SenderInterface;

/**
 * Class Notifier
 */
final class Notifier implements NotifierInterface
{
    /**
     * @var \Opengento\Gdpr\Model\Order\Notifier\SenderInterface[]
     */
    private $senders;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @param \Opengento\Gdpr\Model\Order\Notifier\SenderInterface[] $senders
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        array $senders,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->senders = (static function (SenderInterface ...$senders): array {
            return $senders;
        })(...\array_values($senders));
        $this->orderRepository = $orderRepository;
    }

    /**
     * @inheritdoc
     */
    public function notify(ExportEntityInterface $exportEntity): void
    {
        $order = $this->orderRepository->get($exportEntity->getEntityId());

        foreach ($this->senders as $sender) {
            $sender->send($order);
        }
    }
}

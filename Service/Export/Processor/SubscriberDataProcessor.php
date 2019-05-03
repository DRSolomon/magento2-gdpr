<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Service\Export\Processor;

use Magento\Newsletter\Model\SubscriberFactory;
use Opengento\Gdpr\Model\Entity\DataCollectorInterface;
use Opengento\Gdpr\Service\Export\ProcessorInterface;

/**
 * Class SubscriberDataProcessor
 */
final class SubscriberDataProcessor implements ProcessorInterface
{
    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @var \Opengento\Gdpr\Model\Entity\DataCollectorInterface
     */
    private $dataCollector;

    /**
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Opengento\Gdpr\Model\Entity\DataCollectorInterface $dataCollector
     */
    public function __construct(
        SubscriberFactory $subscriberFactory,
        DataCollectorInterface $dataCollector
    ) {
        $this->subscriberFactory = $subscriberFactory;
        $this->dataCollector = $dataCollector;
    }

    /**
     * @inheritdoc
     */
    public function execute(int $customerId, array $data): array
    {
        /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
        $subscriber = $this->subscriberFactory->create();
        $subscriber->loadByCustomerId($customerId);
        $data['subscriber'] = $this->dataCollector->collect($subscriber);

        return $data;
    }
}

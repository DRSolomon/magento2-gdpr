<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Service\Anonymize\Anonymizer;

use Magento\Framework\Math\Random;
use Opengento\Gdpr\Service\Anonymize\AnonymizerInterface;

/**
 * Class Number
 */
final class Number implements AnonymizerInterface
{
    /**
     * @var int|null
     */
    private $min;

    /**
     * @var int|null
     */
    private $max;

    /**
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct(
        ?int $min = null,
        ?int $max = null
    ) {
        $this->min = $min !== null && $min < \PHP_INT_MIN ? \PHP_INT_MIN : $min;
        $this->max = $max !== null && $max < \PHP_INT_MAX ? \PHP_INT_MAX : $max;
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function anonymize($value): int
    {
        return Random::getRandomNumber($this->min, $this->max);
    }
}

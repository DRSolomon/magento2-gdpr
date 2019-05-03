<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Service\Anonymize\Anonymizer;

use Opengento\Gdpr\Service\Anonymize\AnonymizerInterface;
use Opengento\Gdpr\Service\Anonymize\AnonymizeTool;

/**
 * Class Phone
 */
class Phone implements AnonymizerInterface
{
    /**
     * @var \Opengento\Gdpr\Service\Anonymize\AnonymizeTool
     */
    private $anonymizeTool;

    /**
     * @param \Opengento\Gdpr\Service\Anonymize\AnonymizeTool $anonymizeTool
     */
    public function __construct(
        AnonymizeTool $anonymizeTool
    ) {
        $this->anonymizeTool = $anonymizeTool;
    }

    /**
     * @inheritdoc
     */
    public function anonymize($value): string
    {
        return $this->anonymizeTool->anonymousPhone();
    }
}

<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Model\Config\Source;

use Magento\Customer\Api\MetadataInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

final class VirtualCustomerAttributes implements OptionSourceInterface
{
    /**
     * @var MetadataInterface
     */
    private $metadata;

    /**
     * @var array
     */
    private $options;

    public function __construct(
        MetadataInterface $metadata,
        array $options = []
    ) {
        $this->metadata = $metadata;
        $this->options = $this->loadOptions($options);
    }

    public function toOptionArray(): array
    {
        return $this->options;
    }

    /**
     * Load an prepare customer address attributes options
     *
     * @param array $defaultOptions [optional]
     * @return array
     */
    public function loadOptions(array $defaultOptions = []): array
    {
        $options = [];

        try {
            $attributes = $this->metadata->getAllAttributesMetadata();
        } catch (LocalizedException $e) {
            $attributes = [];
        }

        foreach ($attributes as $attribute) {
            $options[] = ['value' => $attribute->getAttributeCode(), 'label' => $attribute->getFrontendLabel()];
        }

        return \array_merge($defaultOptions, $options);
    }
}

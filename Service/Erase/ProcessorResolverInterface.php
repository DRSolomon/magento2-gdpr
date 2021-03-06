<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Service\Erase;

/**
 * @api
 */
interface ProcessorResolverInterface
{
    public function resolve(string $component): ProcessorInterface;
}

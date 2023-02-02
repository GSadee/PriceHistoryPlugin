<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Sylius\PriceHistoryPlugin\Behat\Context\Api\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Client\ResponseCheckerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius1_11\Behat\Client\ApiClientInterface;

final class ManagingProductVariantsContext implements Context
{
    public function __construct(
        private ApiClientInterface $client,
        private ResponseCheckerInterface $responseChecker,
    ) {
    }

    /**
     * @When /^I set its original price to ("[^"]+") for ("[^"]+" channel)$/
     */
    public function iSetItsPriceToForChannel(int $originalPrice, ChannelInterface $channel): void
    {
        $this->client->addRequestData('channelPricings', [
            $channel->getCode() => [
                'originalPrice' => $originalPrice,
                'channelCode' => $channel->getCode(),
            ],
        ]);
    }

    /**
     * @When /^I want to modify the ("[^"]+" product variant)$/
     */
    public function iWantToModifyProductVariant(ProductVariantInterface $productVariant)
    {
        $this->client->buildUpdateRequest($productVariant->getCode());
    }

    /**
     * @When /^I change its price to ("[^"]+") for ("[^"]+" channel)$/
     */
    public function iChangeItsPriceToForChannel(int $originalPrice, ChannelInterface $channel): void
    {
        $this->client->addRequestData('channelPricings', [
            $channel->getCode() => [
                'price' => $originalPrice,
                'channelCode' => $channel->getCode(),
            ],
        ]);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges(): void
    {
        $this->responseChecker->isUpdateSuccessful($this->client->update());
    }
}

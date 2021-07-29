<?php declare(strict_types=1);

namespace Emizentech\HidePrice;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Emizentech\HidePrice\Services\EmizentechHidePriceCustomFieldSetService;

class EmizentechHidePrice extends Plugin
{
	public function install(InstallContext $installContext): void
    {
        parent::install($installContext);

        $emizentechHidePriceCustomFieldSetService = new EmizentechHidePriceCustomFieldSetService(
            $this->container->get('custom_field_set.repository')
        );

        /** @var Context $context */
        $context = $installContext->getContext();

        $emizentechHidePriceCustomFieldSetService->createProductCustomFieldSet($context);
    }

    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);
        if ($context->keepUserData()) {
            return;
        }

        $this->removeCustomFields($context);
    }

    private function removeCustomFields(UninstallContext $uninstallContext)
    {
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');

        /** @var Context $context */
        $context = $uninstallContext->getContext();

        // Delete custom field set
        $this->deleteCustomFieldSet(
            $context,
            $customFieldSetRepository,
            EmizentechHidePriceCustomFieldSetService::EMIZEN_HIDEPRICE_IS_ACTIVE_CUSTOM_FIELD_SET
        );

        $this->deleteCustomFieldSet(
            $context,
            $customFieldSetRepository,
            EmizentechHidePriceCustomFieldSetService::EMIZEN_HIDEPRICE_BUTTON_TEXT_CUSTOM_FIELD_SET
        );
    }

    private function deleteCustomFieldSet(Context $context, $customFieldSetRepository, string $customFieldSet): void
    {
        $customFieldIds = $this->getCustomFieldSetIds($context, $customFieldSet);

        if ($customFieldIds) {
            $customFieldSetRepository->delete(array_values($customFieldIds->getData()), $context);
        }
    }

    private function getCustomFieldSetIds(Context $context, string $customFieldSet): ?IdSearchResult
    {
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('name', [$customFieldSet]));

        $customFieldIds = $customFieldSetRepository->searchIds($criteria, $context);

        return $customFieldIds->getTotal() > 0 ? $customFieldIds : null;
    }
}
<?php declare(strict_types=1);

namespace Emizentech\HidePrice\Services;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\System\CustomField\CustomFieldTypes;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetCollection;

class EmizentechHidePriceCustomFieldSetService {
    //custom field set
    public const EMIZEN_HIDEPRICE_IS_ACTIVE_CUSTOM_FIELD_SET = 'emizen_hideprice_is_active_custom_field_set';
    public const EMIZEN_HIDEPRICE_BUTTON_TEXT_CUSTOM_FIELD_SET = 'emizen_hideprice_button_text_custom_field_set';

    /**
     * @var EntityRepositoryInterface
     */
    private $customFieldSetRepository;

    public function __construct(EntityRepositoryInterface $customFieldSetRepository)
    {
        $this->customFieldSetRepository = $customFieldSetRepository;
    }

    public function createProductCustomFieldSet(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::EMIZEN_HIDEPRICE_IS_ACTIVE_CUSTOM_FIELD_SET));

        /** @var CustomFieldSetCollection $customFieldSets */
        $customFieldSets = $this->customFieldSetRepository->search($criteria, $context)->getEntities();

        if (!count($customFieldSets)) {
            $this->customFieldSetRepository->create([
                [
                    'name' => self::EMIZEN_HIDEPRICE_IS_ACTIVE_CUSTOM_FIELD_SET,
                    'global' => true,
                    'config' => [
                        'label' => [
                            'de-DE' => 'Preis ausblenden ist aktiv',
                            'en-GB' => 'Hide Price Is Active'
                        ]
                    ],
                    'relations' => [[
                        'entityName' => 'product'
                    ]],
                    'customFields' => [
                        ['name' => 'emizen_hideprice_is_active', 'type' => CustomFieldTypes::BOOL]
                    ]
                ]
            ], $context);
        }


        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::EMIZEN_HIDEPRICE_BUTTON_TEXT_CUSTOM_FIELD_SET));

        /** @var CustomFieldSetCollection $customFieldSets */
        $customFieldSets = $this->customFieldSetRepository->search($criteria, $context)->getEntities();

        if (!count($customFieldSets)) {
            $this->customFieldSetRepository->create([
                [
                    'name' => self::EMIZEN_HIDEPRICE_BUTTON_TEXT_CUSTOM_FIELD_SET,
                    'global' => true,
                    'config' => [
                        'label' => [
                            'de-DE' => 'Text der Preisschaltfläche ausblenden',
                            'en-GB' => 'Hide Price Button Text'
                        ]
                    ],
                    'relations' => [[
                        'entityName' => 'product'
                    ]],
                    'customFields' => [
                        ['name' => 'emizen_hideprice_button_text', 'type' => CustomFieldTypes::TEXT]
                    ]
                ]
            ], $context);
        }
    }
}

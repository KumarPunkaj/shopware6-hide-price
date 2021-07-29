<?php declare(strict_types=1);

namespace Emizentech\HidePrice\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class ProductSubscriber implements EventSubscriberInterface
{   
    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HeaderPageletLoadedEvent::class => 'onPageLoaded',
        ];
    }

    public function onPageLoaded(HeaderPageletLoadedEvent $event): void
    {
        $active         = $this->systemConfigService->get('EmizentechHidePrice.config.active');
         $textarea         = $this->systemConfigService->get('EmizentechHidePrice.config.textarea');
          $selectconfig         = $this->systemConfigService->get('EmizentechHidePrice.config.productvariable');
        
        $event->getPagelet()->setExtensions(['EmizentechHidePriceActive'=>$active,
            'EmizentechHidePriceTextarea'=>$textarea,
            'EmizentechHidePriceSelectconfig'=>$selectconfig]);
    }
}
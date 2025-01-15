<?php

/*
Plugin Name: PPL CZ
Plugin URI: https://www.ppl.cz/jak-zacit#plugin
Author URI: https://www.ppl.cz
Description: Jednoduché vytváření zásilek pro PPL CZ s.r.o. Integrace do košíku, editace adres objednávek, stavy zásilek (zjednodušené, kompletní) a jejich sledování. Základem pluginu je tisk etiket. Pro aktivaci pluginu, kontaktujte ithelp@ppl. Určeno pro WooCommerce verze 8.0 a vyšší.
Author: PPL
Version: 1.0.6
Requires Plugins: woocommerce
License: GPLv2 or later
Requires PHP: 7.3
*/

defined("WPINC") or die();

require_once __DIR__ . '/build/vendor/autoload.php';
require_once __DIR__ . '/src/globals.php';

PPLCZ\Front\Components\ParcelShop\BlockData::register();
PPLCZ\Front\Components\ParcelShopSummary\BlockData::register();

PPLCZ\Front\Components\Map\Map::register();

PPLCZ\Admin\Page\OptionPage::register();
PPLCZ\Admin\RestController\SettingV1RestController::register();
PPLCZ\Admin\RestController\ShipmentV1RestController::register();
PPLCZ\Admin\RestController\SettingV1RestController::register();
PPLCZ\Admin\RestController\CodelistV1RestController::register();
PPLCZ\Admin\RestController\CollectionV1Controller::register();
PPLCZ\Admin\RestController\ShipmentBatchV1Controller::register();
PPLCZ\Admin\Cron\ShipmentPhaseCron::register();
PPLCZ\Admin\Cron\RefreshAboutCron::register();

function pplcz_init()
{

    if (!WC()->session) {
        WC()->initialize_session();
    }
    PPLCZ\Template\Template::register();

    if (class_exists(\Automattic\WooCommerce\Blocks\BlockTypes\OrderConfirmation\ShippingAddress::class))
    {
        PPLCZ\Front\Components\ParcelShopSummary\BlockOrderConfirmation::register();
    }


    PPLCZ\ShipmentMethod::register();

    PPLCZ\Front\Components\ParcelShop\BlockOldView::register();

    PPLCZ\Admin\Product\Tab::register();
    PPLCZ\Front\Validator\ParcelShopValidator::register();


    PPLCZ\Front\Operation\ParcelShopOperation::register();
    PPLCZ\Admin\Order\ParcelShop::register();
    PPLCZ\Admin\Order\OrderFilter::register();
    PPLCZ\Admin\Order\OrderPanel::register();
    PPLCZ\Admin\Order\OrderTable::register();
    PPLCZ\Admin\Category\CategoryPanel::register();

    PPLCZ\Admin\FileDownload::register();

    PPLCZ\Data\CollectionDataStore::register();
    PPLCZ\Data\ShipmentDataStore::register();
    PPLCZ\Data\AddressDataStore::register();
    PPLCZ\Data\CodBankAccountDataStore::register();
    PPLCZ\Data\PackageDataStore::register();
    PPLCZ\Data\ParcelDataStore::register();

}

add_action("before_woocommerce_init", "pplcz_init");
add_action("woocommerce_init", "pplcz_tables");

register_activation_hook(__FILE__, "pplcz_activate");
register_deactivation_hook(__FILE__, "pplcz_deactivate");

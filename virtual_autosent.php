<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Virtual_autosent extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'virtual_autosent';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Alan Reibel';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('virtual products autosent');
        $this->description = $this->l('Cambia el estado de los productos virtuales a enviado cuando el pago es aceptado');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() 
        && $this->registerHook('orderConfirmation');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookOrderConfirmation($params)
    {
        $order = $params["order"];
        $new_state = "4";
        $virtual = $order->isVirtual();//1
        $current_state = $params["order"]->current_state;
        if($current_state == "2")//pago aceptado
        {
            if($virtual)
            {
                $order->setCurrentState($new_state);
            }
        }
    }
}

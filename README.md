# Magento 2 Layered Navigation

For an e-commerce website, Product navigation that simplifies the customer search process is essential. If a customer has trouble finding the product, they will abandon the shopping cart and you will lose a lead. 

Magento Layered Navigation extension by Alothemes integrated Axaj technology that helps customers flexibly and easily navigate products without reloading pages. This extension allows customers to find the products they need exactly, quickly and helping to increase customer experience in your Magento store.

## What is a Magento 2 Layered Navigation?
Basically, Layered Navigation extension is a feature that makes it easy to find products based on category, price range, or any other available attribute. You can customize the product attributes, color and size to match your store. This extension equips your website with very flexible vertical and horizontal navigation, provides a richer user-experience for your customer and is particularly well-suited for stores that have a lot of variable products or products with a number of attributes.

In the process of researching customer behavior on the Magento website, Alothemes has upgraded the layered navigation utility with many outstanding features:
- Fully Responsive & Mobile Optimized
- Integrated Ajax to quickly filter products without reloading pages.
- Show price attribute slider
- Professional and user-friendly interface
- Simplify the customer search process
- Sticky Sidebar helps the menu bar to remain steady when scrolling the product
- Multil select in one filler lelp customers find products quickly & accurately

## How to install Layered Navigation for Magento
Before you continue, ensure you meet the following requirements:
  - You have installed magento2
  - You are using a Linux or Mac OS machine. Windows is not currently supported.
  Install magento2-instagram extension
### Step 1 : Download Magento 2 Layered Navigation Extension

 #### Install via composer (recommend)
Run the following commands in Magento 2 root folder:
```
composer require magepow/layerednav
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
```
  #### Install manual
  
* extract file from archive
* deploy file into Magento2 folder `app/code/Magepow/Layerednav`. Run following commands

 ```
 php bin/magento setup:upgrade
 php bin/magento setup:static-content:deploy -f
 php bin/magento cache:flush
 ```

### Step 2: User guide

  #### 1. General configuration

  Login to magento admin, choose `stores->configuration->magepow->layered ajax`
  
  ![Image of magento admin config](https://github.com/magepow/magento2-layerednav/blob/master/media/config_backend.PNG)

  Select `yes` to enable the module
  
  #### 2. Details Configuration
  
   In `stores->configuration->magepow->layered ajax` we set: 
   * Use range price slider : Select `yes` to enable the price range and `no` to turn to the price of magento default.
   * Show all filters : Select `yes` to show the filter of all the options and `no` to disable the feature
   
   Run the following command:
   
   ```
   php bin/magento cache:clean
   ```
  #### 3. Result
   
   ![Image of magento store front](https://github.com/magepow/magento2-layerednav/blob/master/media/result_frontend.PNG)
   
 ## Donation

If this project help you reduce time to develop, you can give me a cup of coffee :) 

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/paypalme/alopay)

      
**Free Extensions List**

* [Magento 2 Categories Extension](https://magepow.com/magento-categories-extension.html)

* [Magento 2 Sticky Cart](https://magepow.com/magento-sticky-cart.html)

**Premium Extensions List**

* [Magento 2 Pages Speed Optimizer](https://magepow.com/magento2-speed-optimizer.html)

* [Magento 2 Mutil Translate](https://magepow.com/magento-multi-translate.html)

* [Magento 2 Instagram Integration](https://magepow.com/magento-2-instagram.html)

* [Magento 2 Lookbook Pin Products](https://magepow.com/lookbook-pin-products.html)

**Featured Magento Themes**

* [Expert Multipurpose responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/expert-premium-responsive-magento-2-and-1-support-rtl-magento-2-/21667789)

* [Gecko Premium responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/gecko-responsive-magento-2-theme-rtl-supported/24677410)

* [Milano Fashion responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/milano-fashion-responsive-magento-1-2-theme/12141971)

* [Electro responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/electro-responsive-magento-1-2-theme/17042067)

* [Pizzaro Food responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/pizzaro-food-responsive-magento-1-2-theme/19438157)

* [Biolife Organic responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/biolife-organic-food-magento-2-theme-rtl-supported/25712510)

* [Market responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/market-responsive-magento-2-theme/22997928)

* [Kuteshop responsive Magento 2 Theme](https://1.envato.market/c/1314680/275988/4415?u=https://themeforest.net/item/kuteshop-multipurpose-responsive-magento-1-2-theme/12985435)

**Featured Magento Services**

* [PSD to Magento 2 Theme Conversion](https://magepow.com/psd-to-magento-theme-conversion.html)

* [Magento Speed Optimization Service](https://magepow.com/magento-speed-optimization-service.html)

* [Magento Security Patch Installation](https://magepow.com/magento-security-patch-installation.html)

* [Magento Website Maintenance Service](https://magepow.com/website-maintenance-service.html)

* [Magento Professional Installation Service](https://magepow.com/professional-installation-service.html)

* [Magento Upgrade Service](https://magepow.com/magento-upgrade-service.html)

* [Customization Service](https://magepow.com/customization-service.html)

* [Hire Magento Developer](https://magepow.com/hire-magento-developer.html)

[![Latest Stable Version](https://poser.pugx.org/magepow/layerednav/v/stable)](https://packagist.org/packages/magepow/layerednav)
[![Total Downloads](https://poser.pugx.org/magepow/layerednav/downloads)](https://packagist.org/packages/magepow/layerednav)

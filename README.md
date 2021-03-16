# Magento 2 Currency Formatter Extension Free

[Mageplaza Currency Formatter extension](http://www.mageplaza.com/magento-2-currency-formatter/) brings flexibility in formatting the price currency appearance in Magento stores. With the support of Currency Formatter, customers from multi-nations will feel familiar with the price format and avoid risks of misunderstanding. 

## 1. Documentation
- [Installation guide](https://www.mageplaza.com/install-magento-2-extension/)
- [User guide](https://docs.mageplaza.com/currency-formatter/index.html)
- [Introduction page](http://www.mageplaza.com/magento-2-currency-formatter/)
- [Contribute on Github](https://github.com/mageplaza/magento-2-currency-formatter)
- [Get Support](https://github.com/mageplaza/magento-2-currency-formatter/issues)


## 2. FAQ

- **Q: I got an error: Mageplaza_Core has been already defined**

A: Read solution [here](https://github.com/mageplaza/module-core/issues/3)

- **Q: Can I change the position of currency symbol with Currency Formatter?**

A: Yes, definitely. There are different choices to place currency symbol including Before value, Before value with space, After value, After value with space, None (not show symbol) 

- **Q: Can I change the sign of thousands separator to “dot”?** 

A: Yes. You can change easily from “comma” to “dot” via the backend configuration. 

- **Q: How can I modify the decimal part format?**

A: At the admin backend, at Decimal Separator section, you can select sign as dot or comma. Then, at the Decimal Digit(s) section, you can select the number of separator digits from 0 to 4. 

- **Q: Can I preview before applying the change?** 

A: Yes, you can preview each modification at the backend easily with an example. 

## 3. How to install Currency Formatter extension for Magento 2

### Install via composer (recommend)

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-currency-formatter
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## 4. Highlight Features 

### Improve all available currencies at store sites

![Improve all available currencies at store sites](https://i.imgur.com/w2thhyU.png)

Currency Formatter extension improves performance of all available currencies are used at the store site. All prices with at any currencies will be displayed more clearly to customers and in a friendly way for the currency standards of each nation in the world.   

### Specify and place symbol of currency 

Currency Formatter allows assigning any symbols for the price currencies at store. Store owners can flexibly use signs or abbreviations as currency symbols, for example, $ or USD for the US dollar. 

![Specify and place symbol of currency](https://i.imgur.com/YJoW7Y4.png)

More importantly, there are 5 positions for currency symbol correlated with price value including: 

- Before value: The symbol in front of value without space. E.g, $100
- Before value with space: The symbol in front of value with space. E.g, $ 100
- After value: The symbol behind value without space. E.g, 100$
- After value with space: The symbol behind value with space. E.g, 100 $ 
- None: In case you do not want to show the currency symbol


### Easy to modify the thousands separator

![Easy to modify the thousands separator](https://i.imgur.com/VYUJTYH.png)

To make the price value more clear, using the thousands separator is necessary to separate the thousands groups. Depending on the custom of each nation, the sign of separator differs. Likewise, while the U.K. and U.S. use a comma to separate groups of thousands, many other countries use a dot instead, and some countries separate thousands groups with a thin space. 

Understanding this difference, Currency Formatter offers 4 types of separators to meet any nations’ numeric format standards including: 

- Space: a thin space between thousands groups, e.g, 12 467 
- Dot: a small dot between thousands groups, e.g, 12.467
- Comma: a small comma between thousands groups, e.g, 12,467
- None: no separation between thousands groups, e.g, 12467

### Define sign and digit for decimal separator 

A decimal separator is a symbol used to separate the integer part from the fractional part of a number written in decimal form. Different countries officially designate different symbols for the decimal separator. With Currency Formatter, the sign of decimal can be set as a comma or a dot flexibly to match with the number standard in each nation.

![ Define sign and digit for decimal separator ](https://i.imgur.com/8lF2q3N.png)

Besides. the number of decimals can be set up easily at the admin backend. Store owners can display the accuracy levels of price value by adding specific decimal numbers after the integer part. For example, $15.1 for decimal number as 1, $15.11 for decimal number as 2

### Multi-positions of the minus sign

![Multi-positions of the minus sign](https://i.imgur.com/gAGANQj.png)

In case you would like to display the discount, the minus sign is used. With Currency Formatter offers various options for the position of the minus sign. At the admin backend, you can place the minus sign flexibly: 
- Before Value
- After Value
- Before Symbol
- After Symbol


## 5. More Features 

### Preview currency format

There is an example for each currency at the backend. With any change in setting, store admins can preview via this example easily. 

### Support multiple store views

Currency Formatter is well supported in multiple store views

### Support both frontend and backend 

New currency formats are applied both at the backend and on the storefront. 


## 6. Full Features List


### For store admins

- Enable/ Disable the extension 
- Add the symbol for a currency 
- Set the position for the currency symbol 
- Add the sign for the group separator
- Add the digits for decimal value 
- Add the sign for decimal value
- Add the sign for minus value
- Add the position for the minus sign 
- Preview the changes via the backend example 

### For customers

- Price format is friendly to nation practice and standards
- Avoid misunderstanding price due to unfamiliar format 


## 7. User Guide

### How to use

Admin can configure currency format at backend. At Magento Admin, Select `Stores > Settings > Configuration > Mageplaza > Currency Formatter`

![](https://i.imgur.com/HLjr7kf.png)

Currency displayed on the frontend and backend such as product price, the total amount of the order will be displayed in the correct format:

![](https://i.imgur.com/1lsbwwR.png)

### How to configure

Login to Magento Admin, Select `Stores > Settings > Configuration > Mageplaza > Currency Formatter`

![](https://i.imgur.com/oSNg634.png)

#### 1. General

![](https://i.imgur.com/nMhTLWo.png)

- **Enable**: Select **Yes** to enable the module.

##### 1.1. Currency Configuration:

Configure the currency format for all currency enabled by the store

![](https://i.imgur.com/dswJCeC.png)

##### 1.1.1 Currencies:
- Displays the name of the currency enabled by the store
- **Use System** (For **Store View: Default Config**):

  ![](https://i.imgur.com/J3muom0.png)
  - Click to **Use System** checkbox, template is auto loaded using premade template of system.

- **Use Default** ( For **Store View: Website**):

  ![](https://i.imgur.com/QB3H76n.png)
  - Click to **Use Default** checkbox, template is auto loaded using default template at Configuration.
  
- **Use Website** (For **Store View: store view**): 

  ![](https://i.imgur.com/8oWtvmR.png)
  - Click to **Use Website** checkbox, template is auto loaded using website template

##### 1.1.2 Template:

- **Show symbol**: Select the display position for the currency symbol. There are 5 options: **Before value with space options, Before value, After value with space, After value and None**:
  
  ![](https://i.imgur.com/ODbN10i.png)
  - **Before value with space**: The currency symbol displays before the numeric value. There is a space between symbol and the numerical value. For example: $ 100
  - **Before value**: Currency symbol displayed before numeric value, but between symbol and numeric value there is no space. For example: $100
  - **After value with space**: A currency symbol displayed behind a numeric value. There is a space between symbol and the numerical value. Example: 100 $
  - **After value**: The currency symbol displayed behind the numeric value, but between symbol and numeric value there is no space. Example: 100$
  - **None**: Do not display the currency symbol: Example: 100
  
- **Symbol**: Select the currency symbol. For example, if you enter Symbol = $, the currency symbol in currency format is $ which is displayed at the price of the product is $ 100.

- **Thousands Separator**: Choose a way to display the thousandth value separator.
  
  ![](https://i.imgur.com/nLDiGSu.png)
  - **Dot (.)**: The thousands value is separated by dots, for example: 1.000
  - **Comma (,)**: The thousandth value is separated by commas, for example: 1,000
  - **Space ( )**: The thousandth value is separated by spaces, for example: 1 000
  - **None**: Do not separate thousands of values, for example: 1000

- **Decimal Digit(s)**: Select the number of decimal value after the unit value. You can choose from 0 to 4 decimal numbers.

For example: Decimal Digit(s) = 2, there will be 2 decimal numbers. The product price as $ 100.00

![](https://i.imgur.com/wypp291.png)

- **Decimal separator**: Select a symbol to separate the integral and decimal value.
  
  ![](https://i.imgur.com/cTOSFoR.png)
  - **Dot (.)**: The integral and decimals are separated by dots, for example: 100.1
  - **Comma (,)**: The integer and decimal are separated by commas, for example: 100,1

- **Minus Sign**: You can enter any character to represent discount value.

- **Show minus sign**: Select the display location of Minus Sign which include:

  ![](https://i.imgur.com/tX6ghfO.png)
  - **Before value**: minus sign displays before the number. For example: -100
  - **After value**: minus sign displayed after the number. Example: $ 100-
  - **Before symbol**: minus sign displayed before the currency symbol. For example: -$ 100
  - **After symbol**: minus sign displayed after the currency symbol. Example: $ 100-
  
##### 1.1.3. Preview:

Preview the format of the currency configured in the template section

![](https://i.imgur.com/RnuIWvF.png)

When **Template** field changes, the currency format is shown in **Preview** also changes




**Other Mageplaza extensions on Magento Marketplace, Github**


☞ [Magento 2 One Step Checkout extension](https://marketplace.magento.com/mageplaza-magento-2-one-step-checkout-extension.html)

☞ [Magento 2 SEO extension](https://marketplace.magento.com/mageplaza-magento-2-seo-extension.html)

☞ [Magento 2 Reward Points](https://marketplace.magento.com/mageplaza-module-reward-points.html)

☞ [Magento 2 Blog extension](https://marketplace.magento.com/mageplaza-magento-2-blog-extension.html)

☞ [Magento 2 Layered Navigation extension](https://marketplace.magento.com/mageplaza-layered-navigation-m2.html)

☞ [Magento 2 Google Tag Manager Enhanced eCommerce](https://www.mageplaza.com/magento-2-google-tag-manager/)

☞ [Magento 2 Social Login on Github](https://github.com/mageplaza/magento-2-social-login)

☞ [Magento 2 SEO on Github](https://github.com/mageplaza/magento-2-seo)

☞ [Magento 2 SMTP on Github](https://github.com/mageplaza/magento-2-smtp)

☞ [Magento 2 Product Slider on Github](https://github.com/mageplaza/magento-2-product-slider)

☞ [Magento 2 Banner on Github](https://github.com/mageplaza/magento-2-banner-slider)

☞ [Magento 2 GDPR](https://marketplace.magento.com/mageplaza-module-gdpr.html)


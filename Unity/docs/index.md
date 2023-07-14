# web-vision Mage UniTY for Magento 2
The Mage UniTY extension, developed by web-vision, allows seamless integration of the TYPO3 content management system with an existing Magento shop.
This integration enhances search service optimization, improves visitor experience, and facilitates professional content marketing for your Magento store.

## Installation in your Magento Online Shop
*  **Note** : in production please use the `--keep-generated` option

### Step 1 : Setup TYPO3 and install UniTY for typo3

### Step 2 : Setup Mage UniTY for Magento.

###  Step 2.1 : Zip file
 - Unzip the zip file in `app/code/WebVision`
 - Enable the module by running `php bin/magento module:enable WebVision_Unity`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Step 2.2 : USER GUIDE

### Step 2.2.1 : CONFIGURATION
* You will find all settings in **Admin > Stores > Settings > Configuration > web-vision > web-vision UniTy.**

![alt_text](docs/images/2.2.1.1.png "image_tooltip")

![alt_text](docs/images/2.2.1.2.png "image_tooltip")

* Here Magento configuration is divided into 5 Setps:

    1. General Settings
    2. Developer
    3. Magento side settings
    4. TYPO3 side settings
    5. Mode specific settings

### Setp 1. - General Settings
![alt_text](docs/images/step1.png "image_tooltip")

- Enabled: Activate or deactivate the extension globally.

### Setp 2. - Developer
![alt_text](docs/images/step2.1.png "image_tooltip")
- Display errors: Set how errors are displayed.
  - As HTML Code: Error message is shown directly in the frontend.
  - As HTML Comment: Error message is hidden in HTML comments in the frontend.
  - As Log file: Error messages are written to the Magento exception log.
  - no_cache for TYPO3: If enabled, the parameter "no_cache=1" is used instead of the cHash parameter.
  - cUrl timeout: Set the timeout for the CURL request to TYPO3 in seconds.
  - XDebug TYPO3: If disabled, the XDebug cookie is not passed to TYPO3 to avoid slowing down the CURL request.

### Setp 3. - Magento side settings
![alt_text](docs/images/step3.png "image_tooltip")
- Widget cache lifetime: Set the cache lifetime for webvision_unity/t3block and webvision_unity/t3menu widgets. If empty, the block is cached until the cache is cleared.
- Page Cache Lifetime: Set the cache lifetime for pure TYPO3 pages displayed in Magento. If empty, the page is cached until the block cache is cleared.

### Setp 4. - TYPO3 side settings

- **Setp 4.1 Database**:
![alt_text](docs/images/step4.1.png "image_tooltip")
  - Database Host: The host for the TYPO3 database connection.
  - Database Username: The username for the TYPO3 database connection.
  - Database Password: The password for the TYPO3 database connection.
  - Database Name: The name of the TYPO3 database.

- **Setp 4.2 Url parts**:
![alt_text](docs/images/step4.2.png "image_tooltip")
  - Protocol: The protocol for the CURL request.
      - HTTP only: Always use "http://".
      - HTTPS only: Always use "https://".
      - Currently Used: Use the current URL's protocol (either "http://" or "https://").
  - Domain: The domain of the TYPO3 instance.
      - Same as Magento: Use the same domain as Magento.
      - Own Domain: TYPO3 has its own domain.
  - The TYPO3 domain: The domain of the TYPO3 instance if it differs from Magento.
  - Subfolder: Used when TYPO3 is in a subfolder with the same domain as Magento.
  - Use Subpage: Set to Yes if Magento content is on a subpage.
      - Url path prefix: The prefix placed in front of the actual path for Magento content on a subpage.
  - encryptionKey: The encryptionKey from LocalConfiguration.php for generating the cHash.

- **Setp 4.3 PageTypes**:
![alt_text](docs/images/step4.3.png "image_tooltip")
  - PageType for mode head: The value for the type parameter when querying head data.
  - PageType for mode page: The value for the type parameter when querying a page.
  - PageType for mode column: The value for the type parameter when querying a column.
  - PageType for mode menu: The value for the type parameter when querying a content element.
  - PageType for mode xmlsitemap: The value for the type parameter when querying the XML sitemap.

- **Setp 4.4 Additional**:
![alt_text](docs/images/step4.4.png "image_tooltip")
  - TYPO3 rootpage ID: The UID of the TYPO3 rootpage where the static typoscript of wv_t3unity is stored.
  - Multilanguage: Enable multilingualism.
  - config.linkVar: The get parameter for the language (usually "L").
  - Default Language ID: The sys_language_uid of the default language.
  - Language ID for this page: The sys_language_uid for the current Magento language.
  - Use credentials: Set to Yes if the TYPO3 page is protected by a .htpasswd.
  - Credential username: The username for the .htpasswd query.
  - Credential password: The password for the .htpasswd query.
  - Send files: Choose whether to pass files sent via forms to TYPO3.
  - Extensions:
    - Realurl version: Set the realurl version of the TYPO3 instance.

### Setp 5. - Mode specific settings
![alt_text](docs/images/step5.png "image_tooltip")
- Head Mode:
  - Whitelist: Regular expressions for allowed paths.
  - Blacklist: Regular expressions for disallowed paths.
  - Order of Whitelist/Blacklist: Choose whether to process the blacklist or whitelist first.


### Step 2.2.2 : ADD WIDGETS
**- Step 1 : Go to Admin > Content > Widget**

![alt_text](docs/images/step2.2_1.png "image_tooltip")

- Click on the "Add Widget" button. Choose the type of widget you want to create from the available options (TYPO3 Content Block,TYPO3 Menu). Select the widget type that suits your requirements and click on the "Continue" button.

**- Step 2 : Widget Storefront Properties And Layout Updates**
![alt_text](docs/images/widgets_1.png "image_tooltip")
- Configure the widget settings by providing the necessary information such as Widget Title, Storefront Properties, Layout Updates, and Widget Options. The options will vary depending on the widget type you selected.
- Customize the appearance and behavior of the widget using the available settings. These settings may include selecting a specific CMS block or product list, setting display conditions, specifying the number of items to show, etc.

**- Step 3 : Widget Options**
![alt_text](docs/images/widgets_2.png "image_tooltip")
- Options:
    - Display Modus : There are mnay option here. You can use Render Page for render page form typo3.
    - Page ID : You can use specific typo3 page id.
    - Additional classes for wrapper : You can assign the class for wrapping connetn in this class.


### Step 2.2.3 : Enhance your content design by incorporating custom CSS styles.
1. Go to the Admin > "Content" menu and select "Configuration" under the "Design" section.
2. In the "Configuration" page, locate the specific theme you want to add CSS to and click on the corresponding "Edit" button.
3. Scroll down to the "HTML Head" section in the "Theme Configuration" editor.
4. Add your custom CSS code by wrapping it inside `<style>` tags, such as:

![alt_text](docs/images/frontend1.png "image_tooltip")

   ```html
   <style>
      /* Your custom CSS code goes here */
      body {
         background-color: #f5f5f5;
      }
   </style>
   frontend1.
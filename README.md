# yii2-pages
Yii2 module for controlling static pages via CRUD. The module can used both separately and as part
of the [execut/yii2-cms](https://github.com/execut/yii2-cms).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

### Install

Either run

```
$ php composer.phar require execut/yii2-pages
```

or add

```
"execut/yii2-pages": "@dev"
```

to the ```require``` section of your `composer.json` file.

### Configuration

Add module bootstrap to backend application config:
```php
    'bootstrap' => [
    ...
        'pages' => [
            'class' => \execut\pages\bootstrap\Backend::class,
        ],
    ...
    ],
```

Add module bootstrap to common application config:
```php
    'bootstrap' => [
    ...
        'pages' => [
            'class' => \execut\pages\bootstrap\Common::class,
        ],
    ...
    ],
```

Add module bootstrap inside console application config:
```php
    'bootstrap' => [
    ...
        'pages' => [
            'class' => \execut\pages\bootstrap\Console::class,
        ],
    ...
    ],
```

Apply migrations via yii command:
```
./yii migrate/up --interactive=0
```

After configuration, the module should open by paths:
pages/backend

### Module backend navigation

You may output navigation of module inside your layout via execut/yii2-navigation:
```php
    echo Nav::widget([
        ...
        'items' => \yii\helpers\ArrayHelper::merge($menuItems, \yii::$app->navigation->getMenuItems()),
        ...
    ]);
    NavBar::end();

    // Before standard breadcrumbs render breadcrumbs and header widget:
echo \execut\navigation\widgets\Breadcrumbs::widget();
echo \execut\navigation\widgets\Header::widget();
echo Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]);
```
For more information about execut/yii2-navigation module, please read it [documentation](https://github.com/execut/yii2-navigation)

### Usage
#### Administration

![Pages list](https://raw.githubusercontent.com/execut/yii2-pages/master/docs/list.jpg)

Section contains the following columns:

Name|Description
----|-----------
Name | Page name
Pages Page | Parent page

![Pages list](https://raw.githubusercontent.com/execut/yii2-pages/master/docs/form.jpg)

On frontend page is available by parameter ?id=# on main page or /pages/frontend?id=#, where # - database page identify

#### Increasing functionality

The module has poor functionality. For adding more functionality inside module you can connect to module plugin or create it. Plugins based on interface execut\pages\Plugin


Already available plugins sorted by priority:

Name|Required module|Functionality
----|---------------|-------------
Alias|[execut/yii2-alias](http://github.com/execut/yii2-alias)|Attach to every page own alias for adding humanize urls
Seo|[execut/yii2-seo](http://github.com/execut/yii2-seo)|Editor and seo metaTags inside backend. Rendering text and metaTags on frontend.
Menu|[execut/yii2-menus](http://github.com/execut/yii2-menus)|Add helpers to pages menus
Settings|[execut/yii2-settings](http://github.com/execut/yii2-settings)|Customization exception pages like 500 error via settings module.
Sitemap|[execut/yii2-sitemap](http://github.com/execut/yii2-sitemap)|Added checkbox for detect pages needed for rendering into sitemap.xml inside sitemap module
Files|[execut/yii2-files](http://github.com/execut/yii2-files)|Attach image to every page
Goods|[execut/yii2-goods](http://github.com/execut/yii2-goods)|Attach good to every page for render it before page text

After selecting the necessary plugins, connect them as follows to module via common bootstrap depends config:
```php
    'bootstrap' => [
    ...
        'settings' => [
            'class' => \execut\settings\bootstrap\Common::class,
            'depends' => [
                'modules' => [
                    'settings' => [
                        'plugins' => [
                            'own-plugin' => [
                                'class' => $pluginClass // You plugin class here
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ...
    ],
```

# Codigo Wordpress Theme
A clean slate Wordpress theme template with Bootstrap(5), Sass, and a Webpack config with Babel.
Based in [understrap.com](https://understrap.com) and in [Codigo Theme](https://github.com/pablorica/wordpress_codigo_theme)

### Understrap

Website: [understrap.com](https://understrap.com)
Parent Theme Project: [github.com/understrap/understrap](https://github.com/understrap/understrap)
Premium Child Themes: [understrap.com/child-themes/](https://understrap.com/child-themes/)
Understrap customised version [Pablo Rica Understrap Theme](https://github.com/pablorica/understrap-academy)


## Basic Features

-   Combines Underscore’s PHP/JS files and Bootstrap’s HTML/CSS/JS.
-   Comes with Bootstrap v5 Sass source files and additional .scss files. Nicely sorted and ready to add your own variables and customize the Bootstrap variables.
-   Uses sass and postCSS to handle compiling all of the styles into one style sheet. The theme also includes rollup.js to handle javascript compilation and minification.
-   Uses a single minified CSS file for all the basic stuff.
-   [Font Awesome](http://fortawesome.github.io/Font-Awesome/) integration (v4.7.0)
-   Jetpack ready
-   WooCommerce support
-   Contact Form 7 support
-   Translation ready


## Installation

Understrap uses [npm](https://www.npmjs.com/) as manager for dependency packages like Bootstrap and Underscores. And it uses tools like [rollup.js](https://www.rollupjs.org/) and [postCSS](https://postcss.org) as taskrunners to compile .scss code into .css, minify .js code, etc. The following chapter describes the usage and workflow.

### [Preparations: Install node.js](https://docs.understrap.com/#/understrap-child/npm?id=preparations-install-nodejs)

At first you need node.js installed on your computer globally. If you already done this before skip this section. If not: You have to install node.js (comes along with npm) before you can proceed. To install node.js visit the node.js website for the latest installer for your OS. Download and install it like any other program, too.

We also recommend using [NVM - Node Version Manager](https://github.com/nvm-sh/nvm) to keep closer control over your version of node.js and switch between versions.

### [Installing dependencies](https://docs.understrap.com/#/understrap-child/npm?id=installing-dependencies)

-   Make sure you have installed Node.js and Browser-Sync* (* optional, if you wanna use it) on your computer globally
-   Then open your terminal and browse to the location of your Understrap copy
-   Run: `npm install`

#### Engine

There is an `engines` option in `package.json`. It set requirements for node and npm versions:

```json
"engines": {
    "npm" : ">=7.0.5",
    "node": ">=14"
}
```

To enforce this via npm you can create a   `.npmrc` file where sthe `engines-strict` option is set to true, which will cause npm commands such as npm install to fail if the required engine versions to not match:
##### .npmrc

```javascript
engine-strict=true
```

### [Running](https://docs.understrap.com/#/understrap-child/npm?id=running)

To work and compile your Sass files on the fly start:

```
npm run watch
```

Or, to run with Browser-Sync:

First change the browser-sync options to reflect your environment in the file `/src/build/browser-sync.config.js` in the beginning of the file:

```
module.exports = {
    "proxy": "localhost/", // Change here
    "notify": false,
    "files": ["./css/*.min.css", "./js/*.min.js", "./**/*.php"]
};
```

Replace `localhost/theme_test/` with the URL to your local WordPress test environment. For example if you run MAMP and your WordPress installation is placed in a htdocs subfolder called `/understrap` you have to add `localhost:8888/understrap`. Its the same URL you have to type in to see your local WordPress installation. Then run:

```
npm run watch-bs
```
## Code quality setup and GIT Hooks
* Pre commit Hook: Compposer actions added. 

There are some PHP tools for the Code quality setup to be intalled using composer

-   PHP_CodeSniffer 
-   PHPStan
-   PHP Mess Detector (PHPMD)
-   PHP Parallel Lint

Run `composer install` to install them. These are the scripts to run them:

```bash
composer run php-lint
composer run phpcs
composer run phpmd
composer run phpstan
```


In order to run all of them before each commit, we can use the .git/hooks/pre-commit file and pase this code:

```bash
#! /usr/bin/env bash
PROJECT=`php -r "echo dirname(dirname(dirname(realpath('$0'))));"`
STAGED_FILES_CMD=`git diff --cached --name-only --diff-filter=ACMR HEAD | grep \\\\.php`

# Determine if a file list is passed
if [ "$#" -eq 1 ]
then
  oIFS=$IFS
  IFS='
  '
  SFILES="$1"
  IFS=$oIFS
fi
SFILES=${SFILES:-$STAGED_FILES_CMD}

# cd wp-content/themes/codigo
# pwd
# php -v

for FILE in $SFILES
do
  echo "Checking $FILE"
  FILES="$FILES $PROJECT/$FILE"
done

if [ "$FILES" != "" ]
then
  echo "Running PHP Lint..."
  /usr/local/bin/composer run php-lint
  if [ $? != 0 ]
  then
    echo "Fix the error before commit."
    exit 1
  fi
fi

if [ "$FILES" != "" ]
then
  echo "Running Code Sniffer..."
  /usr/local/bin/composer run phpcs 
  if [ $? != 0 ]
  then
    echo "Fix the error before commit."
    exit 1
  fi
fi

if [ "$FILES" != "" ]
then
  echo "Running PHPMD..."
  /usr/local/bin/composer run phpmd
  if [ $? != 0 ]
  then
    echo "Fix the error before commit."
    exit 1
  fi
fi

if [ "$FILES" != "" ]
then
  echo "Running PHPStan..."
  /usr/local/bin/composer run phpstan
  if [ $? != 0 ]
  then
    echo "Fix the error before commit."
    exit 1
  fi
fi

exit $?
```

Don't forget to give execution permission to this file

```bash
chmod +x prepare-commit-msg
```

## Required Plugins
* [Carousel Slider Block for Gutenberg](https://en-gb.wordpress.org/plugins/carousel-block/)
* [Page Builder Gutenberg Blocks – CoBlocks](https://en-gb.wordpress.org/plugins/coblocks/)
* [Advanced Custom Fields](https://www.advancedcustomfields.com/pro/)


### Debug and Development Plugins
* [FakerPress](https://wordpress.org/plugins/fakerpress/)
* [Custom Post Type UI](https://en-gb.wordpress.org/plugins/custom-post-type-ui/)
* [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/)
* [Query Monitor](https://en-gb.wordpress.org/plugins/query-monitor/)
* [Default Admin Color Scheme](https://en-gb.wordpress.org/plugins/default-admin-color-scheme/)

### Recommended Plugins
* [Yoast Duplicate Post](https://en-gb.wordpress.org/plugins/duplicate-post/)
* [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/)


## Acknowledgements

* [HTML 5 Blank](https://github.com/toddmotto/html5blank)

## Copyright and License

Copyright 2023 Codigo Wordpress Theme released under the [MIT](https://github.com/pablorica/citysuburban/blob/main/LICENSE) license.

## Versioning

We use [SemVer](https://semver.org/) for versioning. For the versions available, [list of tags can be found in this page](https://github.com/pablorica/wordpress_codigo_theme/tags).

### Changelog
    3.1.6
        Adding CMS colors

    3.1.2
        Update heading styles

    3.1.1
        Add widget area

    3.1.0
        Add ACF Google Map block

    3.0.15
        Setting up color palette
        Adding bootstrap SCSS

    3.0.10
        Setting up VUE

    3.0.0
        Theme updated to Boostrap 5 
        PHP Code quality setup tools added

    2.2
        REST API filters
        Floating parallax elements

    2.1.2
        Adding Counter Block

    2.1.1
        Adding Instagram block

    2.1.0
        Adding VUE files

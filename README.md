# Codigo Theme
A clean slate Wordpress theme template with Bootstrap(4), Sass, and a Webpack config with Babel.
Based in [wp-bootstrap4-sass](https://github.com/tone4hook/wp-bootstrap4-sass)

## Installation

* Using command line(Terminal or Command Prompt) navigate to seadesign directory in your themes folder
* *npm update --save-dev*  to update all devDependencies
* Run *npm update*
* Run *npm start* for Webpack development build
* Run *npm run build* for Webpack production build

* Run *npm run watch* for Webpack development watch

### Import Data

* Import `_export_files/Export.File.xml`  
* Import `_export_files/acf-export.json` 

## Dev Dependencies

*Note:* Using the extract-text-webpack-plugin Beta version since it plays better with Webpack 4; *npm i -D extract-text-webpack-plugin@next*

* [Babel](https://babeljs.io/)
* [babel-loader](https://github.com/babel/babel-loader)
* [babel-preset-env](https://github.com/babel/babel/tree/master/packages/babel-preset-env)
* [css-loader](https://www.npmjs.com/package/css-loader)
* [extract-text-webpack-plugin ^4.0.0-beta.0](https://github.com/webpack-contrib/extract-text-webpack-plugin)
* [node-sass](https://www.npmjs.com/package/node-sass)
* [sass-loader](https://www.npmjs.com/package/sass-loader)
* [style-loader](https://www.npmjs.com/package/style-loader)
* [uglifyjs-webpack-plugin](https://www.npmjs.com/package/uglifyjs-webpack-plugin)
* [webpack](https://webpack.js.org/)
* [webpack-cli](https://webpack.js.org/api/cli/)

## Dependencies

* [Bootstrap](https://getbootstrap.com/)
* [Popper.js](https://popper.js.org/)

## Acknowledgements

* [HTML 5 Blank](https://github.com/toddmotto/html5blank)

## License

MIT

### Potentially Dangerous Files
 ` yarn.lock` 
 ` package-lock.json`
 
## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/pablorica/wordpress_codigo_theme/tags).

### Changelog

- 2.2
	- REST API filters 
    - Floating parallax elements

- 2.1.2
	- Adding Counter Block

- 2.1.1
	- Adding Instagram block

- 2.1.0
	- Adding VUE files
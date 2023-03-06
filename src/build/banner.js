'use strict'

const pkg = require('../../package.json')
const year = new Date().getFullYear()

function getBanner(pluginFilename) {
  return `/*!
  * Codigo${pluginFilename ? ` ${pluginFilename}` : ''} v${pkg.version} (${pkg.homepage})
  * Copyright ${year} ${pkg.author.name} <${pkg.author.email}>
  * Licensed under ${ pkg.license } (${ pkg.licenseUrl })
  */`
}

module.exports = getBanner
/**
 * PostCSS Configuration
 * 
 * Autoprefixer and other CSS post-processing tools
 */

module.exports = {
  plugins: [
    require('autoprefixer')({
      overrideBrowserslist: [
        '> 1%',
        'last 2 versions',
        'ie >= 11',
        'Firefox ESR'
      ],
      grid: true
    })
  ]
}; 
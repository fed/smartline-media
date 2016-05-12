var version = require('../package.json').version;

module.exports = {
  options: {
    data: {
      version: version
    }
  },
  main: {
    src: 'version.hbs',
    dest: 'dist/version.txt'
  }
};

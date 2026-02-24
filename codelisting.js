const glob = require("glob");
const fs = require('fs');

var getDirectories = function (src, callback) {
  glob(src + '/**/*', callback);
};

// var extensions = ['php', ]
var ignore = [
  'client/assets/fonts',
  'client/assets/adminator/static',
  'client/assets/images',
]

var dirs = ['config', 'database', 'routes', 'app', 'resources', 'client']

for (var x = 0; x < dirs.length; x++) {
  getDirectories('./' + dirs[x], function (err, res) {
    var str = ''
    if (err) {
      console.log('Error', err);
    } else {
      lox: for (var i = 0; i < res.length; i++) {
        for (var n = 0; n < ignore.length; n++) {
          if (res[i].indexOf(ignore[n]) > -1) continue lox
        }
        if (fs.lstatSync(res[i]).isFile()) {
          str += '[File: ' + res[i].replace('./', '') + "]\n\n" + fs.readFileSync(res[i]) + "\n\n";
        }
      }
      console.log(res);
    }
    fs.appendFileSync('e-sakip.txt', str);
  });
}
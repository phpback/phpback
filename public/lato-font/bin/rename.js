
var path = require('path');
var fs = require('fs');
var file = require('file');
var _ = require('underscore');
var changeCase = require('change-case');

var filterExtensions = ['.eot', '.ttf', '.woff'];
var map = {
  'lato-regular' : 'lato-normal',
  'lato-italic'  : 'lato-normal-italic'
};

var fontsPath = path.resolve(__dirname + '/../fonts');

file.walk(fontsPath, function() {
  var files = arguments[3];
  _.each(files, function(filePath) {
    var extension = path.extname(filePath);
    if (-1 !== filterExtensions.indexOf(extension)) {
      renameFile(filePath);
    }
  });
});

function renameFile(filePath) {

  var dirName = path.dirname(filePath);
  var filename = path.basename(filePath);
  var baseFileName = filename.replace(/\.[^/.]+$/, '');
  var extension = path.extname(filePath);

  var newBaseFileName = changeCase.paramCase(baseFileName);

  // Handling exceptional cases.
  if (map[newBaseFileName]) {
    newBaseFileName = map[newBaseFileName];
  }

  var newFileName = newBaseFileName + extension;
  var newFilePath = dirName + '/' + newFileName;

  console.log('Renaming: ' + filename + ' to: ' + newFileName);

  // Re-naming the file.
  fs.rename(filePath, newFilePath);
}

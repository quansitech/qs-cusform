var fs = require("fs");

function callback(err) {
  if (err) throw err;
}

// By using COPYFILE_EXCL, the operation will fail if destination.txt exists.
fs.copyFile(__dirname + '/asset/antd-with-locales.min.js', __dirname + '/build/antd-with-locales.min.js', fs.COPYFILE_EXCL, callback);
fs.copyFile(__dirname + '/asset/moment-with-locales.js', __dirname + '/build/moment-with-locales.js', fs.COPYFILE_EXCL, callback);
fs.copyFile(__dirname + '/asset/react.development.js', __dirname + '/build/react.development.js', fs.COPYFILE_EXCL, callback);
fs.copyFile(__dirname + '/asset/react-dom.development.js', __dirname + '/build/react-dom.development.js', fs.COPYFILE_EXCL, callback);

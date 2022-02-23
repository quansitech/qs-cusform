var fs = require("fs-extra");

function callback(err) {
  if (err) throw err;
}

const srcDir = `${__dirname}/Public/cusform`;
const destDir = `${__dirname}/build`;

fs.copySync(srcDir, destDir, { overwrite: true }, callback);

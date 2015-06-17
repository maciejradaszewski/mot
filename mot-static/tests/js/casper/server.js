/**
 * This is a node express server that will server the casper html files.
 *
 * start tye server using 'grunt express:casper'
 * stop the server using 'grunt express:casper:stop'
 *
 * View the server in a browser at localhost:3900/
 */

var express = require('express');
var app = express();

app.use(express.static(__dirname + '/public'));

module.exports = app.listen(3900);

console.log('Server up.. Ready to run casper tests.');
/**
 * This is a node express server that will serve the ux mocks html files.
 *
 * start the server using 'grunt ux:mocks'
 * stop the server using 'grunt ux:mocks:stop'
 *
 * View the server in a browser at localhost:3909/
 */

var express = require('express');
var app = express();

app.use(express.static(__dirname + '/mocks'));

module.exports = app.listen(3909);

console.log('Server up.. View the UX Mocks in http://localhost:3909');






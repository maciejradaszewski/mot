/**
 * This is a node express server that will serve the styleguide.
 *
 * start the server using 'grunt styleguide:serve'
 *
 * View the server in a browser at localhost:3808/
 */

var express = require('express')
    , port = 3808
    , fs = require('fs')
    , Handlebars = require("handlebars")
    , cons = require('consolidate');

var Tomill = require(__dirname + "/../src/modules/styleguide/tomill");

tomill = new Tomill();
app = express();
app.engine('html', cons.handlebars);
app.use(express.static(__dirname + '/'));
app.set('view engine', 'html');
app.set('views', __dirname + '/views');

var styleguide = {
    config: {
        viewDir: __dirname + '/views/'
        , partialsDir: __dirname + '/views/partials/' 
        , scssDir: __dirname + '/../src/scss/' 
    }
};

function loadPartials(req, res) {
    var partialsDir = styleguide.config.partialsDir;
    fs.readdirSync(partialsDir).forEach(function (file) {
        var source = fs.readFileSync(partialsDir + file, "utf8"),
            partial = /(.+)\.html/.exec(file).pop();

        Handlebars.registerPartial(partial, source);
    })
}

function parseScssFiles() {
    var atoms = [];
    var dir = styleguide.config.scssDir + 'partials/';
    fs.readdirSync(dir).forEach(function (file) {
        tomill.parseFile(dir + file);
    })
    
    tomill.sortAtoms();
    
    tomill.atoms.forEach(function(atom) {
        atoms.push(atom);
    });
    
    return atoms;
}

renderPage = function renderPage(view, data, res, layoutExt) {
    layoutExt = layoutExt || '';

    var source = fs.readFileSync(styleguide.config.viewDir + view + '.html', "utf8");
    var template = Handlebars.compile(source);
    var html = template(data);

    data.content = html;

    res.render('layout' + layoutExt, data);
}

/**
 * We are reloading the partials on each request to help 
 * with development as this is for the Styleguide application.
 */
app.all('*', function(req, res, next) {
    loadPartials(req, res);
    // Make sure there is no caching.
    //res.header("Cache-Control", "no-cache, no-store, must-revalidate");
    //res.header("Pragma", "no-cache");
    res.header("Expires", "Sun, 11 Mar 1984 12:00:00 GMT");
    next();
});

app.get('/', function (req, res) {
    renderPage('index', {
        page_title: 'Introduction'
        , task_title: 'Pattern Library'
        , task_step: 'Introducing the Styleguide'
    }, res);
});

// Build the atom html.
var atoms = parseScssFiles();
console.log(atoms.length + ' atoms created.');

var atomHtml = '';
atoms.forEach(function(atom, index) {
    var source = fs.readFileSync(styleguide.config.partialsDir + 'atom.html', "utf8");
    var template = Handlebars.compile(source);
    var html = template({ name: 'atom ' + index });
    var html = template(atom);
    atomHtml += html;
});

app.get('/atoms', function (req, res) {
    renderPage('atoms', {
        page_title: 'Atoms'
        , task_title: 'Pattern Library'
        , task_step: 'Atoms'
        , atoms: atomHtml
    }, res);
});

app.get('/change-log', function (req, res) {
    renderPage('change-log', {
        page_title: 'Change log'
        , task_title: 'Pattern Library'
        , task_step: 'Change log'
    }, res);
});

app.get('/foundations', function (req, res) {
    renderPage('foundations', {
        page_title: 'Foundations'
        , task_title: 'Pattern Library'
        , task_step: 'Foundations'
    }, res);
});

app.get('/building-blocks', function (req, res) {
    renderPage('building-blocks', {
        page_title: 'Building blocks'
        , task_title: 'Pattern Library'
        , task_step: 'Building blocks'
    }, res);
});

app.get('/web-patterns', function (req, res) {
    renderPage('web-patterns', {
        page_title: 'Web Patterns'
        , task_title: 'Pattern Library'
        , task_step: 'Web Patterns'
    }, res);
});

app.get('/content-guide', function (req, res) {
    renderPage('content-guide', {
        page_title: 'Content guide'
        , task_title: 'Pattern Library'
        , task_step: 'Content guide'
    }, res);
});

app.get('/colour-palette', function (req, res) {
    renderPage('colour-palette', {
        page_title: 'Colour palette'
        , task_title: 'Pattern Library'
        , task_step: 'Colour palette'
    }, res);
});

app.get('/templates', function (req, res) {
    renderPage('templates', {
        page_title: 'Templates'
        , task_title: 'Pattern Library'
        , task_step: 'Templates'
    }, res);
});

app.get('/example-form', function (req, res) {
    renderPage('example-form', {
        page_title: 'Example - Form'
        , task_title: 'Pattern Library'
        , task_step: 'Form Example'
        , back_url: "web-patterns"
        , back_text: "Back to Web patterns"
    }, res, '-example');
});

require('./js/routes/claim-account.js');

app.get('/example-grid', function (req, res) {
    renderPage('example-grid', {
        page_title: 'Example - Grid'
        , task_title: 'Pattern Library'
        , task_step: 'Grid layout'
        , back_url: "foundations"
        , back_text: "Back to Foundations"
    }, res, '-example');
});

app.get('/example-header-tester', function (req, res) {
    renderPage('example-header-tester', {
        page_title: 'Example - Testers header functionality'
        , task_title: 'Pattern Library'
        , task_step: 'Header functionality'
        , back_url: "templates"
        , back_text: "Back to Templates"
    }, res, '-example');
});

app.get('/example-page-task-linear', function (req, res) {
    renderPage('example-page-task-linear', {
        page_title: 'Example - Linear task'
        , task_title: 'Pattern Library'
        , task_step: 'Example - Linear task'
    }, res, '-example');
});

app.get('/example-page-task-non-linear', function (req, res) {
    renderPage('example-page-task-non-linear', {
        page_title: 'Example - Non-linear task'
        , task_title: 'Pattern Library'
        , task_step: 'Example - Non-linear task'
    }, res, '-example');
});

app.get('/example-user-home', function (req, res) {
    var hero = 'hero-' + (req.param('hero') || '1');

    var source = fs.readFileSync(styleguide.config.partialsDir + hero + '.html', "utf8");
    var template = Handlebars.compile(source);
    var heroHtml = template({});

    renderPage('example-page-user-home', {
        page_title: 'Example - User home'
        , task_title: 'Pattern Library'
        , task_step: 'Example - User home'
        , hero: heroHtml
    }, res, '-example');
});

module.exports = app.listen(port);

console.log('Now serving the Styleguide in http://localhost:' + port);


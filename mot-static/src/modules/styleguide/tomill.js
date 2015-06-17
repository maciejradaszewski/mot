/*
 * Tomill - A css living styleguide parser
 *
 * @authors    William Elworthy, Tom Carrington
 */

'use strict';

var tomill = function () {
    this.config = {
        version: '0.1.0'
        , labels: {
            inDevelopment: 'in development',
            defaultCategory: 'page-element'
        }
    };
    this.comments = [];
    this.atoms = [];
};

tomill.prototype.version = function() {
    return this.config.version;
};

tomill.prototype.parseFile = function(filename) {
    this.filename = filename;

    this.fetchSource();
    this.findComments();
    this.processAtoms();
};

tomill.prototype.fetchSource = function() {
    this.source = fs.readFileSync(this.filename, "utf8");
};

tomill.prototype.findComments = function() {
   var regEx = /\/\*([\s\S]+?)\*\//g;

   var m
       , matches = [];

   while((m = regEx.exec(this.source)) !== null) {
       matches.push(m[1].trim('\n').trim());
   }
   //console.log(matches);
   
   this.comments = matches;
};

tomill.prototype.processAtoms = function() {
    var self = this;

    if (!this.comments) {
        return;
    }

    this.comments.forEach(function (comment, index) {
        var commentData = self.splitComment(comment);

        if (commentData) {
            self.atoms.push({
                raw: comment
                //, lines: commentData.lines
                , name: commentData.name || 'Atom ' + index
                , status: commentData.status || self.config.labels.inDevelopment
                , comments: commentData.comments || ''
                , category: commentData.category || self.config.labels.defaultCategory
                , examples: commentData.examples || []
            });
        }
    });
};

tomill.prototype.sortAtoms = function() {
    var self = this;
    
    self.atoms.sort(function (a, b) {
        if (a.category > b.category) {
            return 1;
        }
        if (a.category < b.category) {
            return -1;
        }
        return 0;
    });
}

tomill.prototype.splitComment = function(comment) {
    var self = this;
    var details = {};

    var lines = comment.match(/(@[\w]+)\s?([^@]+)/g);
    //details.lines = lines; // DEBUG

    if (!lines) {
        return;
    }

    details.examples = [];
    lines.forEach(function (line) {
        if (/@name/.test(line)) {
            var m = line.trim().match(/(@[\w]+)\s([\w\-\_\.]+)/);
            details.name = m[2];
        }
        if (/@category/.test(line)) {
            var m = line.trim().match(/(@[\w]+)\s([\w\-\_\.]+)/);
            details.category = m[2];
        }
        if (/@status/.test(line)) {
            var m = line.trim().match(/(@[\w]+)\s(\w+)/);
            details.status = m[2];
        }
        if (/@comments/.test(line)) {
            var m = line.trim().match(/(@[\w]+)[\s\n]?([\s\S]+)/);
            details.comments= m[2].trim().replace(/(?:\r\n|\r|\n)/g, '<br />');
        }

        if (/@example/.test(line)) {
            if (/@example-/.test(line)) {
                var m = line.trim().match(/@example-([\w-]+)[\s\n]?([\s\S]+)/);
                details.examples.push({
                    name: m[1]
                    , html: m[2].trim()
                });
            } else {
                var m = line.trim().match(/@([\w]+)[\s\n]?([\s\S]+)/);
                details.examples.push({
                    name: m[1]
                    , html: m[2].trim()
                });
            }
        }

        //console.log(">>>" + line.trim() + "<<<<");
        //var m = line.trim().match(/(@[\w]+)\s(\w+)/);
        //console.log(m);
    });

    //console.log(details);

    return details;
};

var fs = require('fs');

module.exports = tomill;


/**
 * tests are run within a javascript sandboxed environment.
 *
 * Postman/Newman provides access to the following libraries, which you can use within your tests.
 *
 * Library (variable Name)
 * ----------------------
 *
 * - jQuery ($)
 * - Backbone (Backbone)
 * - underscore (_)
 * - sugar.js
 * - X2JS
 * - Tiny Validator V4. (tv4)(we use this to validate schema.js)
 *
 * For examples and ideas of whats possible see these page:
 * - http://www.getpostman.com/docs/jetpacks_writing_tests
 * - http://www.getpostman.com/docs/jetpacks_examples
 */

var data = JSON.parse(responseBody);
var expectedResponseTime = 500;
var responseTimeMessage = "Response time is less than "+expectedResponseTime+"ms";

tests["Status code is 200"]           = responseCode.code === 200;
tests["We received a JSON response"]  = typeof data === "object";
tests[responseTimeMessage]            = responseTime <= expectedResponseTime;
tests["we have a request object"]     = request;
tests["we have a request data object"]     = request.data;
// ---- JavaScript Utilities -----
// Author: Nigel Sedgwick 
// -------------------------------

// create handy prototype on the String object 
String.prototype.trim = function() {
	// skip leading and trailing whitespace
	// and return everything in between
	var newstr = this.replace(/^\s*/, "").replace(/\s*$/, ""); 
	newstr = newstr.replace(/\s{1,}/g, " "); 
	return newstr;
}

function isEmpty(s){
	return ((s == null) || (s.trim().length == 0))
}

// Extend the validate class with various methods...
jQuery.validator.addMethod("dateRange", function() {
    var start_date = new Date( jQuery('#alt_date_start').val() );
    var end_date = new Date( jQuery('#alt_date_end').val() );
    if( start_date < end_date )
        return true;
    return false;
});



// Extend email validation method so that it ignores whitespace
jQuery.validator.addMethod("emailTrailingSpaces", function(value, element) {
    return (this.optional(element) || jQuery.validator.methods.email.call(this, jQuery.trim(value), element));
}, "<span class='errorMsg'><br />Please enter a valid email");
		

jQuery.validator.addMethod("postcode", function(value) {
	value = jQuery.trim(value);
	return value.match("^[A-Za-z]{1,2}[0-9A-Za-z]{1,2}[ ]?[0-9]{0,1}[A-Za-z]{2}$");
}, "<span class='errorMsg'><br />Please enter a valid UK postcode</span>");


jQuery.validator.addMethod("postcodeInternational", function(value) {
//  With reference to the 'Character Sets' section in http://en.wikipedia.org/wiki/Postal_code
	value = jQuery.trim(value);
	return value.match("^[A-Za-z0-9'\\-/\\s]*$");																																																																																																										}, "<span class='errorMsg'><br />Invalid character in postcode</span>");


jQuery.validator.addMethod("noInvalidChars", function(value, element) {		
	return value.match("^[A-Za-z0-9'/\\.\\\\\:\\_\\|\\$\\%\\*\\=\\;\\`\\(\\)\\!\\?\\#\\-\\+&\\s]*$");
}, "<span class='errorMsg'><br />Invalid characters entered</span>");


jQuery.validator.addMethod("noInvalidCharsInternational", function(value, element) {	
// Allow international chars - such as in name & address: José Raoûl, Hauptstraße 100, Neubiberg, München
// or any of: ÀÈÌÒÙàèìòùÁÉÍÓÚİáéíóúıÂÊÎÔÛâêîôûÃÑÕãñõÄËÏÖÜäëïöüçÇßØøÅåÆæŞşĞğ
	return value.match("^[A-Za-z0-9\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF'/\\.\\\\\:\\_\\|\\=\\$\\%\\*\\;\\`\\,\\@\\!\\?\\(\\)\\[\\]\\#\"\\-\\+&\\s]*$");
}, "<span class='errorMsg'><br />Invalid characters entered</span>");


jQuery.validator.addMethod("noInvalidPhoneChars", function(value, element) {	
	// A general phone number validation to insure that NO malicious code or specified characters are passed 
	// This will allow any characters in the following example: +44 (0) 123 345345 #12 - (after 6pm)
	return value.match("^[A-Za-z0-9'/\\(\\)\\#\\-\\+&\\s]*$");
}, "<span class='errorMsg'><br />Invalid characters entered</span>");


jQuery.validator.addMethod("noInvalidCharsAddr", function(value, element) {		
	return value.match("^[A-Za-z0-9'/\\.\\,\\(\\)\\[\\]\\#\"\\-\\+&\\s]*$");
}, "<span class='errorMsg'><br />Invalid characters entered</span>");


jQuery.validator.addMethod("noInvalidCharsText", function(value, element) {	
	// A general text validation to insure that NO malicious code or specified characters are passed 
	// through user input. This will allow any characters except &lt;&gt;`~/\}_^{|'
	return value.match("^[^<>~\}_^{|]*$");
}, "<span class='errorMsg'><br />Invalid characters entered, please re-phrase</span>");


jQuery.validator.addMethod("noInvalidCharsStrict", function(value, element) {		
	return value.match("^[A-Za-z0-9'\\-/\\s]*$");
}, "<span class='errorMsg'><br />Invalid characters entered</span>");


jQuery.validator.addMethod("numeric", function(value, element) {		
	return value.match("^[0-9]*$");
}, "<span class='errorMsg'><br />Invalid number</span>");


jQuery.validator.addMethod("currency", function(value, element) { 
	return this.optional(element) || /^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/.test(value); 
}, "<span class='errorMsg'><br />Amount must be in decimal currency format (eg. 1234.50)</span>");
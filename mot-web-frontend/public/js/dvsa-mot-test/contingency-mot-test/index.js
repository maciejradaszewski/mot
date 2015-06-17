$(document).ready(function() {

    $('textarea').autosize();   // Set up all textareas to auto-resize automatically

    if ($('.form-vertical').length>0) {
        // Add focus
        $(".block-label input")
            .focus(function() {
                $("label[for='" + this.id + "']").addClass("add-focus");
            }).blur(function() {
                $("label").removeClass("add-focus");
            });

        // Add selected class
        var $inputChecked = $('input:checked');

        $inputChecked.parent().addClass('selected');

        // Add/remove selected class
        $('.block-label input[type=radio], .block-label input[type=checkbox]').click(function() {
            $('input:not(:checked)').parent().removeClass('selected');
            $('input:checked').parent().addClass('selected');

            $('.toggle-content').hide();

            var target = $('input:checked').parent().attr('data-target');

            $('#'+target).show();
        });

        // For pre-checked inputs, show toggled content
        var target = $inputChecked.parent().attr('data-target');
        $('#'+target).show();

    }

    var Contingency = function Contingency(options) {

        this.options = options || {};
        this.performedBy = null;
        this.multisite = null;
        this.container = null;


        this.initialise = function() {
            this.container = $(this.options.selector.container);
            this.setupForm();
            this.bindEvents();
            this.initDateInput();
        };

        this.setupForm = function() {
            if (this.container.find(this.options.selector.performedByCurrent + ':checked').length == 0) {
                this.container.find(this.options.selector.multiSiteContainer).hide();
            }

            if (this.container.find(this.options.selector.performedByOther + ':checked').length == 0) {
                this.container.find(this.options.selector.otherTesterContainer).hide();
            }

            var reason = this.container.find(this.options.selector.reasonContainer + ' input:checked');
            if (!reason.length || reason.val() != 'OT') {
                this.container.find(this.options.selector.reasonsOtherContainer).hide();
            }

            this.refresh();
        };

        this.bindEvents = function() {
            var selector = this.options.selector;

            // across the board refresh...
            this.container.find(selector.refreshOnChange).on('change', $.proxy(this.refresh, this));
            this.container.find(selector.otherTesterNumber).on('keyup', $.proxy(this.refresh, this));

            // performed by current tester...
            this.container.find(selector.performedByCurrent).on('click', $.proxy(function(){
                this.container.find(selector.multiSiteContainer).hide();
                this.container.find(selector.otherTesterContainer).hide();
                this.container.find(selector.multiSiteContainer).show('slow');
            }, this));

            // performed by other tester...
            this.container.find(selector.performedByOther).on('click', $.proxy(function(){
                this.container.find(selector.multiSiteContainer).hide();
                this.container.find(selector.otherTesterContainer).hide();
                this.container.find(selector.otherTesterContainer).show('slow');
            }, this));

            // "other" reasons selected
            this.container.find(selector.reasonsRadio).on('click', $.proxy(function(element){
                var radio = $(element.target);

                if (radio.val() == 'OT') {
                    this.container.find(selector.reasonsOtherContainer).show('slow');
                } else if (typeof(radio.val()) != "undefined") {
                    this.container.find(selector.reasonsOtherContainer).hide('slow');
                }
            }, this));
        };

        this.refresh = function() {
            this.refreshPerformedBy();
            this.refreshIsMultiSite();
//            this.toggleSubmit();
        };

        this.refreshPerformedBy = function() {
            if (this.container.find(this.options.selector.performedByCurrent + ':checked').length) {
                this.performedBy = 'Current';
            } else if (this.container.find(this.options.selector.performedByOther + ':checked').length) {
                this.performedBy = 'Other';
            } else {
                this.performedBy = null;
            }
        };

        this.refreshIsMultiSite = function() {
            if (this.container.find(this.options.selector.multiSiteContainer + ' input[type=hidden]').length) {
                this.multisite = false;
            } else if (this.container.find(this.options.selector.multiSiteContainer + ' input').length) {
                this.multisite = true;
            } else {
                this.multisite = null;
            }
        };

        /**
         * Works out whether the form is submittable
         */
        this.toggleSubmit = function() {

            var reason = this.container.find(this.options.selector.reasonContainer + ' input:checked');
            this.container.find(this.options.selector.submit).prop('disabled', true);

            if (
                // performedby checks
                (
                    (this.performedBy == 'Current' && this.multisite === false)
                    || (this.performedBy == 'Current' && this.multisite && this.container.find(this.options.selector.multiSiteContainer + ' input:checked').length)
                    || (this.performedBy == 'Other' && this.container.find(this.options.selector.otherTesterNumber).val().length == 8)
                )

                // assorted
                && (this.container.find(this.options.selector.contingencyCode).val() != '')
                && (this.container.find(this.options.selector.testTypeContainer + ' input:checked').length)

                // date checks
                && (
                    (this.container.find(this.options.selector.dateDay).val().length)
                    && (this.container.find(this.options.selector.dateMonth).val().length)
                    && (this.container.find(this.options.selector.dateYear).val().length)
                )

                // reasons
                && (
                    (reason.length && reason.val() != 'Other')
                    || (reason.length && reason.val() == 'Other' && this.container.find(this.options.selector.reasonsOtherContainer + ' textarea').val().length)
                )
            ) {
                this.container.find(this.options.selector.submit).prop('disabled', false);
            }
        };

        this.initDateInput = function() {
            $('#dateTestDay, #dateTestMonth')
                .blur(function() {
                    var value = $(this).val();
                    if (1 == value.trim().length) {
                        $(this).val('0' + value.trim());
                    }
                }
            );
        };
    };

    var options = {
        selector: {
            container:            '#CTVerifcation',
            'refreshOnChange':    'select,input,textarea',
            'performedByCurrent': '#who-1',
            'performedByOther':   '#who-2',
            'multiSiteContainer':   '.multiple-sites',
            'otherTesterContainer':   '.otherTester',
            'otherTesterNumber':   '#testerNumber',
            'reasonsRadio':   '.other-reason > input[type="radio"]',
            'reasonsOtherContainer':   '.otherReasons',
            'contingencyCode':   '#ct-code',
            'testTypeContainer':   '.test-type',
            'reasonContainer':   '.ct-reason',
            'dateDay':   '#dateTestDay',
            'dateMonth':   '#dateTestMonth',
            'dateYear':   '#dateTestYear',
            'submit':   '#confirm_ct_button'
        }
    };
    var theContingency = new Contingency(options);
    theContingency.initialise();
});

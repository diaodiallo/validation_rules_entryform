Drupal.behaviors.myBehavior = {
    attach: function (context, settings) {


        var rules = [{ind1: "edit-field-c-newinc-0-value", operator: "<", ind2: "edit-field-newrel-m014-0-value"},
            {ind1: "edit-field-conf-rrmdr-tx-0-value", operator: "<=", ind2: "edit-field-conf-xdr-tx-0-value"},
            {ind1: "edit-field-unconf-rrmdr-tx-0-value", operator: "<", ind2: "edit-field-conf-rrmdr-0-value"}];

        jQuery(document).ready(function () {
            //Loop all validation rules
            jQuery.each(rules, function (i, val) {
                //On doc open
                var val1 = parseInt(jQuery("#" + val.ind1).val());
                var val2 = parseInt(jQuery("#" + val.ind2).val());

                if (!comparator(val1, val2, val.operator)) {
                    if (!jQuery("#" + val.ind1).next().hasClass(formatLabel(val.ind1) + "-" + formatLabel(val.ind2))) {
                        jQuery("#" + val.ind1).css("border-color", "red");
                        jQuery("#" + val.ind1).after("<span class='validationMessage' style='color:red;'> Should be " + val.operator + " " + formatLabel(val.ind2) + ".</span>");
                        jQuery("#" + val.ind1).next().addClass(formatLabel(val.ind1) + "-" + formatLabel(val.ind2));
                    }
                }
            });
        });

        //Loop all validation rules
        jQuery.each(rules, function (i, val) {
            //The left indicator of the validation rule
            jQuery("#" + val.ind1).on("focusout", function () {
                var val1 = parseInt(jQuery(this).val());
                var val2 = parseInt(jQuery("#" + val.ind2).val());

                if (!isNaN(val2)) {
                    if (!comparator(val1, val2, val.operator)) {
                        if (!jQuery(this).next().hasClass(formatLabel(val.ind1) + "-" + formatLabel(val.ind2))) {
                            jQuery(this).css("border-color", "red");
                            jQuery(this).after("<span class='validationMessage' style='color:red;'> Should be " + val.operator + " " + formatLabel(val.ind2) + ".</span>");
                            jQuery(this).next().addClass(formatLabel(val.ind1) + "-" + formatLabel(val.ind2));
                        }
                    }
                    else {
                        if (jQuery("span").hasClass(formatLabel(val.ind1) + "-" + formatLabel(val.ind2))) {
                            jQuery(this).css("border-color", "");
                            jQuery("#" + val.ind2).css("border-color", "");
                            jQuery("." + formatLabel(val.ind1) + "-" + formatLabel(val.ind2)).remove();
                        }
                    }
                }
            });

            //The right element of the validation rules
            jQuery("#" + val.ind2).on("focusout", function () {
                var val2 = parseInt(jQuery(this).val());
                var val1 = parseInt(jQuery("#" + val.ind1).val());

                if (!isNaN(val1)) {
                    if (!comparator(val1, val2, val.operator)) {
                        if (!jQuery(this).next().hasClass(formatLabel(val.ind1) + "-" + formatLabel(val.ind2))) {
                            jQuery(this).css("border-color", "red");
                            jQuery(this).after("<span class='validationMessage' style='color:red;'> Should be " + inverseOperator(val.operator) + " " + formatLabel(val.ind1) + ".</span>");
                            jQuery(this).next().addClass(formatLabel(val.ind1) + "-" + formatLabel(val.ind2));
                        }
                    }
                    else {
                        if (jQuery("span").hasClass(formatLabel(val.ind1) + "-" + formatLabel(val.ind2))) {
                            jQuery(this).css("border-color", "");
                            jQuery("#" + val.ind1).css("border-color", "");
                            jQuery("." + formatLabel(val.ind1) + "-" + formatLabel(val.ind2)).remove();
                        }
                    }
                }
            });
        });

        jQuery("#node-tajikistan-data-entry-edit-form").submit(function(){
            if (jQuery("span").hasClass("validationMessage")) {
                jQuery("#edit-field-validation-status").val('No');
            }
            else {
                //console.log("On submit: Valid");
                jQuery("#edit-field-validation-status").val('Yes');
            }
        });


        //Verify if the rule is violated
        var comparator = function (val1, val2, operator) {
            if (operator == "<") {
                if (val1 < val2) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else if (operator == "<=") {
                if (val1 <= val2) {
                    return true;
                }
                else {
                    return false;
                }
            }
        };

        //Convert the field code to the name of indicator
        var formatLabel = function (ind) {
            ind = ind.replace("edit-field-", "");
            ind = ind.replace("-0-value", "");

            return ind;
        };

        //Inverse the rule operator
        var inverseOperator = function (operator) {
            if (operator == "<") {
                return ">";
            }
            else if (operator == "<=") {
                return ">=";
            }
        };
    }
};
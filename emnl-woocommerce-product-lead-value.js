jQuery(".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
    // Fired when the user selects all the required dropdowns / attributes
    // and a final variation is selected / shown

    // if LeadValue don't update!
    if(!jQuery("#leadValue")[0]) {

        // LeadValueMultiplier
        var leadValueMultiplier = jQuery("#leadValueMultiplier").val();
        var currentLeadValue = 0;
        jQuery.each(dataLayer, function (key, val) {
            if ('ProductLeadValue' in val) {
                currentLeadValue = val.ProductLeadValue;
            }
        });
        var newLeadValue = variation.display_price * leadValueMultiplier;
        newLeadValue = newLeadValue.toFixed(2);
        if (newLeadValue != currentLeadValue || currentLeadValue === 0) {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                "ProductLeadValue": newLeadValue
            });
        }
    }
} );
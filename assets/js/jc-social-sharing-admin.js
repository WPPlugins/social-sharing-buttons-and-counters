jQuery(function($) {
    
    $( ".sortable" ).sortable({
        connectWith: ".connectable",
        helper: 'clone',
        update:  function( ) {
            updateSocialOptions();
            checkTwitter();
        }
    }).disableSelection();
});

function updateSocialOptions()
{
    jQuery("#social-options").val(jQuery("#social-selected ul .social-list-item li span").map(function() {
	   return jQuery(this).html();
    }).get());
}

function checkTwitter() {
    if ( jQuery( "#social-selected").find("#Twitter").length > 0) {
        jQuery( "#twitter-username" ).show();
    }
    else {
        jQuery( "#twitter-username" ).hide();     
    }
}
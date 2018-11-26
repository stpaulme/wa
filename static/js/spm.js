jQuery(document).ready(function() {
    
    function getAway() {
        // Get away right now
        window.open("http://weather.com", "_newtab");
        // Replace current site with another benign site
        window.location.replace('http://google.com');
    }

    jQuery(function() {

        jQuery(".exit").hide();
        
        jQuery(".exit").on("click", function(e) {
            getAway();
        });
        
        jQuery(document).keyup(function(e) {
            if (e.keyCode == 27) { // escape key
                getAway();
            }
        });
    
    });

    var offset = 250;
    
    jQuery(window).scroll(function() {
        
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.exit').fadeIn();
        } else {
            jQuery('.exit').fadeOut();
        }
    
    });

});
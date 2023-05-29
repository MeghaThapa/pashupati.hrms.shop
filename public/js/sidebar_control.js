(function(){
    "use strict";
    
    // LIGHT OR DARK MODE OPTIONS 
    jQuery('#darkmode').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "/theme-settings",
            method: 'post',
            data: {
                mode: jQuery(this).val()
            },
            success: function(result){
                window.location.reload();
            }
        });
    });
    

    // NAVBAR FIXED OPTIONS
    jQuery('#fixedNav').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "/theme-settings",
            method: 'post',
            data: {
                fixedNav: jQuery(this).val()
            },
            success: function(result){
                window.location.reload();
            }
        });
    });

    // NAVBAR FIXED OPTIONS
    jQuery('#borderBtm').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "/theme-settings",
            method: 'post',
            data: {
                borderBtm: jQuery(this).val()
            },
            success: function(result){
                window.location.reload();
            }
        });
    });

    // SIDEBAR COLLAPSED
    jQuery('#collapsedSidebar').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "/theme-settings",
            method: 'post',
            data: {
                collapsedSidebar: jQuery(this).val()
            },
            success: function(result){
                window.location.reload();
            }
        });
    });

    // SIDEBAR FIXED
    jQuery('#fixedSidebar').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "/theme-settings",
            method: 'post',
            data: {
                fixedSidebar: jQuery(this).val()
            },
            success: function(result){
                window.location.reload();
            }
        });
    });

    // SIDEBAR DARK
    jQuery('#darkSidebar').click(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "/theme-settings",
            method: 'post',
            data: {
                darkSidebar: jQuery(this).val()
            },
            success: function(result){
                window.location.reload();
            }
        });
    });

})();
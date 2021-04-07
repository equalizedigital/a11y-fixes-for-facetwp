(function($) {
    var last_checked = null;
   
    if ('undefined' !== typeof FWP.hooks) {
        FWP.hooks.addAction('facetwp/loaded', function() {
           
            $('.facetwp-checkbox, .facetwp-radio').each(function() {
                $(this).attr('role', 'checkbox');
                $(this).attr('aria-checked', $(this).hasClass('checked') ? 'true' : 'false');
                $(this).attr('tabindex', 0);
            });
            $('.facetwp-overflow').each(function(){
               
                if( $(this).find('.checked').length > 0 ) {
                    $(this).removeClass( 'facetwp-hidden');
                    $(this).siblings('a.facetwp-toggle').each(function(){
                        $(this).toggleClass( 'facetwp-hidden');
                    });
                    $(this).next('.facetwp-toggle[data-show="more"]').addClass('facetwp-hidden');
                    $(this).siblings('[data-show="less"]').removeClass('facetwp-hidden');
                }
            });

            $('.facetwp-toggle').each(function() {
                $(this).attr('role', 'link');
                var parentName = $(this).closest('.facetwp-facet').attr('data-name');
                var text = $(this).text();
                var toggleState = text.includes('more')? 'more' : 'less';
                $(this).attr('data-show', toggleState );
                $(this).attr('aria-label', text + ' ' + remove_underscores( parentName ) );
                $(this).attr('tabindex', 0);
            });

            $('.facetwp-type-a11y_pager .facetwp-page').each(function(){
                $(this).removeAttr('aria-current');
            });
 
            $('.facetwp-type-dropdown, .facetwp-type-pager').each(function(){ 
                $(this).find('select').attr( 'aria-label', remove_underscores($(this).data('name')));
            });

            $('.facetwp-type-checkboxes').each(function() {
                $(this).attr('aria-label', remove_underscores($(this).data('name')));
                $(this).attr('role', 'group' );
            });

            $('.facetwp-search').each(function() {
                
                var ariaLabel = $(this).attr('placeholder').length > 0 ? $(this).attr('placeholder') : remove_underscores($(this).closest('.facetwp-facet').attr('data-name'));
                $(this).attr('aria-label', ariaLabel );
            });

            $('.facetwp-number').each(function() {  
                var ariaLabel = remove_underscores($(this).closest('.facetwp-facet').attr('data-name'));
                $(this).attr('aria-label', ariaLabel );
            });

            if ( null != last_checked ) {
                $('.facetwp-facet [data-value="' + last_checked + '"]').focus();
                last_checked = null;
            }

        }, 999);
    }

    $(document).on('keydown', '.facetwp-checkbox, .facetwp-radio', function(e) {
        var keyCode = e.originalEvent.keyCode;
        if ( 32 == keyCode || 13 == keyCode ) {
            last_checked = $(this).attr('data-value');
            e.preventDefault();
            $(this).click();
        }
    });

    $(document).on('keydown', '.facetwp-page, .facetwp-toggle, .facetwp-selection-value', function(e) {
        var keyCode = e.originalEvent.keyCode;
        if ( 32 == keyCode || 13 == keyCode ) {
            e.preventDefault();
            $(this).click();
        }
    });
    
    $(document).on('click', '.facetwp-toggle', function(){
        if( $(this).data('show') == 'more') {
            $(this).siblings('.facetwp-overflow').children(':first-child').focus();
        } else {
            $(this).siblings('.facetwp-toggle[data-show="more"]').focus();
        }
    });
    
})(jQuery);

function remove_underscores( name ) {
    return name.replace(/_/g, ' ');
}
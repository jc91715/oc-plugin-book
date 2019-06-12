jQuery(document).ready(function($){

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        var $doc = $('.docs-sidebar-toc')
        $doc.hide()
        $('.md-content').css({'min-height':'300px'})
        $('.display-sidebar').click(function(){
            $doc.toggle()
            if($doc.is(':visible')){
                $('.display-sidebar').css({'position':'fixed','right':'10px','top':'70px'})
            }else{
                $('.display-sidebar').css({'position':'unset'})
            }

        })
    }else{

        var $doc = $('.docs-sidebar-toc'),
            scrolling = false,
            previousTop = 0,
            currentTop = 0,
            scrollDelta = 10,
            scrollOffset = 150
        $doc.css({'height':'877px'})
        if(window.localStorage.getItem('if_display_sidebar')=='0'){
            $('.col-display').hide()
        }
        $('.display-sidebar').click(function(){
            $('.col-display').toggle()
            if($('.col-display').is(':visible')){
                window.localStorage.setItem('if_display_sidebar','1')
            }else{
                window.localStorage.setItem('if_display_sidebar','0')
            }
        })
        $('.md-content').css({'min-height':'768px', 'padding-left': '20px'})
        $(window).on('scroll', function(){
            if (!scrolling) {
                scrolling = true

                if (!window.requestAnimationFrame) {
                    setTimeout(autoFixedDoc, 250)
                }
                else {
                    requestAnimationFrame(autoFixedDoc)
                }
            }
        })

        function autoFixedDoc() {
            var currentTop = $(window).scrollTop()

            // Scrolling up
            if (previousTop - currentTop > scrollDelta) {
                // $doc.removeClass('is-fixed')
                $doc.addClass('fixed-top')
            }
            else if (currentTop - previousTop > scrollDelta && currentTop > scrollOffset) {
                // Scrolling down
                $doc.addClass('is-fixed')
                $doc.removeClass('fixed-top')
            }
            if (currentTop<50) {
                $doc.removeClass('is-fixed')
            }
            previousTop = currentTop
            scrolling = false
        }
    }

});

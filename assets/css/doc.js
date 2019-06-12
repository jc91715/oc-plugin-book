jQuery(document).ready(function($){

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

    }else{
        var $doc = $('.docs-sidebar-toc'),
            scrolling = false,
            previousTop = 0,
            currentTop = 0,
            scrollDelta = 10,
            scrollOffset = 150

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

(function( $ ) {
	'use strict';
		
	$('#dbwp_main_dashboard_widget').detach().appendTo($('#welcome-panel').siblings('h1'));
    $("a.linkmodal").click(function() {
        var linkurl = $(this).attr("href");
        $("body").append("<div class='video-modal'><div class='video-modal-bg'></div><div class='video-modal-content'><iframe width='640' height='360' border='0' src='"+linkurl+"' frameborder='0' gesture='media' allowfullscreen></iframe></div></div>");
        jQuery(".video-modal-bg").click(function() {
            jQuery(".video-modal").remove();
        });
        jQuery(".video-modal-content, .video-modal-content iframe").width(parseInt(jQuery(window).width())*0.8);
        jQuery(".video-modal-content, .video-modal-content iframe").height(parseInt(jQuery(window).width())*0.8*0.5625);
        return false;
    })

})( jQuery );

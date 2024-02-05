/**
 * Handles the custom AJAX functions
 *
 *
 * @link   URL
 * @file   This files defines the customAJAX class.
 * @author Pablo Rica <pablo@codigo.co.uk>
 * @since  Codigo 1.0
 */

import consoleHello from './consoleHello';

const customAJAX = () => {

    (function($) {

        var launchAjax = (id) => {
            $.ajax({
                type: "post",
                url: cdg_ajax_var.url, //it's always admin_url( 'admin-ajax.php' )
                data: {
                    action  : cdg_ajax_var.action,
                    nonce   : cdg_ajax_var.nonce,
                    id_post : id
                },
                success: function(result){
                    $('#post-'+id).find('.entry-content').html(result);	
                }
            });
        }
        consoleHello('customAJAX is ready');

        $("td.product-remove a.remove").on("click", function(e){
            e.preventDefault();


	 	    var id = $(this).attr('href').replace(/^.*#more-/,'');

            $('#post-'+id).find('.entry-content').html('');	
            $('#summaryForm').data('summary','');

            //Launch AJAX every 2 seconds until the summary is not empty (it means WC has finished populating the basket and the summary is ready to be sent to the server)
            var refreshIntervalId = setInterval(function(){
                if($('#summaryForm').data('summary') != '' ){
                    consoleHello('load new summary in the checkout form');
                    launchAjax(id);
                    clearInterval(refreshIntervalId);
                }
            }, 2000);
            
        });
        

    })( jQuery );
}

export default customAJAX;
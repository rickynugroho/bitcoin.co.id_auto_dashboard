$(function () {
    const numberWithCommas = (x) => {
        //https://stackoverflow.com/questions/2901102/how-to-print-a-number-with-commas-as-thousands-separators-in-javascript
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
    
    function update_idr_value(){
        console.log('Update IDR Value');
        $('#total-asset').val($('#idr-balance').val());
        
        $.each(list_of_currency, function( index, value ) {
            // console.log( index + ": " + value );
            // https://vip.bitcoin.co.id/api/btc_idr/ticker
            
            var request = $.ajax({
                url: "https://vip.bitcoin.co.id/api/" + value + "_idr/ticker",
                method: "GET",
                dataType: "json"
            });
            
            request.done(function( msg ) {
                var idr_value = parseFloat($('#qty_' + value).val() * msg.ticker.last);
                idr_value = parseFloat(idr_value - (3/1000 * idr_value)).toFixed(0);
                $('#total-asset').val(parseFloat($('#total-asset').val()) + parseFloat(idr_value));
                
                $('#idr_value_' + value).html(numberWithCommas(msg.ticker.last) + ' IDR');
                $('#times_idr_value_' + value).html(numberWithCommas(idr_value) + ' IDR');      
                $('#estimates_asset').html(numberWithCommas($('#total-asset').val()) + ' IDR');    
            });
            
            request.fail(function( jqXHR, textStatus ) {
                alert( "Update " + value + " IDR value failed: " + textStatus );
            });
        });
        
        // $('#estimates_asset').html(numberWithCommas(total_asset) + ' IDR');
    }
    
    setInterval(update_idr_value(), 60000);
    
    $('[data-toggle="tooltip"]').tooltip()
    
    $('.refresh-frame').on('click', function(){
        var iframe = document.getElementById($(this).data('frame-id'));
        iframe.src = iframe.src;
    });
})

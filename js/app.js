const numberWithCommas = (x) => {
    //https://stackoverflow.com/questions/2901102/how-to-print-a-number-with-commas-as-thousands-separators-in-javascript
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

var update_idr_func;

function update_idr_value(){
    // console.log('Update IDR Value');
    $('.refresh-idr-value').prop('disabled', true);
    $('.refresh-idr-value').html('(loading...)');
    $('#total-asset').val($('#idr-balance').val());
    
    var count_update_idr_value = 0;
    $.each(list_of_currency, function( index, value ) {
        var request = $.ajax({
            url: "https://vip.bitcoin.co.id/api/" + value + "_idr/ticker",
            method: "GET",
            dataType: "json"
        });
        
        request.done(function( msg ) {
            count_update_idr_value++;
            
            if (typeof msg.ticker !== 'undefined') {
                var idr_value = parseFloat($('#qty_' + value).val() * msg.ticker.last);
                idr_value = parseFloat(idr_value - (3/1000 * idr_value)).toFixed(0);
                $('#total-asset').val(parseFloat($('#total-asset').val()) + parseFloat(idr_value));
                
                $('#idr_value_' + value).html(numberWithCommas(msg.ticker.last) + ' IDR');
                $('#times_idr_value_' + value).html(numberWithCommas(idr_value) + ' IDR');      
                $('#hidden_idr_value_' + value).val(idr_value);      
                $('#estimates_asset').html(numberWithCommas($('#total-asset').val()) + ' IDR');    
            }else{
                alert( "Update " + value + " IDR value failed. Probably because too much request in 1 minute.");
            }
            
            // console.log(list_of_currency.length + ' < ' + count_update_idr_value);
            if(list_of_currency.length <= count_update_idr_value){
                $('.refresh-idr-value').prop('disabled', false);
                $('.refresh-idr-value').html('<span class="oi oi-reload"></span>');
                // update_pending_orders();
            }
        });
        
        request.fail(function( jqXHR, textStatus ) {
            count_update_idr_value++;
            alert( "Update " + value + " IDR value failed: " + textStatus );
            
            if(list_of_currency.length <= count_update_idr_value){
                $('.refresh-idr-value').prop('disabled', false);
                $('.refresh-idr-value').html('<span class="oi oi-reload"></span>');
            }
        });
    });
}

function functionUpdateIDRValue() {
    update_idr_func = setInterval(update_idr_value, 60000);
}
functionUpdateIDRValue();
update_idr_value();

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
    
    $('.refresh-frame').on('click', function(){
        var iframe = document.getElementById($(this).data('frame-id'));
        iframe.src = iframe.src;
    });
    
    $('.transaction-list-btn').on('click', function(){
        $('#transactionListModal').data('pair', $(this).data('pair'));
        $('#transactionListModal').modal('show');
    });
    
    $('#transactionListModal').on('show.bs.modal', function (e) {
        var str_currency = $(this).data('pair');
        str_currency = str_currency.replace(/_idr/g, "");
        str_currency_upper = str_currency.toUpperCase();
        
        $('#transactionListModalLabel').html('Transaction List ' + str_currency_upper + ' - IDR');
        
        $('#transactionListModal .modal-body').html('(loading...)');
        
        var request = $.ajax({
            url: 'index.php?method=tradeHistory',
            method: "POST",
            data: {pair: $(this).data('pair'), order: 'asc'},
            dataType: "json"
        });
        
        request.done(function( msg ) {
            var table_trasnsaction = '<table class="table"><tr><td>Time</td><td>Type</td><td>Price</td><td>' + str_currency_upper + '</td><td>IDR</td></tr>';
            var stock_fifo = [];
            var price_fifo = [];
            var fifo_i = 0;
            
            $.each(msg.return.trades, function( index, value ) {
                table_trasnsaction += '<tr><td>' + moment.unix(value.trade_time).format("DD MMM YYYY HH:mm:ss") + '</td><td>' + value.type + '</td><td>' + numberWithCommas(value.price) + '</td><td>' + value[str_currency] + '</td><td>' + numberWithCommas((parseFloat(value.price) * parseFloat(value[str_currency])).toFixed(0)) + '</td></tr>';
                
                if(value.type == 'buy'){
                    // console.log('Buy: ' + value[str_currency] + ' ' + str_currency + ' x ' + value.price + ' IDR');
                    
                    stock_fifo[fifo_i] = value[str_currency];
                    price_fifo[fifo_i] = value.price;
                    fifo_i++;
                }
                if(value.type == 'sell'){
                    var sell_fifo_i = 0;
                    var sell_stock = value[str_currency];
                    
                    // console.log('Sell: ' + value[str_currency] + ' ' + str_currency);
                    
                    $.each(stock_fifo, function(index_fifo, value_fifo){
                        if(sell_stock > 0){
                            if(sell_stock <= stock_fifo[sell_fifo_i]){
                                // console.log('Sell 1: ' + stock_fifo[sell_fifo_i] + ' - ' + parseFloat(sell_stock) + ' = ' + (parseFloat(stock_fifo[sell_fifo_i]) - parseFloat(sell_stock)));
                                stock_fifo[sell_fifo_i] = (parseFloat(stock_fifo[sell_fifo_i]) - parseFloat(sell_stock));
                                sell_stock = 0;
                            }else{
                                // console.log('Sell 2: ' + parseFloat(sell_stock) + ' - ' + stock_fifo[sell_fifo_i] + ' = ' + (parseFloat(sell_stock) - parseFloat(stock_fifo[sell_fifo_i])));
                                sell_stock = (parseFloat(sell_stock) - parseFloat(stock_fifo[sell_fifo_i]));
                                stock_fifo[sell_fifo_i] = 0;
                            }
                            // console.log('Last Stock[' + sell_fifo_i + '] = ' + stock_fifo[sell_fifo_i]);
                        }
                        sell_fifo_i++;
                    });
                }
            });
            
            var investment_capital = 0;
            sell_fifo_i = 0;
            $.each(stock_fifo, function(index_fifo, value_fifo){
                // console.log('Investment: ' + parseFloat(investment_capital) + ' + ' + parseFloat(value_fifo) + ' X ' + parseFloat(price_fifo[sell_fifo_i]) + ' = ' + (parseFloat(investment_capital) + (parseFloat(value_fifo) * parseFloat(price_fifo[sell_fifo_i]))));
                
                investment_capital = parseFloat(investment_capital) + (parseFloat(value_fifo) * parseFloat(price_fifo[sell_fifo_i]));
                sell_fifo_i++;
            });
            
            // console.log('----------')
            table_trasnsaction += '</table>';
            table_trasnsaction += '<b>Investment Capital: ' + numberWithCommas(investment_capital.toFixed(0)) + ' IDR</b>';
            
            var idr_value_number = $('#hidden_idr_value_' + str_currency).val();
            console.log('#hidden_idr_value_' + str_currency);
            table_trasnsaction += '<br><br>Current IDR Value: ' + numberWithCommas(idr_value_number) + ' IDR';
            table_trasnsaction += '<br>Estimates Profit/Loss (%): <b>' + ((parseFloat(idr_value_number) - parseFloat(investment_capital.toFixed(0))) / parseFloat(investment_capital.toFixed(0)) * 100).toFixed(2) + ' %</b>';
            
            $('#transactionListModal .modal-body').html(table_trasnsaction);
        });
        
        request.fail(function( jqXHR, textStatus ) {
            alert( "Get trade history failed: " + textStatus );
        });
    });
    
    $('.refresh-idr-value').on('click', function(){
        update_idr_value();
    });
    
    $('#pendingListModal').on('show.bs.modal', function (e) {
        $('#pendingListModal .modal-body').html('(loading...)');
        
        var request_pending = $.ajax({
            url: 'index.php?method=openOrders',
            method: "POST",
            //data: {pair: value + '_idr'},
            dataType: "json"
        });
        
        request_pending.done(function( msg ) {
            var table_pending = '';
            
            if(msg.success == 1){
                $.each(msg.return.orders, function( index, value ) {
                    var str_split = index.split('_');
                    var str_currency = str_split[0];
                    str_currency_upper = str_currency.toUpperCase();
                    
                    table_pending += '<h3>' + str_currency_upper + ' - IDR</h3><table class="table"><tr><td>Type</td><td>Order ID</td><td>Time</td><td>Price</td><td>Order IDR/' + str_currency_upper + '</td><td>Estimasi</td></tr>';
                    
                    $.each(value, function( index_row, value_row ) {
                        if(value_row.type == 'buy'){
                            // console.log('buy');
                            table_pending += '<tr><td>' + value_row.type + '</td><td>' + value_row.order_id + '</td><td>' + moment.unix(value_row.submit_time).format("DD MMM YYYY HH:mm:ss") + '</td><td>' + numberWithCommas(value_row.price) + '</td><td>' + numberWithCommas(value_row.order_idr) + '</td><td>' + numberWithCommas((parseFloat(value_row.order_idr) / parseFloat(value_row.price)).toFixed(8)) + '</td><td></tr>';
                        }else if(value_row.type == 'sell'){
                            // console.log('sell');
                            table_pending += '<tr><td>' + value_row.type + '</td><td>' + value_row.order_id + '</td><td>' + moment.unix(value_row.submit_time).format("DD MMM YYYY HH:mm:ss") + '</td><td>' + numberWithCommas(value_row.price) + '</td><td>' + numberWithCommas(value_row['order_' + str_currency]) + '</td><td>' + numberWithCommas((parseFloat(value_row['order_' + str_currency]) * parseFloat(value_row.price)).toFixed(0)) + '</td><td></tr>';
                        }
                    });
                    
                    table_pending += '</table>';
                });
                
                $('#pendingListModal .modal-body').html(table_pending);
            }else{
                $('#pendingListModal .modal-body').html('No data.');
            }
        });
        
        request_pending.fail(function( jqXHR, textStatus ) {
            alert( "Update " + value + " IDR value failed: " + textStatus );
        });
    });
    
    $('.pending-list-btn').on('click', function(){
        $('#pendingListModal').modal('show');
    });
});

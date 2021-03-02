function paypalCreate(grand_total, callback) {


    paypal.Button.render({

        env: ($('#mw-env').data('value') === 'production') ? 'production' : 'sandbox', // Or 'sandbox',
        locale: 'th_TH',
        style: {
            size: 'responsive',
            color: 'gold',
            shape: 'rect',
            label: 'pay'
        },
        client: {
            sandbox: 'AWEpHQ0NoC70eeMVX1mkO-9J04McafWIY3YVmDdnqhGxmtC8fdBTrbvN5MdOeBZcvhfBonptOChh_k2z',
            production: 'AY3r2wYvyiafJWRo-oy71YMbDOfvW41_7mBpnaEpgqQ9dMprg5mLLBgHephydS8bHFZMKFNHC8pHSEsk'
        },
        commit: true,
        payment: function(data, actions) {

            // Set up the payment here
            return actions.payment.create({
                transactions: [
                    {
                        amount: { total: grand_total, currency: 'THB' }
                    }
                ]
            });
        },

        onAuthorize: function(data, actions) {

            return actions.payment.execute().then(function(payment) {

                callback(payment);
                // The payment is complete!
                // You can now show a confirmation message to the customer
            });
        }

    }, '#paypal-button');
}
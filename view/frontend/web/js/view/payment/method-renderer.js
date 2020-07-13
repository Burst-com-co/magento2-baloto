define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'baloto',
                component: 'Burst_Baloto/js/view/payment/method-renderer/baloto'
            }
        );
        return Component.extend({});
    }
);
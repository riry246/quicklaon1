import './bootstrap';
import '@popperjs/core';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'choices.js/public/assets/scripts/choices.min.js';
import 'chart.js';
import 'sticky-js';
import 'nouislider';
import 'wnumb/wNumb.min.js';
import $ from 'jquery';
window.$ = window.jQuery = $;
import { createApp } from 'vue';

import ExampleComponent from './components/ExampleComponent.vue';
import LoanDetailComponent from './components/LoanDetailComponent.vue';
import MobileVerifyComponent from './components/MobileVerifyComponent.vue';
import ApplicationComponent from './components/ApplicationComponent.vue';
import CustomerDashboardComponent from './components/CustomerDashboardComponent.vue';
import ContractSigningAdminComponent from './components/widgets/ContractSigningAdminComponent.vue';
import ApplyComponent from './components/ApplyComponent.vue';




const app = createApp({});
app.component('example-component', ExampleComponent);
app.component('loandetail-component', LoanDetailComponent);
app.component('mobileverify-component', MobileVerifyComponent);
app.component('application-component', ApplicationComponent);
app.component('customer-dashboard-component', CustomerDashboardComponent);
app.component('contract-signing-admin-component', ContractSigningAdminComponent);
app.component('apply-component', ApplyComponent);
app.mount('#app');


document.tidioChatCode = 'dtqf4lmhwlkxrd3fmxeiz38kddyhesay';

function asyncLoad() {
    var tidioScript = document.createElement('script');
    tidioScript.type = 'text/javascript';
    tidioScript.async = true;
    tidioScript.src = `//code.tidio.co/${document.tidioChatCode}.js`;
    document.body.appendChild(tidioScript);
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.attachEvent) {
        window.attachEvent('onload', asyncLoad);
    } else {
        window.addEventListener('load', asyncLoad, false);
    }

    // Add a click event listener to the button
    $('.chat-button').click(() => {
        window.tidioChatApi.show();
        window.tidioChatApi.open();
    });
});
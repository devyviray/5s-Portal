
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// Dashboard
Vue.component('dashboard-index', require('./components/Dashboard/DashboardIndex.vue').default);

// Department
Vue.component('department-index', require('./components/Department/DepartmentIndex.vue').default);

// Location
Vue.component('location-index', require('./components/Location/LocationIndex.vue').default);

// Company
Vue.component('company-index', require('./components/Company/CompanyIndex.vue').default);

// User
Vue.component('user-index', require('./components/User/UserIndex.vue').default);

// Role
Vue.component('role-index', require('./components/Role/RoleIndex.vue').default);

// Checklist
Vue.component('checklist-index', require('./components/Checklist/ChecklistIndex.vue').default);

// Faq
Vue.component('faq-index', require('./components/Faq/FaqIndex.vue').default);

// Faq front page
Vue.component('faq-index-page', require('./components/Faq/FaqIndexPage.vue').default);

// Faq front page
Vue.component('report-index', require('./components/Report/ReportIndex.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});


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

// Homepage
Vue.component('home-page-index', require('./components/HomePage/HomePageIndex.vue').default);

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

// Contact us
Vue.component('contact-us-index', require('./components/ContactUs/ContactUsIndex.vue').default);

// Report page
Vue.component('report-index', require('./components/Report/ReportIndex.vue').default);
Vue.component('report-form', require('./components/Report/ReportForm.vue').default);
Vue.component('report-view', require('./components/Report/ReportView.vue').default);
Vue.component('report-verified', require('./components/Report/ReportVerified.vue').default);
Vue.component('report-edit', require('./components/Report/ReportEdit.vue').default);
Vue.component('report-trend', require('./components/Report/ReportTrend.vue').default);
Vue.component('report-draft', require('./components/Report/ReportDraft.vue').default);

// operation line page
Vue.component('operation-line-index', require('./components/Operation/OperationLineIndex.vue').default);

// company operation line page
Vue.component('company-operation-line-index', require('./components/CompanyOperationLine/CompanyOperationLineIndex.vue').default);

// category page
Vue.component('category-index', require('./components/Category/CategoryIndex.vue').default);

// area page
Vue.component('area-index', require('./components/Area/AreaIndex.vue').default);

// company area page
Vue.component('company-area-index', require('./components/CompanyArea/CompanyAreaIndex.vue').default);


// components
Vue.component('pageHeader', require('./components/PageHeader.vue').default);
Vue.component('navbarRight', require('./components/NavbarRight.vue').default);
Vue.component('breadcrumb', require('./components/Breadcrumb.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});

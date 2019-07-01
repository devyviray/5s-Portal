<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, {{ this.userName }}</span>
                </div>
                <div class="col-md-1">
                    <navbarRight :user-role-level="userRoleLevel" :user-id="userId"></navbarRight>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="div-spacing"></div>     
            <div class="header">
                <h1 class="page-header">
                    <img class="lafil-logo" :src="logoLink">
                    <b>5S PORTAL - FAQs</b>
                </h1>
                <breadcrumb :user-role-level="userRoleLevel"></breadcrumb>
            </div>
            <div id="page-inner">
                <div id="accordion">
                    <div class="card" v-for="(faq, q) in faqs" :key="q">
                        <div class="card-header" :id="'heading-'+q">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" :data-target="'#collapse-'+q" aria-expanded="true" :aria-controls="'collapse-'+q">
                            {{ faq.question }}
                            </button>
                        </h5>
                        </div>

                        <div :id="'collapse-'+q" class="collapse" :aria-labelledby="'heading-'+q" data-parent="#accordion">
                        <div class="card-body">
                            {{ faq.answer }}
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
    import Multiselect from 'vue-multiselect';
    import  navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import loader from '../Loader';
    export default {
        props: ['userName', 'userRoleLevel', 'userId'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                faqs : [],
                faq: [],
                faq_copied: [],
                faq_id: '',
                faq_added: false,
                faq_updated: false,
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
            }
        },
        created(){
            this.fetchFAQs();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            fetchFAQs(){
                axios.get('faqs-all')
                .then(response => {
                    this.faqs = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            } 
        },
        computed:{
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
        }
    }
</script>

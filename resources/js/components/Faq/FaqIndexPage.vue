<template>
    <div id="wrapper">
         <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, {{ this.userName }}</span>
                </div>
                <div class="col-md-1">
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li> <a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a> </li>
                                <li> <a :href="userLink"><i class="fa fa-users fa-fw"></i> Users</a> </li>
                                <li> <a :href="companyLink"><i class="fa fa-building-o fa-fw"></i> Companies</a> </li>
                                <li> <a :href="locationLink"><i class="fa fa-globe fa-fw"></i> Locations</a> </li>
                                <li> <a :href="departmentLink"><i class="fa fa-trello fa-fw"></i> Departments</a> </li>
                                <li> <a :href="roleLink"><i class="fa fa-user-md fa-fw"></i> Roles</a> </li>
                                <li> <a :href="checklistLink"><i class="fa fa-list-ol"></i> Checklist</a> </li>
                                <li> <a :href="faqLink"><i class="fa fa-info-circle"></i> FAQs</a> </li>
                                <li class="divider"></li>
                                <li> <a href="#" @click="logoutForm"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
                            </ul>
                        </li>
                    </ul>
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
                <ol class="breadcrumb">
                    <li><a :href="homeLink">Home</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Report & Rating</a></li> <span  style="color: #FFFF">|</span>
                    <li><a href="#">Orientation</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Exam</a></li><span style="color: #FFFF">|</span>
                    <li><a :href="faqPageLink">FAQs</a></li>
                </ol>
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
    export default {
        props: ['userName'],
        components:{
            Multiselect
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
            }
        },
        created(){
            this.fetchFAQs();
        },
        methods:{
            logoutForm(){
                axios.post('/logout')
                .then(response =>{
                    if(response.status == 200){
                        window.location.href = window.location.origin+'/login';
                    }
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
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
            homeLink(){
                return window.location.origin+'/home'
            },
            departmentLink(){
                return window.location.origin+'/departments'
            },
            locationLink(){
                return window.location.origin+'/locations'
            },
            companyLink(){
                return window.location.origin+'/companies'
            },
            userLink(){
                return window.location.origin+'/users'
            },
            roleLink(){
                return window.location.origin+'/roles'     
            },
            checklistLink(){
               return window.location.origin+'/checklists'
            },
            faqLink(){
                return window.location.origin+'/faqs' 
            },
            faqPageLink(){
                return window.location.origin+'/faqs-page' 
            },
        }
    }
</script>

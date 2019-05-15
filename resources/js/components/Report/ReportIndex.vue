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
                    <b>5S PORTAL - REPORT & RATING </b>
                </h1>
                <ol class="breadcrumb">
                    <li><a :href="homeLink">Home</a></li><span style="color: #FFFF">|</span>
                    <li><a :href="reportLink">Report & Rating</a></li> <span  style="color: #FFFF">|</span>
                    <li><a href="#">Orientation</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Exam</a></li><span style="color: #FFFF">|</span>
                    <li><a :href="faqPageLink">FAQs</a></li>
                </ol>
            </div>
            <div id="page-inner">
                <div class="card">
                    <div class="card-header">
                        <div class="row ml-2">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">Company</label>
                                    <select class="form-control" v-model="company_id">
                                        <option v-for="(company,c) in companies" v-bind:key="c" :value="company.id"> {{ company.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.company  ">{{ errors.company[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">Company Location</label>
                                    <select class="form-control" v-model="location_id">
                                        <option v-for="(location,l) in locations" v-bind:key="l" :value="location.id"> {{ location.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.location  ">{{ errors.location[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">Operation Line</label>
                                    <select class="form-control">
                                        <option></option>
                                        <option>PASTA LINE</option>
                                        <option>SAUCE LINE</option>
                                    </select>
                                    <!-- <span class="text-danger" v-if="errors.location  ">{{ errors.location[0] }}</span> -->
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">Category</label>
                                    <select class="form-control">
                                        <option></option>
                                        <option>OPERATIONS</option>
                                        <option>SUPPORT</option>
                                        <option>OFFICES</option>
                                    </select>
                                    <!-- <span class="text-danger" v-if="errors.location  ">{{ errors.location[0] }}</span> -->
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">AREA</label>
                                    <select class="form-control">
                                        <option></option>
                                        <option>6F-Silo Area</option>
                                        <option>GF-5F Silo Area</option>
                                        <option>Long Goods Production Line</option>
                                        <option>Short Goods Production Line</option>
                                        <option>Die Wash Area</option>
                                    </select>
                                    <!-- <span class="text-danger" v-if="errors.location  ">{{ errors.location[0] }}</span> -->
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-primary mt-4" @click="fetchReports"> Go</button>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>

                    <div class="card-body">
                       <div class="row">
                           <div class="col-md-2">
                               <a>View Report</a>
                           </div>
                           <div class="col-md-2">
                               <a>Trend Analysis(Excel file)</a>
                           </div>
                           <div class="col-md-2">
                                <a>Performance Evaluation Rating</a>
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
                companies : [],
                locations: [],
                company_id: '',
                location_id: '',
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchLocations();
            this.fetchReports();
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
            fetchCompanies(){
                axios.get('companies-all')
                .then(response => {
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            fetchLocations(){
                axios.get('locations-all')
                .then(response => {
                    this.locations = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            fetchReports(){

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
            reportLink(){
                return window.location.origin+'/reports'
            }
        }
    }
</script>

<template>
    <div id="wrapper">
         <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, {{ this.userName }}</span>
                </div>
                <div class="col-md-1">
                    <navbarRight></navbarRight>
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
                <breadcrumb></breadcrumb>
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
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    export default {
        props: ['userName'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb
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
        }
    }
</script>

<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-8">
                            <span class="span-username">Hi, {{ this.userName }}</span>
                        </div>
                        <div class="col-md-4">
                            <navbarRight :user-role-level="userRoleLevel" :user-id="userId"></navbarRight>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="div-spacing"></div>     
            <div class="header">
                <h1 class="page-header">
                    <img class="lafil-logo" :src="logoLink">
                    <b>5S PORTAL - CONTACT US</b>
                </h1>
                <breadcrumb :user-role-level="userRoleLevel"></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card">
                    <div class="card-body"> 
                        <div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-3 card" style="background-color: #A8C897;">
                                    <div class="card-body text-center"><br><br><br>
                                        <h1 class="text-dark">CISCO Local Nos.:</h1><br><br>
                                        <h1 class="text-dark">Manila – 2414 – 2415</h1>
                                        <h1 class="text-dark">Iloilo – 3931 – 3932</h1>
                                        <h1 class="text-dark">Bataan – 7012</h1>
                                        <h1 class="text-dark">Capiz – 5002</h1><br><br>
                                        <h1 class="text-dark">E-mail address:</h1>
                                    </div>
                                    <h1 class="text-dark">tqmmnl-ilogroup@lafiladmin.com</h1><br><br><br>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-9">
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-9 text-center">
                                            <h1> <b>CONTACT US</b> </h1> <br><br>
                                            <div class="form-group row">
                                                <label for="subject" class="col-sm-12 col-md-12 col-lg-4 col-form-label">
                                                    <h1> Subject </h1>
                                                </label>
                                                <div class="col-sm-12 col-md-12 col-lg-8">
                                                    <input type="text" class="form-control" id="subject" v-model="data.subject">
                                                    <span class="text-danger" v-if="errors.subject">{{ errors.subject[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="message" class="col-sm-12 col-md-12 col-lg-4 col-form-label">
                                                    <h1> Message </h1>
                                                </label>
                                                <div class="col-sm-12 col-md-12 col-lg-8">
                                                    <textarea class="form-control" id="message" rows="7" v-model="data.message"></textarea>
                                                    <span class="text-danger" v-if="errors.message">{{ errors.message[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <button type="button" class="btn btn-primary" @click="contactUs(data)">SUBMIT</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
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
                data: [],
                errors: [],
                loading: false,
            }
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            contactUs(data){
                axios.post('/contact-us',{
                    subject: data.subject,
                    message: data.message
                })
                .then(response => {

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

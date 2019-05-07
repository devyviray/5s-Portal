<template>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, Devy Viray</span>
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
                                <li> <a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> </li>
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
                    <b>5S PORTAL - DASHBOARD</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a :href="homeLink">Home</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Report & Rating</a></li> <span  style="color: #FFFF">|</span>
                    <li><a href="#">Orientation</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Exam</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">FAQs</a></li>
                </ol> 
                
            </div>
            <div id="page-inner">
                <carousel 
                    :autoplay="true" 
                    :loop="true"
                    :perPage=1
                    :autoplayTimeout="2500">
                    <slide>
                        <img :src="logoLink" class="carouselImg">
                    </slide>
                    <slide>
                        <img :src="logoLink" class="carouselImg">
                    </slide>
                    <slide>
                        <img :src="logoLink" class="carouselImg">
                    </slide>
                    <slide>
                        <img :src="logoLink" class="carouselImg">
                    </slide>
                    <slide>
                        <img :src="logoLink" class="carouselImg">
                    </slide>
                    <slide>
                        <img :src="logoLink" class="carouselImg">
                    </slide>
                </carousel>
            </div>
        </div>
    </div>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';
    export default {
        components: {
            'carousel': Carousel,
            'slide': Slide
        },
        data(){
            return { 
                errors: [],
            }
        },
        created(){

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
            }
        }
    }
</script>


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
                                <li> <a href="#"><i class="fa fa-users fa-fw"></i> Users</a> </li>
                                <li> <a href="#"><i class="fa fa-building-o fa-fw"></i> Companies</a> </li>
                                <li> <a href="#"><i class="fa fa-globe fa-fw"></i> Locations</a> </li>
                                <li> <a href="#"><i class="fa fa-trello fa-fw"></i> Departments</a> </li>
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
                    <li><a href="#">Home</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Report & Rating</a></li> <span  style="color: #FFFF">|</span>
                    <li><a href="#">Orientation</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">Exam</a></li><span style="color: #FFFF">|</span>
                    <li><a href="#">FAQs</a></li>
                </ol> 
                
            </div>
            <div id="page-inner">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <div class="item active">
                        <img :src="logoLink" alt="Los Angeles" style="width:100%;">
                        </div>
                        <div class="item">
                        <img :src="logoLink" alt="Chicago" style="width:100%;">
                        </div>
                        <div class="item">
                        <img :src="logoLink" alt="New york" style="width:100%;">
                        </div>
                    </div>
                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
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
        }
    }
}
</script>


<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-8">
                    <img class="mt-2 ml-2 mb-2" :src="publicPath +'/img/new_design/5sPortal.png'">
                </div>
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
                    <img class="lafil-logo" :src="publicPath + '/img/lafil-logo.png'">
                    <b>5S PORTAL - HOME PAGE</b>
                </h1>
                <breadcrumb :user-role-level="userRoleLevel"></breadcrumb>
            </div>
            <div id="page-inner">
                <carousel 
                    :loop="true"
                    :perPage=1
                    :autoplayTimeout="2500">
                    <slide v-for="(image, i) in images" :key="i">
                        <img :src="imageLink+image.file_path" class="carouselImg">
                    </slide>
                </carousel>
            </div>
        </div>
    </div>
</template>

<script>
    import { Carousel, Slide } from 'vue-carousel';
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import loader from '../Loader'
    export default {
        props: ['userName','userRoleLevel', 'userId'],
        components: {
            'carousel': Carousel,
            'slide': Slide,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                images: [], 
                errors: [],
                loading: false,
            }
        },
        created(){
            this.fetchImages();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            fetchImages(){
                axios.get('home-page-all')
                .then(response => {
                    this.images = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
        },
        computed:{
            publicPath(){
                return window.location.origin;
            },
            imageLink(){
                 return window.location.origin+'/storage/';
            }
        }
    }
</script>


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
                    <b>5S PORTAL - DASHBOARD</b>
                </h1>
                <breadcrumb></breadcrumb>
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
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    export default {
        props: ['userName'],
        components: {
            'carousel': Carousel,
            'slide': Slide,
            navbarRight,
            breadcrumb
        },
        data(){
            return { 
                errors: [],
            }
        },
        computed:{
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
        }
    }
</script>


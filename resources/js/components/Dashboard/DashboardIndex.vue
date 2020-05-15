<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <page-header></page-header>
        <div id="page-wrapper" class="h-100">
            <div>
                <breadcrumb :user-role-level="userRoleLevel" :user-id="userId"></breadcrumb>
            </div>
            <div class="row row-margin h-100">
                <div class="col-md-3">
                    <div class="w-100 mt-5">
                        <div class="text-center" style="margin-bottom: 85px;">
                            <h1 style="color: #003300;">Hi, {{ this.userName }}!</h1>
                        </div>
                        <div class="contact-us">
                            <img :src="publicPath +'/img/new_design/contact_us.png'">
                            <div class="form-group">
                                <div class="col-sm-12 col-md-12 col-lg-8" style="margin: 0 auto;">
                                    <input type="text" class="form-control" id="subject" placeholder="Subject">
                                    <span class="text-danger" v-if="errors.subject">{{ errors.subject[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 col-md-12 col-lg-8" style="margin: 0 auto;">
                                    <textarea class="form-control" id="message" rows="7" placeholder="Message"></textarea>
                                    <span class="text-danger" v-if="errors.message">{{ errors.message[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <button type="button" class="btn btn-success">SUBMIT</button>
                                </div>
                            </div>
                            <div>
                                <p class="text-center">
                                    CISCO Local Nos.:<br>
                                    Manila – 2414 – 2415<br>
                                    Iloilo – 3931 – 3932<br><br>

                                    E-mail address:<br>
                                    5sPortal@mail.lafilgroup.com
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="page-inner">
                        <carousel 
                            :loop="true"
                            :perPage=1
                            :autoplayTimeout="2500">
                            <slide v-for="(image, i) in images" :key="i">
                                <img :src="imageLink+image.file_path" class="carouselImg h-100 w-100">
                            </slide>
                        </carousel>
                    </div>
                    <div style="height: 5%;" class="text-center">
                        <h1 style="color: #003300;">WELCOME TO 5S PORTAL!</h1>
                    </div>
                    <div id="page-inner2">
                        <div class="row  h-75">
                            <div class="col-md-6">
                                <img class="h-100 w-100" :src="publicPath +'/img/new_design/dashboard1.png'">
                            </div>
                            <div class="col-md-6">
                                <img class="h-100 w-100" :src="publicPath +'/img/new_design/dashboard1.png'">
                            </div>
                        </div>
                        <div class="row h-25" style="background-color: #c3d69b; margin-top: 10px">
                            <div class="col-md-6 center-content">
                                <h3>Conduct your self inspection</h3>
                            </div>
                            <div class="col-md-6 center-content">
                                <h3><a href="#"><i class="fas fa-file-signature"></i>Download checklist here</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 center-content h-100">
                    <div class="w-100 h-100 text-center">
                        <div id="page-inner" class="h-100">
                            <div class="ranking">
                                <h3 class="text-success border-bottom">RANKINGS FOR THE MONTH</h3>
                                <h2 class="text-success border-bottom"><strong>APRIL</strong></h2>
                                <div class="row">
                                    <div class="col-md-3">1st</div>
                                    <div class="col-md-9">PFMC-FLOUR</div>
                                </div>
                                   <div class="row">
                                    <div class="col-md-3">1st</div>
                                    <div class="col-md-9">PFMC-FLOUR</div>
                                </div>
                                   <div class="row">
                                    <div class="col-md-3">1st</div>
                                    <div class="col-md-9">PFMC-FLOUR</div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Carousel, Slide } from 'vue-carousel';
    import pageHeader from '../PageHeader';
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import loader from '../Loader'
    export default {
        props: ['userName','userRoleLevel', 'userId'],
        components: {
            'carousel': Carousel,
            'slide': Slide,
            pageHeader,
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


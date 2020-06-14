<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <page-header></page-header>
        <div>
            <div>
                <breadcrumb :user-role-level="userRoleLevel" :user-id="userId"></breadcrumb>
            </div>
            <div class="row row-margin">
                <div class="col-md-3">
                    <div class="alert alert-success text-center" v-if="email_sent">
                        <strong>Success!</strong> Email Succeffully sent
                    </div>
                    <div class="w-100 mt-5">
                        <div class="text-center" style="margin-bottom: 85px;">
                            <h1 style="color: #003300;">Hi, {{ this.userName }}!</h1>
                        </div>
                        <div class="contact-us">
                            <img :src="publicPath +'/img/new_design/contact_us.png'">
                            <div class="form-group">
                                <div class="col-sm-12 col-md-12 col-lg-8" style="margin: 0 auto;">
                                    <input type="text" class="form-control" id="subject" placeholder="Subject" v-model="contactUs.subject">
                                    <span class="text-danger" v-if="errors.subject">{{ errors.subject[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 col-md-12 col-lg-8" style="margin: 0 auto;">
                                    <textarea class="form-control" id="message" rows="7" placeholder="Message" v-model="contactUs.message"></textarea>
                                    <span class="text-danger" v-if="errors.message">{{ errors.message[0] }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-success" @click="contactUs">SUBMIT</button>
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
                                <img :src="imageLink+image.file_path" class="carouselImg w-100">
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
                                <h3><a href="#" data-toggle="modal" data-target="#submitModal"><i class="fas fa-file-signature"></i> Download checklist here</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="w-100 text-center">
                        <div id="page-inner">
                            <div class="ranking">
                                <h3 class="text-success border-bottom">RANKINGS FOR THE MONTH</h3>
                                <h2 class="text-success border-bottom"><strong>{{ moment().subtract(1, "month").format('MMMM').toUpperCase() }}</strong></h2>
                                <div v-if="final_rating.length > 0">
                                    <div class="row mb-2" v-for="(final, f) in final_rating" :key="f">
                                        <div class="col-md-3">{{ toOrdinal(f+1) }}</div>
                                        <div class="col-md-9 text-left">{{ final.company }}</div>
                                    </div>
                                </div>
                                <div class="row" v-else>
                                    <div class="col-md-12">No data available</div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>


        <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Download Checklist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="role">Please select Checklist</label> 
                            <select class="form-control" v-model="selected_checklist" @change="fetchChecklist">
                                <option value="Operations">Operation and Support Checklist</option>
                                <option value="Offices"> Office Checklist </option>
                                <option value="Perimeter"> Perimeter Checklist </option>
                            </select>
                            <span class="text-danger" v-if="errors.role">{{ errors.role[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <download-excel v-if="checklists.length > 0"
                        :data   = "checklists"
                        :fields = "json_fields"
                        class   = "btn btn-sm btn-success"
                        :name    =  "checklist_name">
                        EXPORT TO EXCEL
                    </download-excel>
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
    import loader from '../Loader';
    import converter from 'number-to-words';
    import moment from 'moment';
    import JsonExcel from 'vue-json-excel';
    export default {
        props: ['userName','userRoleLevel', 'userId'],
        components: {
            'carousel': Carousel,
            'slide': Slide,
            pageHeader,
            navbarRight,
            breadcrumb,
            loader,
            'downloadExcel': JsonExcel 
        },
        data(){
            return {
                reports: [],
                final_rating: [],
                contact_us: [],
                images: [], 
                errors: [],
                loading: false,
                email_sent: false,
                selected_checklist: '',
                checklists: [],
                checklist_name: '',
                json_fields: {
                    'REQUIREMENT': {
                        callback: (value) => {
                            return value.requirement;
                        }
                    },
                    'DESCRIPTION': {
                        callback: (value) => {
                            return value.description;
                        }
                    }
                }
            }
        },
        created(){
            this.fetchImages();
            this.fetchPreviousMonthRating();
        },
        methods:{
            moment,
            showLoader(){
               this.loading = true;
            },
            toOrdinal(num){
                 return converter.toOrdinal(num);
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
            fetchPreviousMonthRating(){
                axios.get('/report-previous-month-ranking')
                .then(response => { 
                    this.reports = Object.values(response.data);
                    if(this.reports.length > 0){
                        this.reports.filter(report => {
                            var total_areas = 0;
                            var total_ratings = 0;
                            report.filter(r => {
                                total_areas = total_areas + 1;
                                total_ratings = parseFloat(total_ratings) + parseFloat(r.ratings);
                            });
                            this.final_rating.push({
                                company: report[0].company.name +' - '+ report[0].location.name,
                                rating: this.numberFormat(total_ratings / total_areas)
                            })
                            this.final_rating.sort((a, b) => b.rating - a.rating);
                        });
                    }
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
            },
            numberFormat(num){
                if(num){
                    return Number(parseFloat(num).toFixed(2)).toLocaleString('en', { minimumFractionDigits: 2 });
                }
            },
            contactUs(){
                this.email_sent = false;
                this.loading = true;
                this.errors = [];
                axios.post('/contact-us',{
                    subject: this.contactUs.subject,
                    message: this.contactUs.message
                }).then(response => {
                    this.contactUs = [];
                    this.email_sent = true;
                    this.loading = false;
                }).catch(error => { 
                    this.errors = error.response.data.errors;
                    this.loading = false;
                })
            },
            fetchChecklist(){
                this.errors = [];
                this.loading = true;
                axios.get(`/checklists-per-category/${this.selected_checklist}`)
                .then(response => {
                    this.checklists = response.data;
                    this.checklist_name = this.selected_checklist == 'Operations' ? 'Operation and Support Checklist.xls' : this.selected_checklist+' Checklist.xls';
                    this.loading = false;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                    this.loading = false;
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


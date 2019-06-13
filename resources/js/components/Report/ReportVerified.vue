<template>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, {{ this.userName }}</span>
                </div>
                <div class="col-md-1">
                    <navbarRight :user-role="userRole"></navbarRight>
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
                    <div class="card-body"> 
                        <div>
                            <div class="row" v-if="reportsPerUser.length">
                                <div class="col-md-3 card card-view-report" style="height: 770px">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <h1 class="col-sm-12 ">{{ this.reportsPerUser[0].category.name }}</h1>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-sm-4 ">Company:</span>
                                            <div class="col-sm-8">
                                                <span> {{ this.reportsPerUser[0].company.name }} </span>
                                            </div>
                                        </div>
                                        <div class="form-group row" v-if="this.reportsPerUser[0].operation_line">
                                            <span class="col-sm-4 ">Operation Line:</span>
                                            <div class="col-sm-8">
                                                <span> {{ this.reportsPerUser[0].operation_line.name }} </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-sm-4 ">Area:</span>
                                            <div class="col-sm-8">
                                                <span> {{ this.reportsPerUser[0].area.name }}  </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-sm-4 ">Inspector:</span>
                                            <div class="col-sm-8">
                                                <span> {{ this.reportsPerUser[0].inspector.name }} </span>
                                            </div>
                                        </div>
                                        <div v-if="reportsPerUser.length">
                                            <div class="form-group row">
                                                <span class="col-sm-4">Date of Inspection:</span>
                                                <div class="col-sm-8">
                                                    <span>{{ this.reportsPerUser[0].date_of_inspection }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <span class="col-sm-4">Time</span>
                                                <div class="col-sm-8">
                                                    <span>{{ this.reportsPerUser[0].start_time_of_inspection + ' - ' + this.reportsPerUser[0].end_time_of_inspection  }} </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <h1 class="col-sm-12">Rating:</h1>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-sm-1"></div>
                                                <div class="col-sm-10 div-rating">
                                                    <span>{{ countRating(this.reportsPerUser[0].report_detail) }} %</span>
                                                </div>
                                                <div class="col-sm-1"></div>
                                            </div> 
                                        </div>
                                        <div class="form-group row" v-if="this.reportsPerUser[0].status == 2">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-6">
                                                <button id="btn-approved" class="btn btn-block btn-primary" data-toggle="modal" data-target="#validateModal">Validate</button>
                                            </div>
                                            <div class="col-sm-3"></div>
                                        </div>
                                        <div class="form-group row" v-else>
                                            <div class="alert alert-info col-md-12">
                                                <strong>Success!</strong> Report succesfully validated
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9" style="height: 770px">
                                    <div class="table-responsive" style="height: 770px">
                                        <div class="col-md-12 card mb-3" v-for="(checklist, c) in reportsPerUser[0].report_detail" v-bind:key="c" style="background-color: #e6e6e6 !important; min-height: 300px;">
                                            <div class="card-body">
                                                <span>{{ c + 1 +'. '+checklist.name }}</span><br>
                                                    <select class="form-control select-points" v-model="checklist.points" style="width: 60px" :disabled="reportsPerUser[0].status !=2">
                                                        <option value="0"> 0 </option>
                                                        <option value="1"> 1 </option>
                                                        <option value="2"> 2 </option>
                                                    </select>
                                                    <div v-for="(points_error, p) in points_errors" :key="p">
                                                        <span class="text-danger" v-if="points_error == c"> This field is required </span>
                                                    </div>
                                                <div class="row mt-2 text-center">
                                                    <div class="col-md-3" v-for="(uploadFile, u) in checklist.uploaded_files" :key="u">
                                                        <img class="report-img mb-2"  :src="attachmentLink + uploadFile.file_path"><br>
                                                        <span>{{ c + 1 +'.' }} </span> <span> {{  u + 1  }} </span>
                                                        <input type="text" :id="uploadFile.id" class="form-control comment-input"  placeholder="Comment..." v-model="uploadFile.comment" disabled>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="reportsPerUser.length ">
                                        <div class="col-6">
                                            
                                        </div>
                                        <div class="col-6 text-right">
                                            <span>{{ reportsPerUser.length }} Checklist(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                No report available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validate Report Modal -->
                <div class="modal fade" id="validateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCompanyLabel">Validate Report</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        Are you sure you want to Validate this Report?
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                            <button class="btn btn-primary" @click="validateReport">Validate</button>
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
        props: ['userName', 'userRole' ,'reportId'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb
        },
        data(){
            return {
                reportsPerUser: [],
                comments: [],
                points_errors: [],
                final_rating: '',
                show_forChecking: false,
                errors: [],
                currentPage: 0,
                itemsPerPage: 8,
                keywords: '',
            }
        },
        created(){
            this.fetchReportsPerUser();
        },
        methods:{
            fetchReportsPerUser(){
                axios.get(`/reports-per-user/${this.reportId}`)
                .then(response => {
                    this.reportsPerUser = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            countRating(reportsPerUser){
                var denominator = reportsPerUser.length * 2;
                var total_points = 0;
                reportsPerUser.filter(item => total_points = parseInt(total_points) +  parseInt(item.points));

                return this.final_rating = total_points / denominator * 100;
            },
            validateReport(){
                this.enabledBtn();
                var report_ids = [];
                this.reportsPerUser.filter(item => report_ids.push(item.id));
                let t = this;
                this.points = [];
                this.points_errors = [];
                this.reportsPerUser[0].report_detail.filter(function(item,index) {
                    item.rating !== null ? t.points.push(item.points) : t.points_errors.push(index);
                });

                axios.post('/report-validate', {
                    ids: report_ids,
                    final_rating: this.final_rating,
                    points: this.points
                })
                .then(response => {
                    this.show_approved = true;
                    this.disabledBtn();
                    this.reportsPerUser = response.data;
                    $('#validateModal').modal('hide');
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                    this.enabledBtn();
                })
            },
            disabledBtn(){
                document.getElementById('btn-approved').disabled = true;
            },
            enabledBtn(){
                document.getElementById('btn-approved').disabled = false;
            },
            setPage(pageNumber) {
                this.currentPage = pageNumber;
            },
            resetStartRow() {
                this.currentPage = 0;
            },
            showPreviousLink() {
                return this.currentPage == 0 ? false : true;
            },
            showNextLink() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            }, 
        },
        computed:{
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
            attachmentLink(){
                 return window.location.origin+'/storage/';
            }
        }
    }
</script>

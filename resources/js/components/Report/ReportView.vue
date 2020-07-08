<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <page-header></page-header>
        <div style="background: #EDEDED;">
            <div>
                <breadcrumb :user-role-level="userRoleLevel" :user-id="userId"></breadcrumb>
            </div>
            <div v-if="reportsPerUser[0]">
                <div class="row">
                    <div class="col-md-3 ml-4">
                        <a v-if="this.userRoleLevel.includes(3)" :href="publicPath + '/reports-my-drafts'"><h3><i class="fas fa-chevron-circle-left"></i> Back to Draft</h3></a>
                    </div>
                    <div class="col-md-7 text-center">
                        <h1 class="text-primary">REPORT PREVIEW</h1>
                    </div>
                    <div class="col-md-2"></div>
                </div>
                <div class="row row-margin" v-if="report_submitted">
                    <div class="col-md-11" style="margin: 0 auto">
                        <div class="alert alert-success text-center" role="alert">
                            REPORT SUCCESSFULLY SUBMITTED
                        </div>
                    </div>
                </div>
                <div class="row row-margin" v-if="report_approved">
                    <div class="col-md-11" style="margin: 0 auto">
                        <div class="alert alert-success text-center" role="alert">
                            REPORT SUCCESSFULLY ACCEPTED
                        </div>
                    </div>
                </div>
                <div class="row row-margin" v-if="report_revised">
                    <div class="col-md-11" style="margin: 0 auto">
                        <div class="alert alert-success text-center" role="alert">
                            REPORT SUCCESSFULLY REVISED
                        </div>
                    </div>
                </div>
                <div class="row row-margin" v-if="errors.server_error">
                    <div class="col-md-11" style="margin: 0 auto">
                        <div class="alert alert-danger text-center" role="alert">
                            <span><b> Server Error - </b> {{ errors.server_error }}</span>
                        </div>
                    </div>
                </div>
                <div class="card view-report-div">
                    <div class="row row-margin">
                        <div class="col-md-12 mt-4 mb-2">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="d-flex">
                                        <td class="col-sm-1">Business Unit</td>
                                        <td class="col-sm-2" style="white-space: normal"><strong>: {{ reportsPerUser[0].company.name }}</strong></td>
                                        <td class="col-sm-1">Date of Inspection</td>
                                        <td class="col-sm-2"><strong>: {{ moment(this.reportsPerUser[0].date_of_inspection).format('LL') }}</strong></td>
                                        <td class="col-sm-1">Accompanied by</td>
                                        <td class="col-sm-2"><strong>: {{ reportsPerUser[0].accompanied_by }} </strong></td>
                                        <td class="col-sm-1">Total No. of NC</td>
                                        <td class="col-sm-2"><strong>: {{ countNonCriticality(reportsPerUser[0]) }}</strong></td>
                                    </tr>
                                    <tr class="d-flex">
                                        <td class="col-sm-1">Department</td>
                                        <td class="col-sm-2" style="white-space: normal"><strong>: {{ reportsPerUser[0].department.name }}</strong></td>
                                        <td class="col-sm-1">Time of Inspection</td>
                                        <td class="col-sm-2"><strong>: {{ moment( this.reportsPerUser[0].start_time_of_inspection, "hh").format('LT') + ' - ' + moment( this.reportsPerUser[0].end_time_of_inspection, "hh").format('LT') }}</strong></td>
                                        <td class="col-sm-1">Area Owner</td>
                                        <td class="col-sm-2"><strong>:  {{ reportsPerUser[0].process_owner.name }} </strong></td>
                                        <td class="col-sm-1">Total No. of Crititcal</td>
                                        <td class="col-sm-2"><strong>: {{ countCriticality(reportsPerUser[0]) }}</strong></td>
                                    </tr>
                                    <tr class="d-flex">
                                        <td class="col-sm-1">Area</td>
                                        <td class="col-sm-2"><strong>: {{ reportsPerUser[0].area.name }} </strong></td>
                                        <td class="col-sm-1">Inspector</td>
                                        <td class="col-sm-2"><strong>: {{ reportsPerUser[0].inspector.name }} </strong></td>
                                        <td class="col-sm-1">Department Head</td>
                                        <td class="col-sm-2"><strong>: {{ reportsPerUser[0].department_head.name }}</strong></td>
                                        <td class="col-sm-1 final-rating">Final Rating</td>
                                        <td class="col-sm-2 final-rating"><strong>: {{ countRating(this.reportsPerUser[0].report_detail) }} %</strong></td>
                                    </tr>   
                                </tbody>
                            </table>
                        </div>
                        <div class="row text-right">
                            <div class="col-md-12">
                                <a :href="publicPath+'/report-inspection-history/'+reportsPerUser[0].id" target="_blank"><u> View last 3 inspections</u></a>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2 mb-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="d-flex">
                                        <td class="col-sm-1"></td>
                                        <td class="col-sm-8 text-center">Requirement</td>
                                        <td class="col-sm-1">Criticality</td>
                                        <td class="col-sm-2">Rating</td>
                                    </tr>
                                </thead>
                                <tbody v-for="(checklist, c) in reportsPerUser[0].report_detail" v-bind:key="c">
                                    <tr class="d-flex">
                                        <td class="col-sm-1">{{  c + 1 }}</td>
                                        <td class="col-sm-8" style="white-space:normal">{{ checklist.name }}</td>
                                        <td class="col-sm-1"><strong> {{checklist.criticality }}</strong></td>
                                        <td class="col-sm-2">
                                            <select class="form-control select-points" v-model="checklist.points"  v-if="reportsPerUser[0].inspector_id == userId && reportsPerUser[0].status == 2">
                                                <option value="N/A"> N/A </option>
                                                <option value="0"> 0 </option>
                                                <option value="1"> 1 </option>
                                                <option value="2"> 2 </option>
                                            </select>
                                            <span v-else><strong>{{ checklist.points }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr style="height: 200px">
                                        <td class="col-sm-12">
                                            <div class="row mt-2 text-center">
                                                <div class="col-md-3" v-for="(uploadFile, u) in checklist.uploaded_files" :key="u">
                                                    <a href="#" data-toggle="modal" data-target="#viewImageModal" @click="getLinkAttachment(attachmentLink + uploadFile.file_path)"><img class="report-img mb-2"  :src="attachmentLink + uploadFile.file_path"></a><br>
                                                     <span>{{ uploadFile.description }} </span>
                                                    <!-- <span>{{ c + 1 +'.' }} </span> <span> {{  u + 1  }} </span> -->
                                                    <textarea cols="5" rows="3" v-if="reportsPerUser[0].status == 1 && reportsPerUser[0].process_owner_id == userId" :id="uploadFile.id" class="form-control comment-input"  placeholder="Type response here" v-model="uploadFile.comment"></textarea>
                                                    <div v-else>
                                                        <textarea cols="5" rows="3" v-if="uploadFile.comment" :id="uploadFile.id" class="form-control comment-input"  v-model="uploadFile.comment" disabled></textarea>
                                                    </div>
                                                    <span class="text-danger" v-if="errors['comment.'+c + 1 +'' + u + 1]"> This field is required </span>
                                                </div> 
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row row-margin">
                    <div class="col-md-7 pl-4 mt-3">
                        <div v-if="reportsPerUser[0].status != 4">
                            <span>NOTES:</span><br>
                            <span>If there are for correction on the report, click “I DO NOT ACCEPT” button – to prevent relay of report, this function is one-time only.<br>
                                For any objection on the report, discuss with inspector and agree on the final rating.
                            </span><br><br>
                            <span>If report is acknowledged, click “ACCEPT” button</span><br>
                            <span><strong>You have 24 - 72 hours to respond to the report otherwise, final rating will be posted</strong></span>
                        </div>
                    </div>
                    <div class="col-md-5 mt-3 text-right">
                        <div v-if="reportsPerUser[0].inspector_id == userId && reportsPerUser[0].is_draft">
                            <button data-toggle="modal" data-target="#submitModal" class="btn btn-success btn- btn-lg mr-3"> SUBMIT REPORT</button>
                        </div>
                        <div v-if="reportsPerUser[0].inspector_id == userId && reportsPerUser[0].status == 2">
                            <button data-toggle="modal" data-target="#reviseModal" class="btn btn-success btn- btn-lg mr-3"> REVISE REPORT</button>
                        </div>
                        <div v-if="reportsPerUser[0].process_owner_id == userId && reportsPerUser[0].status == 1 && !reportsPerUser[0].is_draft">
                            <button data-toggle="modal" data-target="#disapproveModal" class="btn btn-danger btn- btn-lg mr-3" id="btn-checking"> I DO NOT ACCEPT</button>
                            <button data-toggle="modal" data-target="#approveModal" class="btn btn-success btn- btn-lg mr-3" id="btn-approved"> I ACCEPT</button>
                        </div>
                        <div v-if="reportsPerUser[0].process_owner_id == userId && reportsPerUser[0].status == 3">
                            <button data-toggle="modal" data-target="#approveModal" class="btn btn-success btn- btn-lg mr-3" id="btn-approved"> I ACCEPT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img class="attachment-img"  :src="attachment_link">
                </div>
                <div class="modal-footer"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Submit Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to submit this Report?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-primary" @click="submitReport">Submit</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reviseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Revise Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    By clicking revise, I confirm that revisions were made in coordination with the area owner
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-primary" @click="reviseReport">Revise</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Accept Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to accept this Report?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-primary" @click="approvedReport">I Accept</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="disapproveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Reason of non-acceptance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row row-margin">
                        <div class="col-md-12">
                            <textarea cols="10" rows="10" class="form-control"  placeholder="Type here reason why report is not accepted" v-model="non_acceptance_reason"></textarea><br>
                            <span class="text-danger" v-if="errors.non_acceptance_reason"> {{ errors.non_acceptance_reason[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-primary" @click="forCheckingReport">Submit</button>
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
import loader from '../Loader';
import moment from 'moment';
export default {
    props: ['userName', 'userRoleLevel' ,'reportId', 'userId'],
    components:{
        Multiselect,
        navbarRight,
        breadcrumb,
        loader
    },
    data(){
        return {
            reportsPerUser: [],
            comments: [],
            final_rating: '',
            report_submitted: false,
            report_approved: false,
            report_revised: false,
            errors: [],
            currentPage: 0,
            itemsPerPage: 8,
            keywords: '',
            loading: false,
            attachment_link: '',
            non_acceptance_reason: '',
            points: []
        }
    },
    created(){
        this.fetchReportsPerUser();
    },
    methods:{
        moment,
        getLinkAttachment(link){
            this.attachment_link = link; 
        },
        showLoader(){
            this.loading = true;
        },
        countNonCriticality(report){
            var nc = 0;
            report.report_detail.filter(report_detail => { 
                if(report_detail.uploaded_files.length > 0){
                    nc = nc + report_detail.uploaded_files.length;
                }
            });
            return nc;
        },
        countCriticality(report){
            var criticality = 0;
            report.report_detail.filter(report_detail => { 
                if(report_detail.criticality == 'Critical'){
                    criticality = criticality + 1;
                }
            });
            return criticality;
        },
        fetchReportsPerUser(){
            axios.get(`/reports-per-user/${this.reportId}`)
            .then(response => {
                this.reportsPerUser = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.errors;
            })
        },
        approvedReport(){
            this.errors = [];
            this.loading = true;
            this.enabledBtn();
            var report_ids = [];
            this.reportsPerUser.filter(item => report_ids.push(item.id))
            let t = this;
            var comment = $(".comment-input").map(function() {
                t.comments.push({
                    id: $(this).attr('id'),
                    text: $(this).val()
                });
            }).get();
            axios.post('/report-approve', {
                ids: report_ids,
                final_rating: this.final_rating,
                comments: t.comments,
            })
            .then(response => {
                this.report_approved = true;
                this.disabledBtn();
                $('#approveModal').modal('hide');
                this.loading = false;
            })
            .catch(error => {
                this.errors = error.response.data.errors
                this.enabledBtn();
                this.loading = false;
            })
        },
        forCheckingReport(){
            this.errors = [];
            this.loading = true;
            this.enabledBtn();
            let t = this;
            var comment = $(".comment-input").map(function() {
                t.comments.push({
                    id: $(this).attr('id'),
                    text: $(this).val()
                });
            }).get();
            
            var report_ids = [];
            this.reportsPerUser.filter(item => report_ids.push(item.id))
            
            axios.post('/report-checking', {
                ids: report_ids, 
                comments: t.comments,
                non_acceptance_reason: t.non_acceptance_reason
            })
            .then(response => { 
                this.disabledBtn();
                this.loading = false;
                $('#disapproveModal').modal('hide');
                
            })
            .catch(error => { 
                this.errors = error.response.data.errors;
                this.enabledBtn();
                this.loading = false;
            })
        },
        submitReport(){
            this.errors = [];
            this.loading = true;
            axios.post('/submit-report',{
                id: this.reportsPerUser[0].id
            })
            .then(response => {
                this.loading = false;
                this.reportsPerUser = response.data;
                this.report_submitted = true;
                $('#submitModal').modal('hide');
            })
            .catch(error => {
                this.loading = false;
                this.errors = error.response.data.errors;
            })
        },
        reviseReport(){
            this.loading = true;
            let t = this;
            this.reportsPerUser[0].report_detail.filter(function(item,index) {
                if(item.points !== null){
                    t.points.push(item.points);
                }
            });
            axios.post('/revise-report',{
                id: this.reportsPerUser[0].id,
                final_rating: this.final_rating,
                points: t.points
            })
            .then(response => {
                this.loading = false;
                this.reportsPerUser = response.data;
                this.report_revised = true;
                $('#reviseModal').modal('hide');
            })
            .catch(error => {
                this.loading = false;
                this.errors = error.response.data.errors;
            })
        },
        countRating(reportsPerUser){
            var denominator = 0;
            var total_points = 0;
            reportsPerUser.filter(item => {
                if(item.points !== 'N/A'){
                    total_points = parseInt(total_points) + parseInt(item.points);
                    denominator = denominator + 1;
                }
            });
            this.final_rating = total_points / (denominator * 2) * 100;
            return  this.final_rating.toFixed(2);
        },
        disabledBtn(){
            document.getElementById('btn-checking').disabled = true;
            document.getElementById('btn-approved').disabled = true;
            document.getElementsByClassName('comment-input').disabled = true;
        },
        enabledBtn(){
            document.getElementById('btn-checking').disabled = false;
            document.getElementById('btn-approved').disabled = false;
            document.getElementsByClassName('comment-input').disabled = false;
        }   
    },
    computed:{
        publicPath(){
            return window.location.origin;
        },
        attachmentLink(){
                return window.location.origin+'/storage/';
        }
    }
}
</script>

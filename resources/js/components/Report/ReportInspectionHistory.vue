<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <page-header></page-header>
        <div style="background: #EDEDED;">
            <div>
                <breadcrumb :user-role-level="userRoleLevel" :user-id="userId"></breadcrumb>
            </div>
            <div>
                <div class="row">
                    <div class="col-md-3 ml-4"></div>
                    <div class="col-md-7 text-center">
                        <h1 class="text-primary">INSPECTION HISTORY</h1>
                    </div>
                    <div class="col-md-2"></div>
                </div>
                <div class="row row-margin mb-3" style="width: 95%">
                    <div class="col-md-2"> Business Unit </div>
                    <div class="col-md-2">: PFMC - FLOUR  ILOILO</div>
                    <div class="col-md-2"> Department </div>
                    <div class="col-md-2">: FLOUR MILL </div>
                    <div class="col-md-2"> Area </div>
                    <div class="col-md-2">: Mill Ground Floor </div>
                </div>
                <div class="card view-report-div">
                    <div class="row row-margin">
                        <div class="col-md-1 mt-4 mb-4">
                            <table class="table table-bordered">
                                <thead v-if="reportsPerUser.length > 0">
                                    <tr v-if="reportsPerUser[0]">
                                        <th :class="view_report_class0" @click="viewReport(0)">{{ formatDate(reportsPerUser[0].date_of_inspection) }}</th>
                                    </tr>
                                    <tr v-if="reportsPerUser[1]">
                                        <th :class="view_report_class1" @click="viewReport(1)">{{ formatDate(reportsPerUser[1].date_of_inspection) }}</th>
                                    </tr>
                                    <tr v-if="reportsPerUser[2]">
                                        <th :class="view_report_class2" @click="viewReport(2)">{{ formatDate(reportsPerUser[2].date_of_inspection) }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-11 mt-4 mb-4" v-if="reportsPerUser.length > 0">
                            <div v-if="reportsPerUser[0] && view_report1">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="d-flex">
                                            <td class="col-sm-1">Business Unit</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[0].company.name }}</strong></td>
                                            <td class="col-sm-1">Date of Inspection</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[0].date_of_inspection }}</strong></td>
                                            <td class="col-sm-1">Accompanied by</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[0].accompanied_by }} </strong></td>
                                            <td class="col-sm-1">Total No. of NC</td>
                                            <td class="col-sm-2"><strong>: 0</strong></td>
                                        </tr>
                                        <tr class="d-flex">
                                            <td class="col-sm-1">Department</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[0].department.name }}</strong></td>
                                            <td class="col-sm-1">Time of Inspection</td>
                                            <td class="col-sm-2"><strong>:  {{ reportsPerUser[0].start_time_of_inspection +' - '+ reportsPerUser[0].end_time_of_inspection }}</strong></td>
                                            <td class="col-sm-1">Area Owner</td>
                                            <td class="col-sm-2"><strong>:  {{ reportsPerUser[0].process_owner.name }} </strong></td>
                                            <td class="col-sm-1">Total No. of Crititcal</td>
                                            <td class="col-sm-2"><strong>: 0</strong></td>
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
                                            <td class="col-sm-2"><strong>{{ checklist.points }}</strong></td>
                                        </tr>
                                        <tr style="height: 200px">
                                            <td class="col-sm-12">
                                                <div class="row mt-2 text-center">
                                                    <div class="col-md-3" v-for="(uploadFile, u) in checklist.uploaded_files" :key="u">
                                                        <a href="#" data-toggle="modal" data-target="#viewImageModal" @click="getLinkAttachment(attachmentLink + uploadFile.file_path)"><img class="report-img mb-2"  :src="attachmentLink + uploadFile.file_path"></a><br>
                                                        <span>{{ uploadFile.description }} </span>
                                                    </div> 
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-if="reportsPerUser[1] && view_report2">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="d-flex">
                                            <td class="col-sm-1">Business Unit</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[1].company.name }}</strong></td>
                                            <td class="col-sm-1">Date of Inspection</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[1].date_of_inspection }}</strong></td>
                                            <td class="col-sm-1">Accompanied by</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[1].accompanied_by }} </strong></td>
                                            <td class="col-sm-1">Total No. of NC</td>
                                            <td class="col-sm-2"><strong>: 0</strong></td>
                                        </tr>
                                        <tr class="d-flex">
                                            <td class="col-sm-1">Department</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[1].department.name }}</strong></td>
                                            <td class="col-sm-1">Time of Inspection</td>
                                            <td class="col-sm-2"><strong>:  {{ reportsPerUser[1].start_time_of_inspection +' - '+ reportsPerUser[1].end_time_of_inspection }}</strong></td>
                                            <td class="col-sm-1">Area Owner</td>
                                            <td class="col-sm-2"><strong>:  {{ reportsPerUser[1].process_owner.name }} </strong></td>
                                            <td class="col-sm-1">Total No. of Crititcal</td>
                                            <td class="col-sm-2"><strong>: 0</strong></td>
                                        </tr>
                                        <tr class="d-flex">
                                            <td class="col-sm-1">Area</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[1].area.name }} </strong></td>
                                            <td class="col-sm-1">Inspector</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[1].inspector.name }} </strong></td>
                                            <td class="col-sm-1">Department Head</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[1].department_head.name }}</strong></td>
                                            <td class="col-sm-1 final-rating">Final Rating</td>
                                            <td class="col-sm-2 final-rating"><strong>: {{ countRating(this.reportsPerUser[1].report_detail) }} %</strong></td>
                                        </tr>   
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="d-flex">
                                            <td class="col-sm-1"></td>
                                            <td class="col-sm-8 text-center">Requirement</td>
                                            <td class="col-sm-1">Criticality</td>
                                            <td class="col-sm-2">Rating</td>
                                        </tr>
                                    </thead>
                                    <tbody v-for="(checklist, c) in reportsPerUser[1].report_detail" v-bind:key="c">
                                        <tr class="d-flex">
                                            <td class="col-sm-1">{{  c + 1 }}</td>
                                            <td class="col-sm-8" style="white-space:normal">{{ checklist.name }}</td>
                                            <td class="col-sm-1"><strong> {{checklist.criticality }}</strong></td>
                                            <td class="col-sm-2"><strong>{{ checklist.points }}</strong></td>
                                        </tr>
                                        <tr style="height: 200px">
                                            <td class="col-sm-12">
                                                <div class="row mt-2 text-center">
                                                    <div class="col-md-3" v-for="(uploadFile, u) in checklist.uploaded_files" :key="u">
                                                        <a href="#" data-toggle="modal" data-target="#viewImageModal" @click="getLinkAttachment(attachmentLink + uploadFile.file_path)"><img class="report-img mb-2"  :src="attachmentLink + uploadFile.file_path"></a><br>
                                                        <span>{{ uploadFile.description }} </span>
                                                    </div> 
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-if="reportsPerUser[2] && view_report3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="d-flex">
                                            <td class="col-sm-1">Business Unit</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[2].company.name }}</strong></td>
                                            <td class="col-sm-1">Date of Inspection</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[2].date_of_inspection }}</strong></td>
                                            <td class="col-sm-1">Accompanied by</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[2].accompanied_by }} </strong></td>
                                            <td class="col-sm-1">Total No. of NC</td>
                                            <td class="col-sm-2"><strong>: 0</strong></td>
                                        </tr>
                                        <tr class="d-flex">
                                            <td class="col-sm-1">Department</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[2].department.name }}</strong></td>
                                            <td class="col-sm-1">Time of Inspection</td>
                                            <td class="col-sm-2"><strong>:  {{ reportsPerUser[2].start_time_of_inspection +' - '+ reportsPerUser[2].end_time_of_inspection }}</strong></td>
                                            <td class="col-sm-1">Area Owner</td>
                                            <td class="col-sm-2"><strong>:  {{ reportsPerUser[2].process_owner.name }} </strong></td>
                                            <td class="col-sm-1">Total No. of Crititcal</td>
                                            <td class="col-sm-2"><strong>: 0</strong></td>
                                        </tr>
                                        <tr class="d-flex">
                                            <td class="col-sm-1">Area</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[2].area.name }} </strong></td>
                                            <td class="col-sm-1">Inspector</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[2].inspector.name }} </strong></td>
                                            <td class="col-sm-1">Department Head</td>
                                            <td class="col-sm-2"><strong>: {{ reportsPerUser[2].department_head.name }}</strong></td>
                                            <td class="col-sm-1 final-rating">Final Rating</td>
                                            <td class="col-sm-2 final-rating"><strong>: {{ countRating(this.reportsPerUser[2].report_detail) }} %</strong></td>
                                        </tr>   
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="d-flex">
                                            <td class="col-sm-1"></td>
                                            <td class="col-sm-8 text-center">Requirement</td>
                                            <td class="col-sm-1">Criticality</td>
                                            <td class="col-sm-2">Rating</td>
                                        </tr>
                                    </thead>
                                    <tbody v-for="(checklist, c) in reportsPerUser[2].report_detail" v-bind:key="c">
                                        <tr class="d-flex">
                                            <td class="col-sm-1">{{  c + 1 }}</td>
                                            <td class="col-sm-8" style="white-space:normal">{{ checklist.name }}</td>
                                            <td class="col-sm-1"><strong> {{checklist.criticality }}</strong></td>
                                            <td class="col-sm-2"><strong>{{ checklist.points }}</strong></td>
                                        </tr>
                                        <tr style="height: 200px">
                                            <td class="col-sm-12">
                                                <div class="row mt-2 text-center">
                                                    <div class="col-md-3" v-for="(uploadFile, u) in checklist.uploaded_files" :key="u">
                                                        <a href="#" data-toggle="modal" data-target="#viewImageModal" @click="getLinkAttachment(attachmentLink + uploadFile.file_path)"><img class="report-img mb-2"  :src="attachmentLink + uploadFile.file_path"></a><br>
                                                        <span>{{ uploadFile.description }} </span>
                                                    </div> 
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-else>
                            <p> No data available in the table</p>
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
        loader,
    },
    data(){
        return {
            reportsPerUser: [],
            errors: [],
            loading: false,
            attachment_link: '',
            view_report1: false,
            view_report2: false,
            view_report3: false,
            view_report_class0: 'final-rating',
            view_report_class1: 'th-inspection-report',
            view_report_class2: 'th-inspection-report',
        }
    },
    created(){
        this.fetchReportsPerUser();
    },
    methods:{
        moment,
        viewReport(index){
            switch(index) {
                case 0:
                    this.view_report1 = true;
                    this.view_report2 = false;
                    this.view_report3 = false;
                    this.view_report_class0 = 'final-rating';
                    this.view_report_class1 = 'th-inspection-report';
                    this.view_report_class2 = 'th-inspection-report';
                    break;
                case 1:
                    this.view_report1 = false;
                    this.view_report2 = true;
                    this.view_report3 = false;
                    this.view_report_class0 = 'th-inspection-report';
                    this.view_report_class1 = 'final-rating';
                    this.view_report_class2 = 'th-inspection-report';
                    break;
                case 2:
                    this.view_report1 = false;
                    this.view_report2 = false;
                    this.view_report3 = true;
                    this.view_report_class0 = 'th-inspection-report';
                    this.view_report_class1 = 'th-inspection-report';
                    this.view_report_class2 = 'final-rating';
                    break;
                default:
                    this.view_report1 = false;
                    this.view_report2 = false;
                    this.view_report3 = false;
                    this.view_report_class0 = 'final-rating';
                    this.view_report_class1 = 'th-inspection-report';
                    this.view_report_class2 = 'th-inspection-report';
            }
        },
        formatDate(date){
            return moment(date).format('LL');
        },
        getLinkAttachment(link){
            this.attachment_link = link; 
        },
        showLoader(){
            this.loading = true;
        },
        fetchReportsPerUser(){
            axios.get(`/report-inspection-history-data/${this.reportId}`)
            .then(response => {
                this.reportsPerUser = response.data;
                this.view_report1 = this.reportsPerUser.length > 0 ?  true : false;
            })
            .catch(error => { 
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

<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <page-header></page-header>
        <div id="page-wrapper">
            <div>
                <breadcrumb :user-role-level="userRoleLevel" :user-id="userId"></breadcrumb>
            </div>
            <div>
                <div class="row row-margin">
                    <div class="col-md-12">
                        <div class="card">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Business Unit</th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Area</th>
                                        <th scope="col">Inspector</th>
                                        <th scope="col">Date of Inspection</th>
                                        <th scope="col">Total NC</th>
                                        <th scope="col">Total Critical</th>
                                        <th scope="col">Final Rating</th>
                                        <th scope="col">For Review and Action</th>
                                        <th scope="col">Inspection History</th>
                                    </tr>
                                </thead>
                                <tbody v-if="reports.length > 0">
                                    <tr v-for="(report, r) in filteredQueues" v-bind:key="r">
                                        <td scope="row">{{ report.id }}</td>
                                        <td>{{ report.company.name +' - ' +  report.location.name }}</td>
                                        <td>{{ report.department.name }}</td>
                                        <td>{{ report.area.name }}</td>
                                        <td>{{ report.inspector.name }}</td>
                                        <td>{{ report.date_of_inspection }}</td>
                                        <td>{{ countNonCriticality(report) }}</td>
                                        <td>{{ countCriticality(report) }}</td>
                                        <td>{{ countRating(report.report_detail) }}</td>
                                        <td>
                                            <a :href="publicPath+'/view-report/'+report.id"><u>View Report</u></a>
                                            <span v-if="report.inspector_id == userId && report.status == 1">
                                                <br><br>
                                                <a :href="publicPath+'/edit-report/'+report.id"><u>Update Report</u></a>
                                            </span>
                                        </td>
                                        <td>
                                            <a :href="publicPath+'/report-inspection-history/'+report.id" target="_blank"><u> View last 3 inspections</u></a>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <td>No data available in the table</td>
                                </tbody>
                            </table>
                            <div class="row mb-3 mt-3 ml-3" v-if="filteredQueues.length ">
                                <div class="col-6">
                                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                                    <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                                </div>
                                <div class="col-6 text-right">
                                    <span>{{ filteredQueues.length }} of {{ reports.length }} Total Document(s)</span>
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
                reports: [],
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
            }
        },
        created(){
            this.fetchReportNotifications();
        },
        methods:{
            moment,
            showLoader(){
               this.loading = true;
            },
            countNonCriticality(report){
                var nc = 0;
                report.report_detail.filter(report_detail => { 
                    if(report_detail.criticality == 'Non - Critical'){
                        nc = nc + 1;
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
            countRating(report){
                var denominator = 0;
                var total_points = 0;
                report.filter(item => {
                    if(item.points !== 'N/A'){
                        total_points = parseInt(total_points) + parseInt(item.points);
                        denominator = denominator + 1;
                    }
                });
                this.final_rating = total_points / (denominator * 2) * 100;
                return  this.final_rating.toFixed(2);
            },
            fetchReportNotifications(){
                axios.get('/reports-notification-all')
                 .then(response => {
                    this.reports = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
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
            showNextLinkView() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            }   
        },
        computed:{
            filteredObjects(){
                let self = this;
                return self.reports.filter(report => {
                    return report.process_owner.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.filteredObjects.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredObjects.slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            },
            publicPath(){
                return window.location.origin;
            },
        }
    }
</script>

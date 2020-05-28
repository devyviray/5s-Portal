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
                        <h1 class="text-primary">SUMMARY OF REPORTS</h1>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                                                PAGE NOT YET AVAILABLE
                            <!-- <div class="col-md-4 text-left mb-3 mt-3">
                                <input type="text" class="form-control" placeholder="Search by area owner" v-model="keywords" id="keywords">
                            </div>
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col"></th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Business Unit</th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Date of Insepction</th>
                                        <th scope="col">Area Owner</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(report, r) in filteredQueues" v-bind:key="r">
                                          <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow">
                                                    <a :href="publicPath+'/edit-report/'+report.id" class="dropdown-item">Edit</a>
                                                    <a :href="publicPath+'/view-report/'+report.id" class="dropdown-item">Preview</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td scope="row">{{ report.id }}</td>
                                        <td>{{ report.company.name +' - ' +  report.location.name }}</td>
                                        <td>{{ report.department.name }}</td>
                                        <td>{{ report.date_of_inspection }}</td>
                                        <td>{{ report.process_owner.name }} </td>
                                    </tr>
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
                            </div> -->
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
            this.fetchDraftReports();
        },
        methods:{
            moment,
            fetchDraftReports(){
                axios.get('/reports-my-drafts-all')
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

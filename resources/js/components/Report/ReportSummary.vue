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
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link custom-navlink active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">All Business Units</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link custom-navlink" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Per Business Unit</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div v-if="!view_ranking">
                                    <div class="row mt-4">
                                        <div class="col-md-1">
                                            <select class="form-control">
                                                <option>2019</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-success">Generate PDF</button>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button type="button" class="btn btn-secondary" @click="viewRanking">View Ranking</button>
                                        </div>
                                    </div>
                                    <div class="card mt-4">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Business Unit</th>
                                                    <th scope="col">JAN</th>
                                                    <th scope="col">FEB</th>
                                                    <th scope="col">MAR</th>
                                                    <th scope="col">APR</th>
                                                    <th scope="col">MAY</th>
                                                    <th scope="col">JUN</th>
                                                    <th scope="col">JUL</th>
                                                    <th scope="col">AUG</th>
                                                    <th scope="col">SEP</th>
                                                    <th scope="col">OCT</th>
                                                    <th scope="col">NOV</th>
                                                    <th scope="col">DEC</th>
                                                    <th scope="col">AVERAGE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>PFMC – FLOUR MANILA</td>
                                                    <td>85.00</td>
                                                    <td>86.59</td>
                                                    <td>75.00</td>
                                                    <td>86.25</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>83.21</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="row mt-4">
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-secondary" @click="exitRanking">Exit Ranking</button>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-success">Generate PDF</button>
                                        </div>
                                    </div>
                                    <div class="card mt-4">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Business Unit</th>
                                                    <th scope="col">JAN</th>
                                                    <th scope="col">FEB</th>
                                                    <th scope="col">MAR</th>
                                                    <th scope="col">APR</th>
                                                    <th scope="col">MAY</th>
                                                    <th scope="col">JUN</th>
                                                    <th scope="col">JUL</th>
                                                    <th scope="col">AUG</th>
                                                    <th scope="col">SEP</th>
                                                    <th scope="col">OCT</th>
                                                    <th scope="col">NOV</th>
                                                    <th scope="col">DEC</th>
                                                    <th scope="col">AVERAGE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>PFMC – FLOUR MANILA</td>
                                                    <td>85.00</td>
                                                    <td>86.59</td>
                                                    <td>75.00</td>
                                                    <td>86.25</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>83.21</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row mt-4">
                                    <div class="col-md-1">
                                        <select class="form-control">
                                            <option>2019</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-4 col-form-label">Business Unit</label>
                                            <div class="col-sm-8">
                                                <select class="form-control">
                                                    <option>PFMC - FLOUR ILOILO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-4 col-form-label">Department</label>
                                            <div class="col-sm-8">
                                                <select class="form-control">
                                                    <option>FLOUR MILL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <label for="colFormLabel" class="col-sm-4 col-form-label">Area</label>
                                            <div class="col-sm-8">
                                              <select class="form-control">
                                                    <option>All</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-secondary">Filter</button>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-success">Generate PDF</button>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <table class="table align-items-center table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Business Unit</th>
                                                <th scope="col">JAN</th>
                                                <th scope="col">FEB</th>
                                                <th scope="col">MAR</th>
                                                <th scope="col">APR</th>
                                                <th scope="col">MAY</th>
                                                <th scope="col">JUN</th>
                                                <th scope="col">JUL</th>
                                                <th scope="col">AUG</th>
                                                <th scope="col">SEP</th>
                                                <th scope="col">OCT</th>
                                                <th scope="col">NOV</th>
                                                <th scope="col">DEC</th>
                                                <th scope="col">AVERAGE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>PFMC – FLOUR MANILA</td>
                                                <td>85.00</td>
                                                <td>86.59</td>
                                                <td>75.00</td>
                                                <td>86.25</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>83.21</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
            loader
        },
        data(){
            return {
                reports: [],
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
                view_ranking: false
            }
        },
        created(){
            this.fetchDraftReports();
        },
        methods:{
            moment,
            viewRanking(){
                this.view_ranking = true;
            },
            exitRanking(){
                this.view_ranking = false;
            },
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

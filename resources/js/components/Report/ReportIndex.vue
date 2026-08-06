<template>
    <div id="wrapper">
    <loader v-if="loading"></loader>
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <div class="row">
            <div class="col-md-8"></div>
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
                <img class="lafil-logo" :src="logoLink">
                <b>5S PORTAL - REPORT & RATING </b>
            </h1>
            <breadcrumb :user-role-level="userRoleLevel"></breadcrumb>
        </div>
        <div id="page-inner">
            <div class="card">
                <div class="card-header">
                    <div class="row ml-2">
                        <div class="col-md-8"></div>
                        <div class="col-md-4" v-if="userRoleLevel > 2">
                            <button class="btn btn-sm btn-primary" @click="createReport"> Create Report</button>
                            <a target="_blank"  :href="trendAndAnalysis" class="btn btn-sm btn-primary"> Trend and Analysis</a>
                            <div class="dropdown" id="dropdown">
                                <a class="dropdown-toggle"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   + Performance Evaluation Rating
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-if="performanceEvaluationRating.length">
                                    <div class="row pl-2">
                                        <div class="col-md-12 mb-1">1st Quarter: {{ firstQuarterRating() }}</div>
                                        <div class="col-md-12 mb-1">2nd Quarter: {{ secondQuarterRating() }}</div>
                                        <div class="col-md-12 mb-1">3rd Quarter: {{ thirdQuarterRating() }}</div>
                                        <div class="col-md-12 mb-1">4th Quarter: {{ fourthQuarterRating() }}</div>
                                    </div>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-else>
                                    <div class="row pl-2">
                                        <div class="col-md-12 mb-1">1st Quarter: N/A</div>
                                        <div class="col-md-12 mb-1">2nd Quarter: N/A</div>
                                        <div class="col-md-12 mb-1">3rd Quarter: N/A</div>
                                        <div class="col-md-12 mb-1">4th Quarter: N/A</div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-4" v-else>
                            <a target="_blank" :href="trendAndAnalysis" class="btn btn-sm btn-primary float-right"> Trend and Analysis</a>
                        </div>
                    </div>
                    <div class="row ml-2">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Category</label>
                                <select class="form-control" v-model="filterData.category"  @change="searchKeyUp">
                                    <option v-for="(category,c) in categories" v-bind:key="c" :value="category.id"> {{ category.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.category  ">{{ errors.category[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2" v-if="show_operation_line">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Operation Line</label>
                                <select class="form-control" v-model="filterData.operation_line" @change="searchKeyUp">
                                    <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line.id"> {{ operation_line.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.operation_line  ">{{ errors.operation_line[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">AREA</label>
                                <select class="form-control" v-model="area"  @change="searchKeyUp">
                                    <option v-for="(area,a) in areas" v-bind:key="a" :value="area.id"> {{ area.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.area  ">{{ errors.area[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Status</label>
                                <select class="form-control" v-model="filterData.status"  @change="searchKeyUp">
                                    <option value="1"> {{ reportStatus(1) }}</option>
                                    <option value="2"> {{ reportStatus(2) }}</option>
                                    <option value="3"> {{ reportStatus(3) }}</option>
                                    <option value="4"> {{ reportStatus(4) }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.status  ">{{ errors.status[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-2 mt-2">
                            <button class="btn btn-sm btn-primary mt-4" @click="fetchFilteredReport"> Filter</button>
                            <button class="btn btn-sm btn-warning mt-4" @click="resetFilters"> Reset</button>
                        </div>
                    </div>
                </div>
                
                <table class="table align-items-center table-flush ">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th scope="col">ID</th>
                            <th scope="col">Process Owner</th>
                            <th scope="col">Company</th>
                            <th scope="col">Category</th>
                            <th scope="col">Operation Line</th>
                            <th scope="col">Area</th>
                            <th scope="col">Inspector</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="isProcessing">
                            <td colspan="9" class="text-center">
                                <!-- <table-spinner /> -->
                                <h4 class="text-warning">Loading... Please wait...</h4>
                            </td>
                        </tr>
                        <tr v-if="!isProcessing && !items.length">
                            <td colspan="9" class="py-10">
                                No results found
                            </td>
                        </tr>
                        <template v-if="!isProcessing">
                            <tr v-for="(report, r) in items" v-bind:key="r">
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a
                                            class="btn btn-sm btn-icon-only text-light"
                                            href="#"
                                            role="button"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                            @click.prevent="toggleDropdown($event)"
                                        >
                                            <i class="fa fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a v-if="report.status == 1 && userRoleLevel > 2" class="dropdown-item" target="_blank" :href="editReportLink+report.id">Edit</a>
                                            <a class="dropdown-item" target="_blank" :href="viewReportLink+report.id">View</a>
                                            <a v-if="report.status == 2 && userRoleLevel > 2 && userId == report.inspector_id" class="dropdown-item" target="_blank" :href="verifiedReportLink+report.id">Validate</a>
                                            <!-- <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(report)">Delete</a> -->
                                        </div>
                                    </div> 
                                </td>
                                <td scope="row">{{ report.id }}</td>
                                <td>{{ report.process_owner.name }}</td>
                                <td>{{ report.company.name + ' ' + report.location.name }}</td>
                                <td>{{ report.category.name }} </td>
                                <td v-if="report.operation_line">{{ report.operation_line.name }}</td>
                                <td v-else></td>
                                <td>{{ report.area.name }} </td>
                                <td>{{ report.inspector.name }}</td>
                                <td>{{ reportStatus(report.status) }} </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <table-pagination v-if="items.length > 0" :pagination="pagination" v-on:updatePage="goToPage" v-on:doChangeLimit="changePageCount"/>
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
    export default {
        props: ['userName', 'userRoleLevel', 'userId', 'companyId', 'locationId'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                performanceEvaluationRating: [],
                reports: [],
                items: [],
                companies : [],
                locations: [],
                operation_lines: [],
                categories: [],
                areas: [],
                company: '',
                location: '',
                operation_line: '',
                category: '',
                area: '',
                report_show: false,
                errors: [],
                currentPage: 0,
                itemsPerPage: 15,
                keywords: '',
                loading: false,
                show_operation_line: false,
                filterData: {},

                //pagination =====
				pagination: {},
				page_limit: 10,
				currentPageToGo: 1,

                isProcessing: false,
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchOperationLines();
            this.fetchCategories();
            this.fetchReports();
            this.fetchPerformanceEvaluationRating();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            createReport(){
                return window.location.href = window.location.origin+'/create-report';
            },
            toggleDropdown(event) {
                $(event.currentTarget).dropdown('toggle');
            },
            // changeCompany(company,action){
            //     if(action == 'getCompanies'){
            //         axios.get(`/company-location/${company.id}`)
            //         .then(response => { 
            //             this.locations = response.data.locations;
            //         })
            //         .catch(error => {
            //             this.errors = error.response.data.errors;
            //         })
            //     }else {
            //         if(this.category.id == 1){
            //              this.show_operation_line = true
            //         }else{
            //             this.show_operation_line = false;
            //             this.operation_line = '';
            //         }
            //         if(this.company && this.location && this.category){
            //             this.fetchCompayAreas();
            //         }
            //     }
            // },
            changeCategory(){
                if(this.category.id == 1){
                        this.show_operation_line = true
                }else{
                    this.show_operation_line = false;
                    this.operation_line = '';
                }
                if(this.companyId && this.locationId && this.category){
                    this.fetchCompayAreas();
                }
            },
            fetchReports(){
                this.isProcessing = true;
                 axios.get(`/reports-all/${this.companyId}/${this.locationId}`, {
                     params: {
                        ...this.filterData,
                         page: this.currentPageToGo,
                         page_limit: this.page_limit
                     }
                 })
                 .then(response => {
                    this.items = response.data.data;

                    this.pagination = response.data;
					this.isProcessing = false;
					this.currentPageToGo = 1

					//Set pagination page count
					this.setPaginationPageRange(this.pagination.current_page, this.pagination.last_page);
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchPerformanceEvaluationRating(){
                 axios.get(`/performace-evaluation-rating/${this.companyId}/${this.locationId}`)
                .then(response =>{
                    this.performanceEvaluationRating = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.error;
                })
            },
            fetchCompanies(){
                axios.get('/companies-all')
                 .then(response => {
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchOperationLines(){
                axios.get('/operation-lines-all')
                 .then(response => {
                    this.operation_lines = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchCategories(){
                axios.get('/categories-all')
                 .then(response => {
                    this.categories = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchCompayAreas(){
                var operationId = this.operation_line ? this.operation_line.id : 0;
                axios.get(`/company-areas-per-company/${this.companyId}/${this.locationId}/${this.category.id}/${operationId}`)
                 .then(response => {
                    this.areas = response.data[0].areas;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            fetchFilteredReport(){
                axios.post('/report-filtered', {
                    company: this.companyId,
                    location: this.locationId,
                    operation_line: this.operation_line.id,
                    category: this.category.id,
                    area: this.area.id
                })
                .then(response => { 
                    this.reports = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors
                })
            },
            reportStatus(status){
                switch(status){
                    case 1:
                        return 'Pending'; 
                        break;
                    case 2: 
                        return 'For Checking';
                        break;
                    case 3: 
                        return 'Validated';
                        break;
                    case 4: 
                        return 'Report Final'
                        break;
                    default:
                }
            },
            firstQuarterRating(){
                var rating = 0;
                var count = 0;
                this.performanceEvaluationRating.filter(item => {
                    if(item.reporting_month == 1 || item.reporting_month == 2 || item.reporting_month == 3){
                        rating = rating + item.ratings;
                        count = count + 1;
                    }
                })
                var result = rating ?  rating / count + ' %  ' : 'N/A'
               
                return result;
            },
            secondQuarterRating(){
                var rating = 0;
                var count = 0;
                this.performanceEvaluationRating.filter(item => {
                    if(item.reporting_month == 4 || item.reporting_month == 5 || item.reporting_month == 6){
                        rating = rating + item.ratings;
                        count = count + 1;
                    }
                })
                var result = rating ?  rating / count + ' %  ' : 'N/A'

                return result;
            },
            thirdQuarterRating(){
                var rating = 0;
                var count = 0;
                this.performanceEvaluationRating.filter(item => {
                    if(item.reporting_month ==  7 || item.reporting_month == 8 || item.reporting_month == 9){
                        rating = rating + item.ratings;
                    }
                })
                var result = rating ?  rating / count + ' %  ': 'N/A'
               
                return result;
            },
            fourthQuarterRating(){
                var rating = 0;
                var count = 0;
                this.performanceEvaluationRating.filter(item => {
                    if(item.reporting_month ==  10 || item.reporting_month == 11 || item.reporting_month == 12){
                        rating = rating + item.ratings;
                        count = count + 1;
                    }
                })
                var result = rating ?  rating / count + ' %  ' : 'N/A'
               
                return result;
            },

            resetFilters(){
                this.filterData = {};
                this.area = '';
                this.operation_line = '';
                this.category = '';
                this.fetchReports();
            },

            searchKeyUp() {
				clearTimeout(this.keyTimeout);
                this.keyTimeout = setTimeout(() => {
					this.isProcessing = true;
					this.fetchReports();
                }, 500)
			},
            //Pagination methods =============================
            goToPage(page) {
				this.currentPageToGo = page;
                this.fetchReports();
            },
            changePageCount(pageLimit) {
                this.page_limit = pageLimit;
                this.fetchReports();
            },
            setPaginationPageRange(page, pageCount) {

                let start = page - 2,
                    end = page + 2;

                if (end > pageCount) {
                    start -= (end - pageCount);
                    end = pageCount;
                }

                if (start <= 0) {
                    end += ((start - 1) * (-1));
                    start = 1;
                }

                end = end > pageCount ? pageCount : end;

                return this.pagination.range = Array(end - start + 1).fill().map((_, idx) => start + idx)

            },
			//================================================
        },
        computed:{
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
            viewReportLink(){
               return window.location.origin+'/view-report/'; 
            },
            editReportLink(){
                return window.location.origin+'/edit-report/'; 
            },
            verifiedReportLink(){
                return window.location.origin+'/validate-report/'; 
            },
            trendAndAnalysis(){
                return window.location.origin+'/trend-and-analysis';
            },
        }
    }
</script>

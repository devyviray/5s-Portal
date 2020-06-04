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
                                            <select class="form-control" v-model="year1" @change="fetchSummaryPerBu">
                                                <option v-for="(year,y) in years" :key="y" :value="year">
                                                    {{ year }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-success" @click="summaryPerBuToPdf">Generate PDF</button>
                                            <span class="text-danger" v-if="no_year">No data to generate</span>
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
                                            <tbody v-if="reports_summary.length > 0">
                                                <tr v-for="(summary,s) in reports_summary" :key="s">
                                                    <td>{{ summary[0].company.name+ ' - ' +summary[0].location.name }}</td>
                                                    <td>{{ getRating(summary, 1) }}</td>
                                                    <td>{{ getRating(summary, 2) }}</td>
                                                    <td>{{ getRating(summary, 3) }}</td>
                                                    <td>{{ getRating(summary, 4) }}</td>
                                                    <td>{{ getRating(summary, 5) }}</td>
                                                    <td>{{ getRating(summary, 6) }}</td>
                                                    <td>{{ getRating(summary, 7) }}</td>
                                                    <td>{{ getRating(summary, 8) }}</td>
                                                    <td>{{ getRating(summary, 9) }}</td>
                                                    <td>{{ getRating(summary, 10) }}</td>
                                                    <td>{{ getRating(summary, 11) }}</td>
                                                    <td>{{ getRating(summary, 12) }}</td>
                                                    <td>{{ getAverage(summary) }}</td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <td>No data available in the table</td>
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
                                        <label for="disabledTextInput">Year</label>
                                        <select class="form-control" v-model="year">
                                            <option v-for="(year,y) in years" :key="y" :value="year">
                                                {{ year }}
                                            </option>
                                        </select>
                                        <span class="text-danger" v-if="errors.year">{{ errors.year[0] }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="disabledTextInput">Company</label>
                                            <select class="form-control" v-model="company" @change="changeCompany">
                                                <option v-for="(company,c) in companies" v-bind:key="c" :value="company"> {{ company.name }}</option>
                                            </select>
                                            <span class="text-danger" v-if="errors.company">{{ errors.company[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="disabledTextInput">Location</label>
                                           <select class="form-control" v-model="location" @change="changeLocation">
                                                <option v-for="(location,l) in locations" v-bind:key="l" :value="location"> {{ location.name }}</option>
                                            </select>
                                            <span class="text-danger" v-if="errors.location">{{ errors.location[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="disabledTextInput">Category</label>
                                            <select class="form-control" v-model="category"  @change="changeCategory">
                                                <option v-for="(category,c) in categories" v-bind:key="c" :value="category"> {{ category.name }}</option>
                                            </select>
                                            <span class="text-danger" v-if="errors.category  ">{{ errors.category[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2" v-if="show_operation_line">
                                        <div class="form-group">
                                            <label for="disabledTextInput">Operation Line</label>
                                           <select class="form-control" v-model="operation_line" @change="changeOperationLine">
                                                <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line.operation_line"> {{ operation_line.operation_line.name }}</option>
                                            </select>
                                            <span class="text-danger" v-if="errors.operation_line  ">{{ errors.operation_line[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="disabledTextInput">Area</label>
                                            <select class="form-control" v-model="area">
                                                <option v-if="areas.length > 0" value="ALL">ALL</option>
                                                <option v-for="(area,a) in areas" v-bind:key="a" :value="area"> {{ area.name }}</option>
                                            </select>
                                            <span class="text-danger" v-if="errors.area  ">{{ errors.area[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-secondary" @click="fetchFilteredReport">Filter</button>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-success" @click="filteredReportToPdf">Generate PDF</button><br>
                                        <span class="text-danger" v-if="no_data">No data to generate</span>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <table class="table align-items-center table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Business Unit</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Operation Line</th>
                                                <th scope="col">Area</th>
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
                                        <tbody v-if="reports.length > 0">
                                            <tr v-for="(report,r) in filteredQueues" :key="r">
                                                <td>{{ report[0].company.name + ' - ' + report[0].location.name }}</td>
                                                <td>
                                                    {{ report[0].category.name  }}
                                                </td>
                                                <td>
                                                    <span v-if="report[0].operation_line">
                                                        {{ report[0].operation_line.name  }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ report[0].area.name  }}
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '1'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '2'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '3'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '4'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '5'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '6'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '7'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '8'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '9'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '10'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '11'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span v-for="(rp, p) in report" :key="p">
                                                        <span v-if="rp.reporting_month == '12'">{{  numberFormat(rp.ratings) }}</span>
                                                    </span>
                                                </td>
                                                <td>{{ countAverage(report) }}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <td>No data available in the table</td>
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
                reports_summary: [],
                companies: [],
                company: '',
                departments: [],
                department: '',
                locations: [],
                location: '',
                categories: [],
                category: '',
                operation_lines: [],
                operation_line: '',
                areas: [],
                area: '',
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
                view_ranking: false,
                show_operation_line: false,
                years:  '',
                year1: '',
                year: '',
                no_data: false,
                no_year: false
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchCategories();
            this.generateYear();
        },
        methods:{
            moment,
            getRating(reports,month){
                var total_areas = 0;
                var total_ratings = 0;

                if(reports){
                    reports.filter(report =>{
                        if(report.reporting_month == month){
                            total_areas = total_areas + 1;
                            total_ratings = total_ratings + report.ratings;
                        }
                    });
                    return this.numberFormat(total_ratings / total_areas);
                }
                return '-';
            },
            getAverage(reports){
                var reporting_month = [];
                var total_rating = 0;
                if(reports){
                    reports.filter(report =>{ // Get month that has report
                        if(!reporting_month.includes(report.reporting_month)){
                            reporting_month.push(report.reporting_month)
                        }
                    });

                    for(var i = 1; i < 13; i++){ // Add all reports per month
                        var monthly_average = this.getRating(reports,i);
                        if(monthly_average){
                            total_rating == 0 ? total_rating = monthly_average :  
                            total_rating = parseFloat(total_rating) + parseFloat(monthly_average);
                        }
                    }
                    return this.numberFormat(total_rating / reporting_month.length);
                }
                return '-';
            },
            numberFormat(num){
                if(num){
                    return Number(parseFloat(num).toFixed(2)).toLocaleString('en', { minimumFractionDigits: 2 });
                }
            },
            generateYear(){
                var years = []
                var start_year = 2018;
                var upcoming_years = 15;
                for(var i=0;i<=upcoming_years;i++){
                    years.push(start_year = start_year + 1);
                } 
                this.years = years;
            },
            showLoader(){
               this.loading = true;
            },
            viewRanking(){
                this.view_ranking = true;
            },
            exitRanking(){
                this.view_ranking = false;
            },  
            countAverage(reports){
                var reporting_month_count = 0;
                var total_points = 0;
                reports.filter(report => {
                    reporting_month_count = reporting_month_count + 1;
                    total_points = total_points + report.ratings;
                });

                return this.numberFormat(total_points / reporting_month_count);
            },
            changeCompany(){
                this.location = '';
                this.operation_lines = [];
                this.operation_line = '';
                this.areas = [];
                this.area = '';
                axios.get(`/company-location/${this.company.id}`)
                .then(response => { 
                    this.locations = response.data.locations;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
                if(this.location && this.category){
                    this.fetchCompayAreas();
                }
            },
            changeLocation(){
               if(this.category.name == "Operations"){
                    this.fetchOperationLines();
                }else{
                    this.fetchCompayAreas();
                }
            },
            changeCategory(){
                if(this.category.name == "Operations"){
                    this.show_operation_line = true;
                    if(this.company && this.location){
                        this.fetchOperationLines();
                    }
                }else{
                    if(this.company && this.location){
                        this.fetchCompayAreas();
                    }
                    this.show_operation_line = false;
                    this.operation_lines = [];
                    this.operation_line = '';
                }
                this.areas = [];
                this.area = '';
            },
            changeOperationLine(){
                if(this.company && this.location && this.category){
                    this.fetchCompayAreas();
                }
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
            fetchDepartments(){
                axios.get('/departments-all')
                 .then(response => {
                    this.departments = response.data;
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
            fetchOperationLines(){
                axios.get(`/operation-lines/${this.company.id}/${this.location.id}`)
                 .then(response => {
                    this.operation_lines = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchCompayAreas(){
                var operationId = this.operation_line ? this.operation_line.id : 0;
                axios.get(`/company-areas-per-company/${this.company.id}/${this.location.id}/${this.category.id}/${operationId}`)
                 .then(response => {
                    this.areas =  response.data.length > 0 ? response.data[0].areas : [];
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            fetchFilteredReport(){
                this.loading = true;
                this.errors = [];
                axios.post('/report-filtered', {
                    year: this.year,
                    company: this.company.id,
                    location: this.location.id,
                    category: this.category.id,
                    operation_line: this.operation_line.id,
                    area: this.area == 'ALL' ? this.area :  this.area.id
                })
                .then(response => { 
                    this.reports = Object.values(response.data);
                    this.loading = false;
                })
                .catch(error => { 
                    this.loading = false;
                    this.errors = error.response.data.errors
                })
            },
            filteredReportToPdf(){
                var t = this;
                if(t.reports.length < 1){
                    t.no_data = true;
                    return false;
                }
                var operationId = t.operation_line ? t.operation_line.id : 0;
                var area = t.area == 'ALL' ? t.area : t.area.id;
                window.open(window.location.origin+'/report-filtered-to-pdf/'+t.year+'/'+t.company.id+'/'+t.location.id+'/'+t.category.id+'/'+operationId+'/'+area, '_blank');
            },
            summaryPerBuToPdf(){
                var t = this;
                if(t.year1 == ''){
                    t.no_year = true;
                    return false;
                }
                window.open(window.location.origin+'/report-summary-per-bu-to-pdf/'+t.year1, '_blank');
            },
            fetchSummaryPerBu(){
                this.loading = true;
                this.errors = [];
                axios.post('/report-summary-per-bu', {
                    year: this.year1
                })
                .then(response => { 
                    this.reports_summary = Object.values(response.data);
                    this.loading = false;
                })
                .catch(error => { 
                    this.loading = false;
                    this.errors = error.response.data.errors
                })
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
                    return report[0].area.name.toLowerCase().includes(this.keywords.toLowerCase())
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
            }
        }
    }
</script>

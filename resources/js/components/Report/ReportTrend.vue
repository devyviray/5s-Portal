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
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr> 
                                    <td rowspan="2">Area Inspected</td>
                                    <td colspan="14" style="text-align: center">5s Rating</td>
                                </tr>
                                <tr>
                                    <td scope="col">Jan</td>
                                    <td scope="col">Feb</td>
                                    <td scope="col">Mar</td>
                                    <td scope="col">Apr</td>
                                    <td scope="col">May</td>
                                    <td scope="col">Jun</td>
                                    <td scope="col">Jul</td>
                                    <td scope="col">Aug</td>
                                    <td scope="col">Sep</td>
                                    <td scope="col">Oct</td>
                                    <td scope="col">Nov</td>
                                    <td scope="col">Dec</td>
                                    <td scope="col">Ave.(ytd)</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color: #EDEDED"><td colspan="14">Operations</td></tr>
                                <tr v-for="(company, c) in companies" :key="c">
                                    <td>{{ company.company.name + ' ' + company.location.name + ' - ' +  company.operation_line.name }}</td>
                                    <td>{{ getRating(company.reports, 1) }}</td>
                                    <td>{{ getRating(company.reports, 2) }}</td>
                                    <td>{{ getRating(company.reports, 3) }}</td>
                                    <td>{{ getRating(company.reports, 4) }}</td>
                                    <td>{{ getRating(company.reports, 5) }}</td>
                                    <td>{{ getRating(company.reports, 6) }}</td>
                                    <td>{{ getRating(company.reports, 7) }}</td>
                                    <td>{{ getRating(company.reports, 8) }}</td>
                                    <td>{{ getRating(company.reports, 9) }}</td>
                                    <td>{{ getRating(company.reports, 10) }}</td>
                                    <td>{{ getRating(company.reports, 11) }}</td>
                                    <td>{{ getRating(company.reports, 12) }}</td>
                                    <td>{{ getAverage(company.reports) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row mt-4">
                            <div class="col-md-4"></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-right">
                                <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill">Print as PDF</button>
                                <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill">Export as excel File</button>
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
                companies: [],
                trendAndAnalysis: [],
                errors: [] 
            }
        },
        created(){
            this.fetchCompanies();
        },
        methods:{
            getRating(reports, item){
                if(reports.length){
                    var rating = '-';
                    reports.filter(report => {
                        if(report.reporting_month == item && report.ratings){
                            rating =  report.ratings;
                        }
                    });
                    return rating; 
                }else{
                    return '-';
                }
            },
            getAverage(reports){
                var average = 0;
                var count = 0;
                reports.filter(report => {
                    if(report.ratings){
                        average = average + report.ratings;
                        count = count + 1;
                    }
                });
                var total = average / count;
                if( isNaN(total)){
                    return '-';
                }else {
                    return total;
                }
            },
            fetchCompanies(){
                axios.get('/companies-with-operation-line')
                .then(response => {
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
        },
        computed:{
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            }
        }
    }
</script>

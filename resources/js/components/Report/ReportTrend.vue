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
                                <tr v-for="(trend, t) in trendAndAnalysis" :key="t">
                                    <td>{{ trend.company.name + ' ' + trend.location.name + ' - ' +  trend.operation_line.name }}</td>
                                    <td>{{ getRating(trend.reports, 1) }}</td>
                                    <td>{{ getRating(trend.reports, 2) }}</td>
                                    <td>{{ getRating(trend.reports, 3) }}</td>
                                    <td>{{ getRating(trend.reports, 4) }}</td>
                                    <td>{{ getRating(trend.reports, 5) }}</td>
                                    <td>{{ getRating(trend.reports, 6) }}</td>
                                    <td>{{ getRating(trend.reports, 7) }}</td>
                                    <td>{{ getRating(trend.reports, 8) }}</td>
                                    <td>{{ getRating(trend.reports, 9) }}</td>
                                    <td>{{ getRating(trend.reports, 10) }}</td>
                                    <td>{{ getRating(trend.reports, 11) }}</td>
                                    <td>{{ getRating(trend.reports, 12) }}</td>
                                    <td>{{ getAverage(trend.reports) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row mt-4">
                            <div class="col-md-4"></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-right">
                                <a :href="pdfLink" target="_blank" class="btn btn-primary btn-round btn-fill">Print as PDF</a>
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
                trendAndAnalysis: [],
                errors: [] 
            }
        },
        created(){
            this.fetchTrendAndAnalysis();
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
            fetchTrendAndAnalysis(){
                axios.get('/trend-and-analysis-data')
                .then(response => {
                    this.trendAndAnalysis = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
        },
        computed:{
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
            pdfLink(){
                return window.location.origin+'/generate-pdf';  
            }
        }
    }
</script>

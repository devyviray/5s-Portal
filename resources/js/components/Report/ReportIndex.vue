<template>
    <div id="wrapper">
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-2">
                <span class="span-username">Hi, {{ this.userName }}</span>
            </div>
            <div class="col-md-1">
                <navbarRight></navbarRight>
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
                <div class="card-header">
                    <div class="row ml-2">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Company</label>
                                <select class="form-control" v-model="company" @change="changeCompany(company, 'getCompanies')">
                                    <option v-for="(company,c) in companies" v-bind:key="c" :value="company"> {{ company.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.company  ">{{ errors.company }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Company Location</label>
                                <select class="form-control" v-model="location" @change="changeCompany('', '')">
                                    <option v-for="(location,l) in locations" v-bind:key="l" :value="location"> {{ location.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.location  ">{{ errors.location }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Operation Line</label>
                                <select class="form-control" v-model="operation_line" @change="changeCompany('', '')">
                                    <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line"> {{ operation_line.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.operation_line  ">{{ errors.operation_line }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">Category</label>
                                <select class="form-control" v-model="category"  @change="changeCompany('', '')">
                                    <option v-for="(category,c) in categories" v-bind:key="c" :value="category"> {{ category.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.category  ">{{ errors.category }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" for="role">AREA</label>
                                <select class="form-control" v-model="area"  @change="changeCompany('', '')">
                                    <option v-for="(area,a) in areas" v-bind:key="a" :value="area.area"> {{ area.area.name }}</option>
                                </select>
                                <span class="text-danger" v-if="errors.area  ">{{ errors.area }}</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-primary mt-4" @click="viewAction"> Go</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>

                <div class="card-body" v-show="report_show"> 
                    <div class="row">
                        <div class="col-md-2">
                            <a href="javascript:void(0)" @click="createReport">Create Report</a>
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:void(0)" @click="viewReport">View Report</a>
                        </div> 
                        <div class="col-md-2">
                            <a href="javascript:void(0)" >Trend Analysis(Excel file)</a>
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:void(0)">Performance Evaluation Rating</a>
                        </div>
                    </div>
                    

                    <!-- Create Report page -->
                    <div class="row mt-5" v-if="show_create_report">
                        <div class="col-md-4 card card-report">
                            <div class="card-body">
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Company:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.company.name + ' - ' + this.location.name }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Operation Line:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.operation_line.name }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Category:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.category.name }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Area:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.area.name }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Inspector:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.userName }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabel" class="col-sm-3 col-form-label">Process owner</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" v-model="process_owner">
                                            <option v-for="(process_owner,p) in process_owners" v-bind:key="p" :value="process_owner.id"> {{ process_owner.name }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.process_owner  ">{{ errors.process_owner[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabel" class="col-sm-3 col-form-label">Date of Inspection</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="colFormLabel" v-model="date_of_inspection">
                                        <span class="text-danger" v-if="errors.process_owner  ">{{ errors.date_of_inspection[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabel" class="col-sm-3 col-form-label">Time</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="colFormLabel" v-model="time_of_inspection">
                                        <span class="text-danger" v-if="errors.time_of_inspection  ">{{ errors.time_of_inspection[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabel" class="col-sm-3 col-form-label">Cheklist</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" v-model="checklist" @change="getSelectedChecklist(checklist)">
                                            <option v-for="(checklist,c) in checklists" v-bind:key="c" :value="checklist"> {{ ' CHECKLIST - '+ checklist[0].created_at }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.checklist  ">{{ errors.checklist[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-4">
                                    <button class="btn btn-block btn-primary" @click="addReport(selected_checklist,points)"> POST</button>
                                    </div>
                                    <div class="col-sm-4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="table-responsive" v-if="selected_checklist.length">
                                <!-- Projects table -->
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr class="d-flex">
                                            <th scope="col" class="col-1">ID</th>
                                            <th scope="col" class="col-6">Name</th>
                                            <th scope="col" class="col-2">Points</th>
                                            <th scope="col" class="col-3">File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(checklist, c) in filteredQueues" v-bind:key="c" class="d-flex">
                                            <td scope="row" class="col-sm-1">{{ c + 1 }}</td>
                                            <td class="col-sm-6" style="white-space: inherit;">{{ checklist.requirement +' - '+ checklist.description  }}</td>
                                            <td class="col-sm-2">
                                                <select class="form-control" v-model="points[c]">
                                                    <option value="0"> 0 </option>
                                                    <option value="1"> 1 </option>
                                                    <option value="2"> 2 </option>
                                                </select>
                                            </td>
                                            <td class="col-sm-3">
                                                <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mb-3 mt-3 ml-1" v-if="selected_checklist.length ">
                                <div class="col-6">
                                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                                    <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                                </div>
                                <div class="col-6 text-right">
                                    <span>{{ selected_checklist.length }} Checklist(s)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- View Report page -->
                    <div class="row mt-5" v-if="show_view_report">
                            <div class="col-md-4 card card-report">
                            <div class="card-body">
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Company:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.company.name }} </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Operation Line:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.operation_line.name }} </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Category:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.category.name }} </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Area:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.area.name }}  </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-3 ">Inspector:</span>
                                    <div class="col-sm-9">
                                        <span> {{ this.userName }} </span>
                                    </div>
                                </div>
                                <div v-if="reportsPerUser.length">
                                    <div class="form-group row">
                                        <span class="col-sm-3 ">Date of Inspection:</span>
                                        <div class="col-sm-9">
                                            <span>{{ this.reportsPerUser[0].date_of_inspection }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="col-sm-3 ">Time</span>
                                        <div class="col-sm-9">
                                            <span>{{ this.reportsPerUser[0].time_of_inspection }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="col-sm-3 ">Rating:</span>
                                        <div class="col-sm-9">
                                            <span>{{ countRating(this.reportsPerUser) }} %</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <button class="btn btn-block btn-danger" @click="forCheckingReport(this.reportsPerUser)">FOR CHECKING</button>
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-block btn-primary" @click="approvedReport(this.reportsPerUser)">APPROVED</button>
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-success" @click="viewSummaryReport(this.reportsPerUser)">SUMMARY REPORT</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="table-responsive" v-if="reportsPerUser.length">
                                <!-- View reports table -->
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr class="d-flex">
                                            <th scope="col" class="col-1">ID</th>
                                            <th scope="col" class="col-6">Name</th>
                                            <th scope="col" class="col-2">Points</th>
                                            <th scope="col" class="col-3">File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(checklist, c) in filteredQueuesView" v-bind:key="c" class="d-flex">
                                            <td scope="row" class="col-sm-1">{{ c + 1 }}</td>
                                            <td class="col-sm-6" style="white-space: inherit;">{{ checklist.name }}</td>
                                            <td class="col-sm-2">
                                                <select class="form-control" v-model="checklist.points">
                                                    <option value="0"> 0 </option>
                                                    <option value="1"> 1 </option>
                                                    <option value="2"> 2 </option>
                                                </select>
                                            </td>
                                            <td class="col-sm-3">
                                                <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mb-3 mt-3 ml-1" v-if="reportsPerUser.length ">
                                <div class="col-6">
                                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPagesView }}</span>
                                    <button :disabled="!showNextLinkView()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                                </div>
                                <div class="col-6 text-right">
                                    <span>{{ reportsPerUser.length }} Checklist(s)</span>
                                </div>
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
    export default {
        props: ['userName'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb
        },
        data(){
            return {
                companies : [],
                locations: [],
                operation_lines: [],
                categories: [],
                areas: [],
                checklists: [],
                selected_checklist:[],
                company: '',
                location: '',
                operation_line: '',
                category: '',
                area: '',
                checklist: '',
                process_owners: [],
                process_owner: '',
                date_of_inspection: '',
                time_of_inspection: '',
                selected_data: [],
                points: [],
                attachments: [],
                reportsPerUser: [],
                report_show: false,
                show_create_report: false,
                show_view_report: false,
                errors: [],
                currentPage: 0,
                itemsPerPage: 8,
                keywords: '',
                fileSize: 0,
                maximumSize: 5000000,
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchOperationLines();
            this.fetchCategories();
            this.fetchChecklist();
        },
        methods:{
            uploadFileChange(e){
                this.attachments = [];
                var files = e.target.files || e.dataTransfer.files;

                if(!files.length)
                    return;
                
                for (var i = files.length - 1; i >= 0; i--){
                    this.attachments.push(files[i]);
                    this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
                }
                if(this.fileSize > 5){
                    alert('File size exceeds 5 MB');
                    document.getElementById('attachments').value = "";
                    this.attachments = [];
                    this.fileSize = 0;
                }
            },
            changeCompany(company,action){
                if(action == 'getCompanies'){
                    axios.get(`/company-location/${company.id}`)
                    .then(response => { 
                        this.locations = response.data.locations;
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    })
                }else {
                    if(this.company && this.location && this.operation_line && this.category){
                        this.fetchCompayAreas();
                    }
                }
            },
            fetchCompanies(){
                axios.get('companies-all')
                 .then(response => {
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchOperationLines(){
                axios.get('operation-lines-all')
                 .then(response => {
                    this.operation_lines = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchCategories(){
                axios.get('categories-all')
                 .then(response => {
                    this.categories = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                }); 
            },
            fetchCompayAreas(){
                axios.get(`/company-areas-per-company/${this.company.id}/${this.location.id}/${this.operation_line.id},${this.category.id}`)
                 .then(response => {
                    this.areas = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            fetchChecklist(){
                axios.get('checklists-all')
                .then(response => { 
                    this.checklists = response.data
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            fetchInspectors(){
                axios.get(`/user-process-owner/${this.company.id}/${this.location.id}`)
                .then(response => {
                    this.process_owners = response.data;
                })
                .catch(error =>{
                    this.errors = error.response.data.errors;
                })
            },
            viewAction(){
                this.errors = [];
                if(!this.company){
                    this.errors.company = 'This field is required';
                    this.report_show = false;
                }
                if(!this.location){
                    this.errors.location = 'This field is required';
                    this.report_show = false;
                }
                if(!this.operation_line){
                    this.errors.operation_line = 'This field is required';
                   this.report_show = false;
                }
                if(!this.category){
                    this.errors.category = 'This field is required';
                   this.report_show = false;
                }
                if(!this.area){
                    this.errors.area = 'This field is required';
                    this.report_show = false;
                }
                if(this.company && this.location && this.operation_line && this.category && this.area){
                    this.report_show = true;
                }
            },
            getSelectedChecklist(checklist){
                this.selected_checklist = [];
                this.selected_checklist = checklist;
            },
            addReport(selected_checklist,points){
                axios.post('/report', {
                    company: this.company.id,
                    location: this.location.id,
                    operation_line: this.operation_line.id,
                    category: this.category.id,
                    area: this.area.id,
                    process_owner: this.process_owner,
                    date_of_inspection: this.date_of_inspection,
                    time_of_inspection: this.time_of_inspection,
                    checklist: selected_checklist,
                    points: points,
                    attachments: this.attachments
                })
                .then(response => { 

                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            createReport(){
                this.show_create_report = true;
                this.show_view_report = false;
                this.fetchInspectors();
            },
            viewReport(){
                this.show_create_report = false;
                this.show_view_report = true;
                this.fetchReports();

            },
            fetchReports(){
                axios.get(`/reports-per-user/${this.company.id}/${this.location.id}/${this.operation_line.id}/${this.category.id}/${this.area.id}`)
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
                reportsPerUser.filter(item => total_points = total_points + item.points);

                return total_points / denominator * 100;
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
            totalPages() {
                return Math.ceil(Object.values(this.selected_checklist).length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = Object.values(this.selected_checklist).slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            },
            totalPagesView() {
                return Math.ceil(Object.values(this.reportsPerUser).length / this.itemsPerPage);
            },
            filteredQueuesView() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = Object.values(this.reportsPerUser).slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPagesView) {
                    this.currentPage = this.totalPagesView - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            },
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
        }
    }
</script>

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
                <div class="card-body"> 
                    <div class="row">
                        <div class="col-md-3 card card-report">
                            <div class="card-body">
                                <div class="form-group row">
                                    <span class="col-sm-5">Company:</span>
                                    <div class="col-sm-7">
                                        <select class="form-control" v-model="company" @change="changeCompany(company, 'getCompanies')">
                                            <option v-for="(company,c) in companies" v-bind:key="c" :value="company"> {{ company.name }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.company  ">{{ errors.company[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-5">Location:</span>
                                    <div class="col-sm-7">
                                        <select class="form-control" v-model="location" @change="changeCompany('', '')">
                                            <option v-for="(location,l) in locations" v-bind:key="l" :value="location"> {{ location.name }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.location  ">{{ errors.location[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-5">Operation Line:</span>
                                    <div class="col-sm-7">
                                        <select class="form-control" v-model="operation_line" @change="changeCompany('', '')">
                                            <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line"> {{ operation_line.name }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.operation_line  ">{{ errors.operation_line[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-5">Category:</span>
                                    <div class="col-sm-7">
                                        <select class="form-control" v-model="category"  @change="changeCompany('', '')">
                                            <option v-for="(category,c) in categories" v-bind:key="c" :value="category"> {{ category.name }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.category  ">{{ errors.category[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="col-sm-5">Area:</span>
                                    <div class="col-sm-7">
                                        <select class="form-control" v-model="area"  @change="changeCompany('', '')">
                                            <option v-for="(area,a) in areas" v-bind:key="a" :value="area.area"> {{ area.area.name }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.area  ">{{ errors.area[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabel" class="col-sm-5 col-form-label">Process owner</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" v-model="process_owner">
                                            <option v-for="(process_owner,p) in process_owners" v-bind:key="p" :value="process_owner.id"> {{ process_owner.name }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.process_owner  ">{{ errors.process_owner[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabel" class="col-sm-5 col-form-label">Date of Inspection</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control" id="colFormLabel" v-model="date_of_inspection">
                                        <span class="text-danger" v-if="errors.process_owner  ">{{ errors.date_of_inspection[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabel" class="col-sm-5 col-form-label">Time</label>
                                    <div class="col-sm-7">
                                        <input type="email" class="form-control" id="colFormLabel" v-model="time_of_inspection">
                                        <span class="text-danger" v-if="errors.time_of_inspection  ">{{ errors.time_of_inspection[0] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="colFormLabel" class="col-sm-5 col-form-label">Cheklist</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" v-model="checklist" @change="getSelectedChecklist(checklist)">
                                            <option v-for="(checklist,c) in checklists" v-bind:key="c" :value="checklist"> {{  checklist[0].name }}</option>
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
                                <div class="form-group row">
                                    <div class="alert alert-info col-md-12" v-if="show_added">
                                        <strong>Success!</strong> Report succesfully added
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="table-responsive" v-if="selected_checklist.length" style="height: 770px">
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
                                        <tr v-for="(checklist, c) in selected_checklist" v-bind:key="c" class="d-flex">
                                            <td scope="row" class="col-sm-1">{{ c + 1 }}</td>
                                            <td class="col-sm-6" style="white-space: inherit;">{{ checklist.requirement +' - '+ checklist.description  }}</td>
                                            <td class="col-sm-2">
                                                <select class="form-control" v-model="points[c]">
                                                    <option value="0"> 0 </option>
                                                    <option value="1"> 1 </option>
                                                    <option value="2"> 2 </option>
                                                </select>
                                                <span class="text-danger" v-if="errors['points.'+c] || errors.points"> This field is required </span> 
                         
                                            </td>
                                            <td class="col-sm-3">
                                                <input type="file" multiple="multiple" id="attachments"  class="attachments" accept="image/*" placeholder="Attach file" @change="uploadFileChange($event,c,checklist.id)"><br>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                             <div class="table-responsive" v-else style="height: 770px; display: inline-flex; align-items: center;">
                                 <span style="margin: 0 auto">No Checklist Selected</span>   
                             </div>
                            <div class="row mb-3 mt-3 ml-1" v-if="selected_checklist.length ">
                                <div class="col-6">

                                </div>
                                <div class="col-6 text-right">
                                    <span>{{ selected_checklist.length }} Checklist(s)</span>
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
                attachment_ids: [],
                attachment_index: [],
                file_index: [],
                index_count: 0,
                reportsPerUser: [],
                errors: [],
                currentPage: 0,
                itemsPerPage: 8,
                keywords: '',
                fileSize: 0,
                maximumSize: 5000000,
                formData: new FormData(),
                show_added: false
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchOperationLines();
            this.fetchCategories();
            this.fetchChecklist();
        },
        methods:{
            prepareFields(){
                if(this.attachments.length > 0){
                    for(var i = 0; i < this.attachments.length; i++){
                        let attachment = this.attachments[i];
                        this.formData.append('attachments[]', attachment);
                    }
                } 
            },
            uploadFileChange(e, index,id){
                var files = e.target.files || e.dataTransfer.files;

                if(!files.length)
                    return;

                this.attachment_index = this.attachment_index ? this.attachment_index.filter(item => item !== index) : '';
                this.attachment_ids = this.attachment_ids ? this.attachment_ids.filter(item => item !== id) : '';

                let splice_index = [];
                let splice_attachment = [];
                //Remove old attach file if there a new attached
                if(this.file_index){
                    Object.entries((this.file_index)).forEach(
                        ([key, value]) => {
                            if(value.id == id){
                                splice_attachment.push(value.file_index);
                                splice_index.push(key);
                            }
                        }
                    );
                }
                if(splice_index){
                    Object.entries((splice_index.reverse())).forEach(
                        ([key, value]) =>  this.file_index.splice(value,1));
                }
                if(splice_attachment){
                    Object.entries((splice_attachment.reverse())).forEach(
                        ([key, value]) => 
                        this.attachments.splice(this.file_index.filter(item => item.file_index == value),1),
                    );
                }
                //END

                for (var i = files.length - 1; i >= 0; i--){
                    this.attachments.push(files[i]);
                    this.attachment_index.push(index);
                    this.attachment_ids.push(id);
                    this.file_index.push({
                        id: id,
                        index: index,
                        file_index: this.index_count = this.index_count + 1,
                    });
                    this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
                }
                // if(this.fileSize > 5){
                //     alert('File size exceeds 5 MB');
                //     document.getElementById('attachments').value = "";
                //     this.attachments = [];
                //     this.fileSize = 0;
                // }
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
                    if(this.company && this.location ){
                        this.fetchProccessOwner();
                    }
                    if(this.company && this.location && this.operation_line && this.category){
                        this.fetchCompayAreas();
                    }
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
            fetchProccessOwner(){
                axios.get(`/user-process-owner/${this.company.id}/${this.location.id}`)
                .then(response => {
                    this.process_owners = response.data;
                })
                .catch(error =>{
                    this.errors = error.response.data.errors;
                })
            },
            getSelectedChecklist(checklist){
                this.errors = [];
                this.points = [];
                this.selected_checklist = [];
                this.selected_checklist = checklist;
                this.attachments = [];
                var removeElements = (elms) => [...elms].forEach(el => el.value = "");
                removeElements( document.querySelectorAll(".attachments"));
            },
            fetchReportsPerUser(){
                axios.get(`/reports-per-user/${this.company.id}/${this.location.id}/${this.operation_line.id}/${this.category.id}/${this.area.id}`)
                .then(response => {
                    this.reportsPerUser = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            addReport(selected_checklist,points){
                this.show_added = false;
                this.errors = [];
                this.prepareFields();
                this.formData.append('company', this.company.id ? this.company.id : '');
                this.formData.append('location', this.location.id ? this.location.id : '');
                this.formData.append('operation_line', this.operation_line.id ? this.operation_line.id : '');
                this.formData.append('category', this.category.id ? this.category.id : '');
                this.formData.append('area', this.area.id ? this.area.id : '');
                this.formData.append('process_owner', this.process_owner ? this.process_owner : '');
                this.formData.append('date_of_inspection', this.date_of_inspection ? this.date_of_inspection : '');
                this.formData.append('time_of_inspection', this.time_of_inspection ? this.time_of_inspection : '');
                this.formData.append('checklist', selected_checklist ? JSON.stringify(selected_checklist) : '');
                this.formData.append('points', points.length == 0 ? '' : points);
                this.formData.append('attachment_ids',this.attachment_ids.length > 0 ? this.attachment_ids : '');  
                this.formData.append('attachment_index',this.attachment_index.length > 0 ? this.attachment_index : '');  

                axios.post('/report', this.formData)
                .then(response => { 
                    this.resetForm();
                    this.show_added = true;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                    this.show_added = false;
                });
            },
            resetForm(){
                this.process_owner = '';
                this.date_of_inspection = '';
                this.time_of_inspection = '';
                this.points = '';
                this.attachments = [];
                var removeElements = (elms) => [...elms].forEach(el => el.value = "");
                removeElements( document.querySelectorAll(".attachments"));
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

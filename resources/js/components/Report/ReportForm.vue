<template>
    <div id="wrapper">
    <loader v-if="loading"></loader>
    <page-header></page-header>
    <div>   
        <div>
            <breadcrumb :user-role-level="userRoleLevel" :user-id="userId"></breadcrumb>
        </div>
        <div style="max-height: 827px;">
            <div class="row row-margin mb-2">
                <div class="col-md-12 text-right">
                    <a :href="publicPath+'/reports-my-drafts'" class="font-weight-bold">MY DRAFTS</a>
                </div>
            </div>
            <div class="row row-margin">
                <div class="col-md-2 card card-report">
                    <div class="card-body">
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Company</label>
                            <select class="form-control" v-model="company" @change="changeCompany">
                                <option v-for="(company,c) in companies" v-bind:key="c" :value="company"> {{ company.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.company  ">{{ errors.company[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Location</label>
                            <select class="form-control" v-model="location" @change="changeLocation">
                                <option v-for="(location,l) in locations" v-bind:key="l" :value="location"> {{ location.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.location  ">{{ errors.location[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Department</label>
                            <select class="form-control" v-model="department" @change="changeDepartment">
                                <option v-for="(department,d) in departments" v-bind:key="d" :value="department"> {{ department.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.department  ">{{ errors.department[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Category</label>
                             <select class="form-control" v-model="category"  @change="changeCategory">
                                <option v-for="(category,c) in categories" v-bind:key="c" :value="category"> {{ category.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.category  ">{{ errors.category[0] }}</span>
                        </div>
                        <div class="form-group form-group-report" v-if="show_operation_line">
                            <label for="exampleInputEmail1">Operation Line</label>
                            <select class="form-control" v-model="operation_line" @change="changeOperationLine">
                                <option v-for="(operation_line,o) in operation_lines" v-bind:key="o" :value="operation_line.operation_line"> {{ operation_line.operation_line.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.operation_line  ">{{ errors.operation_line[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Area</label>
                            <select class="form-control" v-model="area">
                                <option v-for="(area,a) in areas" v-bind:key="a" :value="area"> {{ area.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.area  ">{{ errors.area[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Date of Inspection</label>
                            <input type="date" class="form-control" v-model="date_of_inspection">
                            <span class="text-danger" v-if="errors.date_of_inspection  ">{{ errors.date_of_inspection[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Start Time</label>
                            <input type="time" class="form-control" v-model="start_time_of_inspection">
                            <span class="text-danger" v-if="errors.start_time_of_inspection  ">{{ errors.start_time_of_inspection[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">End Time</label>
                            <input type="time" class="form-control" v-model="end_time_of_inspection">
                            <span class="text-danger" v-if="errors.end_time_of_inspection  ">{{ errors.end_time_of_inspection[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Accompanied by</label>
                            <input type="text" class="form-control" v-model="accompanied_by">
                            <span class="text-danger" v-if="errors.accompanied_by  ">{{ errors.accompanied_by[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Area owner</label>
                            <select class="form-control" v-model="process_owner">
                                <option v-for="(process_owner,p) in process_owners" v-bind:key="p" :value="process_owner"> {{ process_owner.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.process_owner  ">{{ errors.process_owner[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Department Head</label>
                            <select class="form-control" v-model="department_head">
                                <option v-for="(department_head,d) in department_heads" v-bind:key="d" :value="department_head"> {{ department_head.name }}</option>
                            </select>
                            <span class="text-danger" v-if="errors.department_head  ">{{ errors.department_head[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">BU / Cluster Head</label>
                            <input type="text" class="form-control" v-model="bu_head.name" readonly>
                            <span class="text-danger" v-if="errors.bu_head">{{ errors.bu_head[0] }}</span>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Group President</label>
                            <input type="text" class="form-control" v-model="group_president.name" readonly>
                            <span class="text-danger" v-if="errors.group_president">{{ errors.group_president[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive" v-if="selected_checklist.length" style="height: 840px">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr class="d-flex">
                                            <th scope="col" class="col-1">ID</th>
                                            <th scope="col" class="col-5">Requirement</th>
                                            <th scope="col" class="col-1">Previous Rating</th>
                                            <th scope="col" class="col-2">Upload Picture</th>
                                            <th scope="col" class="col-1">Rating</th>
                                            <th scope="col" class="col-1">Recurrence No.</th>
                                            <th scope="col" class="col-1">Criticality</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(checklist, c) in selected_checklist" v-bind:key="c" class="d-flex">
                                            <td scope="row" class="col-sm-1">{{ c + 1 }}</td>
                                            <td class="col-sm-5" style="white-space: inherit;">{{ checklist.requirement +' - '+ checklist.description  }}</td>
                                            <td class="col-sm-1">Previous Rating</td>
                                            <td class="col-sm-2">
                                                <input type="file" multiple="multiple" :id="'attachments'+c"  class="attachments" accept="image/*" placeholder="Attach file" @change="uploadFileChange($event,c,checklist.id)"><br>
                                            </td>
                                            <td class="col-sm-1">
                                                <select class="form-control select-points" v-model="checklist.rating" @change="checkRating($event,c)">
                                                    <option value="N/A"> N/A </option>
                                                    <option value="0"> 0 </option>
                                                    <option value="1"> 1 </option>
                                                    <option value="2"> 2 </option>
                                                </select>
                                                <div v-for="(points_error, p) in points_errors" :key="p">
                                                    <span class="text-danger" v-if="points_error == c"> This field is required </span>
                                                </div>
                                            </td>
                                            <td class="col-sm-1">
                                                <select class="form-control select-points recurrence_number" :id="'recurrence-'+c" disabled v-model="checklist.recurrence_number">
                                                    <option value="0"> 0 </option>
                                                    <option value="1"> 1 </option>
                                                    <option value="2"> 2 </option>
                                                    <option value="3"> 3 </option>
                                                    <option value="4"> 4 </option>
                                                    <option value="5"> 5 </option>
                                                    <option value="6"> 6 </option>
                                                    <option value="7"> 7 </option>
                                                    <option value="8"> 8 </option>
                                                    <option value="9"> 9 </option>
                                                    <option value="10"> 10 </option>
                                                    <option value="11"> 11 </option>
                                                    <option value="12"> 12 </option>
                                                </select>
                                                <div v-for="(recurrence_number_error, r) in recurrence_number_errors" :key="r">
                                                    <span class="text-danger" v-if="recurrence_number_error == c"> This field is required </span>
                                                </div>
                                            </td>
                                            <td class="col-sm-1">
                                                <select class="form-control select-points criticality" :id="'criticality-'+c" disabled v-model="checklist.criticality">
                                                    <option value="Critical"> Critical </option>
                                                    <option value="Non - critical"> Non - critical </option>
                                                </select>
                                                <div v-for="(criticality_error, cc) in criticality_errors" :key="cc">
                                                    <span class="text-danger" v-if="criticality_error == c"> This field is required </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" v-else style="height: 840px; display: inline-flex; align-items: center;">
                                <span style="margin: 0 auto">No Checklist Selected</span>   
                            </div>
                            <div class="row mb-3 mt-3 ml-1" v-if="selected_checklist.length ">
                                <div class="col-6"></div>
                                <div class="col-6 text-right">
                                    <span>{{ selected_checklist.length }} Checklist(s)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-right mt-5">
                        <div class="col-md-11" style="margin: 0 auto">
                            <div class="alert alert-success text-center" role="alert" v-if="report_as_draft">
                                REPORT SUCCESSFULLY SAVE AS DRAFT
                            </div>
                            <div class="alert alert-danger text-center" role="alert" v-if="errors.server_error">
                                <span><b> Server Error - </b> {{ errors.server_error }}</span>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary btn- btn-lg" @click="addReport(selected_checklist,points)" id="addReport" :disabled="selected_checklist.length < 1"> SAVE AS DRAFT</button>
                        </div>
                    </div>
                    <div class="row row-margin">
                        <div>
                            <span>Rating Criteria:</span><br>
                            <span>0: More than 2 Non-Conformance</span><br> 
                            <span>1: 1-2 Non-Conformances</span><br> 
                            <span>2: Full Compliance</span><br>
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
    export default {
        props: ['userName', 'userRoleLevel', 'userId'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                companies : [],
                locations: [],
                departments: [],
                operation_lines: [],
                categories: [],
                areas: [],
                selected_checklist:[],
                company: '',
                department: '',
                location: '',
                operation_line: '',
                category: '',
                area: '',
                process_owner: '',
                process_owners: [],
                date_of_inspection: '',
                start_time_of_inspection: '',
                end_time_of_inspection: '',
                accompanied_by: '',
                selected_data: [],
                points: [],
                attachments: [],
                attachment_ids: [],
                attachment_index: [],
                file_index: [],
                index_count: 0,
                reportsPerUser: [],
                errors: [],
                points_errors: [],
                recurrence_number_errors: [],
                criticality_errors: [],
                fileSize: 0,
                maximumSize: 5000000,
                formData: new FormData(),
                report_as_draft: false,
                loading: false,
                show_operation_line: false,
                department_head: '',
                department_heads: [],
                bu_head: '',
                group_president: '',
                default_category: ''
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchCategories();
        },
        methods:{
            checkRating(e,index){
                if(event.target.value == 'N/A' || event.target.value == '2'){
                    document.getElementById("recurrence-"+index).disabled = true;
                    document.getElementById("criticality-"+index).disabled = true;
                }else{
                    document.getElementById("recurrence-"+index).disabled = false;
                    document.getElementById("criticality-"+index).disabled = false;
                }
            },
            showLoader(){
               this.loading = true;
            },
            customLabelLocation (selectedTopManagement) {
                return `${selectedTopManagement.name  }`
            },
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
                
                let numberOfFiles = files.length + this.attachments.length;
                if(numberOfFiles > 30){ //Restrict user to attach only 30 files per report
                    alert('Attachment exceed 30 files');
                    document.getElementById('attachments'+index).value = "";
                    return false;
                }
               
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

                // Check size of each image
                var fileSizeErrors = 0;
                for (var i = files.length - 1; i >= 0; i--){
                    // this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
                    var imageSize = files[i].size / 1024 / 1024;

                    if(imageSize > 1){// Restrict 1 mb per attachment
                        fileSizeErrors = fileSizeErrors + 1;
                    }
                }
                
                if(fileSizeErrors == 0){// If no errors attach file to an array
                    for (var i = files.length - 1; i >= 0; i--){
                        this.attachments.push(files[i]);
                        this.attachment_index.push(index);
                        this.attachment_ids.push(id);
                        this.file_index.push({
                            id: id,
                            index: index,
                            file_index: this.index_count = this.index_count + 1,
                        });
                    }
                }else{//Remove attachment
                    alert('File size exceeds 1MB Please resize image');
                    document.getElementById('attachments'+index).value = "";
                }
            },
            changeCompany(){
                this.location = '';
                this.locations = [];
                this.operation_lines = [];
                this.operation_line = '';
                this.areas = [];
                this.area = '';
                this.process_owners = [];
                this.process_owner = '';
                this.department_heads = [];
                this.department_head = '';
                this.bu_head = '';
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
                this.changeDepartment();
            },
            changeLocation(){
               if(this.category.name == "Operations"){
                    this.fetchOperationLines();
                }else{
                    this.fetchCompayAreas();
                }
                this.changeDepartment();
            },
            changeDepartment(){
                if(this.company && this.location){
                    this.fetchProccessOwner();
                    this.fetchDepartmentHead();
                    this.fetchbuHead();
                    this.fetchGroupPresident();
                }
            },
            changeCategory(){
                if(!this.default_category){
                    this.fetchChecklist();
                    this.default_category = this.category.name;
                }else{
                    if(this.default_category !== this.category.name){
                        this.fetchChecklist();
                        this.default_category = this.category.name;
                    }
                }

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
            fetchOperationLines(){
                axios.get(`/operation-lines/${this.company.id}/${this.location.id}`)
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
                axios.get(`/company-areas-per-company/${this.company.id}/${this.location.id}/${this.category.id}/${operationId}`)
                 .then(response => {
                    this.areas =  response.data.length > 0 ? response.data[0].areas : [];
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                });
            },
            fetchChecklist(){
                this.errors = [];
                this.points = [];
                this.selected_checklist = [];
                this.attachments = [];
                this.attachment_ids = [];
                this.attachment_index = [];
                var removeElements = (elms) => [...elms].forEach(el => el.value = "");
                removeElements( document.querySelectorAll(".attachments"));

                axios.get(`/checklists-per-category/${this.category.name}`)
                .then(response => {
                    this.selected_checklist = response.data;
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
            fetchDepartmentHead(){
                axios.get(`/user-department-head/${this.company.id}/${this.location.id}`)
                .then(response => {
                    this.department_heads = response.data;
                })
                .catch(error =>{
                    this.errors = error.response.data.errors;
                })
            },
            fetchbuHead(){
                axios.get(`/user-bu-head/${this.company.id}/${this.location.id}`)
                .then(response => {
                    this.bu_head = response.data;
                })
                .catch(error =>{
                    this.errors = error.response.data.errors;
                })
            },
            fetchGroupPresident(){
                axios.get('/user-group-president')
                .then(response => {
                    this.group_president = response.data;
                })
                .catch(error =>{
                    this.errors = error.response.data.errors;
                })
            },
            addReport(selected_checklist,points){
                document.getElementById("addReport").disabled = true;
                this.loading = true;
                this.report_as_draft = false;
                this.formData = new FormData();
                let t = this;
                this.points = [];
                this.points_errors = [];
                this.recurrence_number_errors = [];
                this.criticality_errors = [];
                selected_checklist.filter(function(item,index) {
                    if(item.rating !== null){
                        t.points.push(item.rating);
                        if(item.rating !== 'N/A' && item.rating !== '2'){
                            if(item.recurrence_number == null){
                                t.recurrence_number_errors.push(index);
                            }
                            if(item.criticality == null){
                                t.criticality_errors.push(index);
                            }
                        }
                    }else{
                        t.points_errors.push(index);
                    }
                });
                if(this.points_errors.length || this.recurrence_number_errors.length || this.criticality_errors.length){
                    this.loading = false;
                    document.getElementById("addReport").disabled = false;
                    return false;
                }
                this.errors = [];
                this.prepareFields();
                this.formData.append('company', this.company.id ? this.company.id : '');
                this.formData.append('location', this.location.id ? this.location.id : '');
                this.formData.append('department', this.department.id ? this.department.id : '');
                this.formData.append('operation_line', this.operation_line.id ? this.operation_line.id : '');
                this.formData.append('category', this.category.id ? this.category.id : '');
                this.formData.append('area', this.area ? this.area.id : '');
                this.formData.append('process_owner', this.process_owner ? this.process_owner.id : '');
                this.formData.append('date_of_inspection', this.date_of_inspection ? this.date_of_inspection : '');
                this.formData.append('start_time_of_inspection', this.start_time_of_inspection ? this.start_time_of_inspection : '');
                this.formData.append('end_time_of_inspection', this.end_time_of_inspection ? this.end_time_of_inspection : '');
                this.formData.append('accompanied_by', this.accompanied_by ? this.accompanied_by : '');
                this.formData.append('checklist', selected_checklist ? JSON.stringify(selected_checklist) : '');
                this.formData.append('points', this.points.length == 0 ? '' : this.points);
                this.formData.append('attachment_ids',this.attachment_ids.length > 0 ? this.attachment_ids : '');  
                this.formData.append('attachment_index',this.attachment_index.length > 0 ? this.attachment_index : '');
                this.formData.append('department_head', this.department_head.id ? this.department_head.id : '');
                this.formData.append('bu_head', this.bu_head.id ? this.bu_head.id : '');
                this.formData.append('group_president', this.group_president.id ? this.group_president.id : '');

                axios.post('/report', this.formData)
                .then(response => { 
                    this.resetForm();
                    this.report_as_draft = true;
                    this.loading = false;
                    document.getElementById("addReport").disabled = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.report_as_draft = false;
                    this.loading = false;
                    document.getElementById("addReport").disabled = false;
                });
            },
            resetForm(){
                this.company = '';
                this.location = '';
                this.department = '';
                this.category = '';
                this.operation_line = '';
                this.area = '';
                this.process_owner = '';
                this.department_head = '',
                this.bu_head ='',
                this.group_president = '',
                this.date_of_inspection = '';
                this.start_time_of_inspection = '';
                this.end_time_of_inspection = '';
                this.accompanied_by = '';
                this.points = [];
                this.attachments = [];
                var removeElements = (elms) => [...elms].forEach(el => el.value = "");
                removeElements( document.querySelectorAll(".attachments"));

                const recurrence_number = document.querySelectorAll('.recurrence_number');
                const criticality = document.querySelectorAll('.criticality');
                recurrence_number.forEach(input => input.disabled = true);
                criticality.forEach(input => input.disabled = true);
                this.selected_checklist = [];
                // this.selected_checklist.filter(checklist => {
                //     checklist.rating = '';
                //     checklist.recurrence_number = '';
                //     checklist.criticality = '';
                // });
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
            }
        },
        computed:{
            publicPath(){
                return window.location.origin;
            }
        }
    }
</script>

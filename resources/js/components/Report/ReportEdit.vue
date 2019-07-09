<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-2">
                    <span class="span-username">Hi, {{ this.userName }}</span>
                </div>
                <div class="col-md-1">
                    <navbarRight :user-role-level="userRoleLevel" :user-id="userId"></navbarRight>
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
                    <div class="card-body"> 
                        <div>
                            <div class="row" v-if="reportsPerUser.length">
                                <div class="col-md-3 card card-view-report" style="height: 770px">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <h1 class="col-sm-12 ">{{ this.reportsPerUser[0].category.name }}</h1>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-sm-4 ">Company:</span>
                                            <div class="col-sm-8">
                                                <span> {{ this.reportsPerUser[0].company.name }} </span>
                                            </div>
                                        </div>
                                        <div class="form-group row" v-if="this.reportsPerUser[0].operation_line">
                                            <span class="col-sm-4 ">Operation Line:</span>
                                            <div class="col-sm-8">
                                                <span> {{ this.reportsPerUser[0].operation_line.name }} </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-sm-4 ">Area:</span>
                                            <div class="col-sm-8">
                                                <span> {{ this.reportsPerUser[0].area.name }}  </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <span class="col-sm-4 ">Inspector:</span>
                                            <div class="col-sm-8">
                                                <span> {{ this.reportsPerUser[0].inspector.name }} </span>
                                            </div>
                                        </div>
                                        <div v-if="reportsPerUser.length">
                                            <div class="form-group row">
                                                <span class="col-sm-4">Date of Inspection:</span>
                                                <div class="col-sm-8">
                                                    <span>{{ this.reportsPerUser[0].date_of_inspection }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <span class="col-sm-4">Time</span>
                                                <div class="col-sm-8">
                                                    <span>{{ this.reportsPerUser[0].start_time_of_inspection + ' - ' + this.reportsPerUser[0].end_time_of_inspection  }} </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <h1 class="col-sm-12">Rating:</h1>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-sm-1"></div>
                                                <div class="col-sm-10 div-rating">
                                                    <span>{{ countRating(this.reportsPerUser[0].report_detail) }} %</span>
                                                </div>
                                                <div class="col-sm-1"></div>
                                            </div> 
                                        </div>
                                        <div class="form-group row" v-if="this.reportsPerUser[0].status == 1">
                                            <div class="col-sm-3"></div>
                                            <div class="col-sm-6">
                                                <button id="btn-approved" class="btn btn-block btn-primary" data-toggle="modal" data-target="#updateModal">Update</button>
                                            </div>
                                            <div class="col-sm-3"></div>
                                             <div class="alert alert-danger col-md-12 mt-2" v-if="errors.id">
                                                <strong><span class="text-white" v-if="errors.id">{{ errors.id[0] }}</span></strong>
                                            </div>
                                        </div>
                                        <div class="form-group row" v-else>
                                            <div class="alert alert-info col-md-12">
                                                <strong>Success!</strong> Report succesfully updated
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9" style="height: 770px">
                                    <div class="table-responsive" style="height: 770px">
                                        <div class="col-md-12 card mb-3" v-for="(checklist, c) in reportsPerUser[0].report_detail" v-bind:key="c" style="background-color: #e6e6e6 !important; min-height: 300px;">
                                            <div class="card-body">
                                                <span>{{ c + 1 +'. '+checklist.name }}</span><br>
                                                <div class="row">
                                                    <select class="form-control select-points" v-model="checklist.points" style="width: 75px" :disabled="reportsPerUser[0].status !=1">
                                                        <option value="N/A"> N/A </option>
                                                        <option value="0"> 0 </option>
                                                        <option value="1"> 1 </option>
                                                        <option value="2"> 2 </option>
                                                    </select>
                                                    <input type="file" multiple="multiple" id="attachments"  class="attachments ml-2 mt-2" accept="image/*" placeholder="Attach file" @change="uploadFileChange($event,c,checklist.id,checklist.checklist_id)">
                                                </div>
                                                <div v-for="(points_error, p) in points_errors" :key="p">
                                                    <span class="text-danger" v-if="points_error == c"> This field is required </span>
                                                </div>
                                                <div class="row mt-2 text-center">
                                                    <div class="col-md-3" :id="'photo-'+uploadFile.id" v-for="(uploadFile, u) in checklist.uploaded_files" :key="u">
                                                        <div class="text-right mr-5">
                                                            <span @click="removePhoto(uploadFile.id, 'photo-'+uploadFile.id)" class="cursor-pointer">X</span>
                                                        </div>
                                                        <img class="report-img mb-2"  :src="attachmentLink + uploadFile.file_path"><br>
                                                        <span>{{ c + 1 +'.' }} </span> <span> {{  u + 1  }} </span>
                                                        <!-- <input type="text" :id="uploadFile.id" class="form-control comment-input"  placeholder="Comment..." v-model="uploadFile.comment" disabled> -->
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="reportsPerUser.length ">
                                        <div class="col-6">
                                            
                                        </div>
                                        <div class="col-6 text-right">
                                            <span>{{ reportsPerUser.length }} Checklist(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                No report available
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Report Modal -->
                <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCompanyLabel">Update Report</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        Are you sure you want to Update this Report?
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                            <button class="btn btn-primary" @click="updateReport">Update</button>
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
        props: ['userName', 'userRoleLevel' ,'reportId', 'userId'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                points: [],
                reportsPerUser: [],
                comments: [],
                points_errors: [],
                final_rating: '',
                deleted_uploaded_id: [],
                attachments: [],
                attachment_ids: [],
                attachment_index: [],
                checklist_ids: [],
                file_index: [],
                show_forChecking: false,
                checklists_ids: [],
                formData: new FormData(),
                errors: [],
                currentPage: 0,
                itemsPerPage: 8,
                keywords: '',
                loading: false
            }
        },
        created(){
            this.fetchReportsPerUser();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            removePhoto(id,element){
                this.deleted_uploaded_id.push(id);
                document.getElementById(element).style.display = 'none';
            },
            prepareFields(){
                if(this.attachments.length > 0){
                    for(var i = 0; i < this.attachments.length; i++){
                        let attachment = this.attachments[i];
                        this.formData.append('attachments[]', attachment);
                    }
                } 
            },
            uploadFileChange(e, index,id, checklist_id){
                var files = e.target.files || e.dataTransfer.files;

                if(!files.length)
                    return;

                this.attachment_index = this.attachment_index ? this.attachment_index.filter(item => item !== index) : '';
                this.attachment_ids = this.attachment_ids ? this.attachment_ids.filter(item => item !== id) : '';
                this.checklist_ids = this.checklist_ids ? this.checklist_ids.filter(item => item !== checklist_id) : '';

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
                    this.checklist_ids.push(checklist_id)
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
            fetchReportsPerUser(){
                axios.get(`/reports-per-user/${this.reportId}`)
                .then(response => {
                    this.reportsPerUser = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            countRating(reportsPerUser){
                var denominator = 0;
                var total_points = 0;
                reportsPerUser.filter(item => {
                    if(item.points !== 'N/A'){
                        total_points = parseInt(total_points) + parseInt(item.points);
                        denominator = denominator + 1;
                    }
                });
                return this.final_rating = total_points / (denominator * 2) * 100;
            },
            updateReport(){
                this.loading = true;
                this.enabledBtn();
                this.errors = [];

                let t = this;
                this.points_errors = [];
                this.reportsPerUser[0].report_detail.filter(function(item) {
                    t.points.push(item.points);
                });
                 
                this.prepareFields();
                this.formData.append('id', this.reportsPerUser[0].id ? this.reportsPerUser[0].id : '');
                this.formData.append('process_owner_id', this.reportsPerUser[0].process_owner_id ?  this.reportsPerUser[0].process_owner_id : '');
                this.formData.append('final_rating', this.final_rating ? this.final_rating : '');
                this.formData.append('points', this.points.length == 0 ? '' : this.points);
                this.formData.append('deleted_uploaded_id', this.deleted_uploaded_id ? this.deleted_uploaded_id : '');
                this.formData.append('attachment_ids',this.attachment_ids.length > 0 ? this.attachment_ids : '');  
                this.formData.append('attachment_index',this.attachment_index.length > 0 ? this.attachment_index : '');
                this.formData.append('checklist_ids',this.checklist_ids.length > 0 ? this.checklist_ids : ''); 

                axios.post('/report-update', this.formData)
                .then(response => {
                    this.show_approved = true;
                    this.disabledBtn();
                    this.reportsPerUser = response.data;
                    $('#updateModal').modal('hide');
                    this.loading = false;
                    window.location.reload();
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                    this.enabledBtn();
                    $('#updateModal').modal('hide');
                    this.loading = false;
                })
            },
            disabledBtn(){
                document.getElementById('btn-approved').disabled = true;
            },
            enabledBtn(){
                document.getElementById('btn-approved').disabled = false;
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
        },
        computed:{
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
            attachmentLink(){
                 return window.location.origin+'/storage/';
            }
        }
    }
</script>

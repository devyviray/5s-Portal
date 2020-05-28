<template>
    <div id="wrapper">
        <loader v-if="loading"></loader>
        <page-header></page-header>
        <div>
            <div>
                <breadcrumb :user-role-level="userRoleLevel" :user-id="userId"></breadcrumb>
            </div>
            <div class="row row-margin">
                <div class="col-md-3 ml-4">
                    <a v-if="this.reportsPerUser[0].status == 1" :href="publicPath + '/reports-my-drafts'"><h3><i class="fas fa-chevron-circle-left"></i> Back to Draft</h3></a>
                    <a v-if="this.reportsPerUser[0].status == 2" :href="publicPath + '/reports-notifications'"><h3><i class="fas fa-chevron-circle-left"></i> Back to Notification</h3></a>
                </div>
                <div class="col-md-7 text-center">
                    <h1 class="text-primary">REPORT EDIT</h1>
                </div>
                <div class="col-md-2"></div>
            </div>
            <div style="max-height: 800px;" v-if="reportsPerUser.length > 0">
                <div class="row row-margin">
                    <div class="col-md-2 card card-report">
                        <div class="form-group row">
                            <h1 class="col-sm-12 ">{{ this.reportsPerUser[0].category.name }}</h1>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Business Unit</label><br>
                            <p class="custom-p">{{ this.reportsPerUser[0].company.name }}</p>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Department</label><br>
                            <p class="custom-p">{{ this.reportsPerUser[0].department.name }}</p>
                        </div>
                        <div class="form-group form-group-report" v-if="this.reportsPerUser[0].operation_line">
                            <label for="exampleInputEmail1">Operation Line</label><br>
                            <p class="custom-p">{{ this.reportsPerUser[0].operation_line.name }}</p>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Area</label><br>
                            <p class="custom-p">{{ this.reportsPerUser[0].area.name }}</p>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Inspector</label><br>
                            <p class="custom-p">{{ this.reportsPerUser[0].inspector.name }}</p>
                        </div>
                        <div class="form-group form-group-report" v-if="reportsPerUser.length">
                            <label for="exampleInputEmail1">Date of Inspection</label><br>
                            <p class="custom-p">{{ moment(this.reportsPerUser[0].date_of_inspection).format('LL') }}</p>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Time of Inspection</label><br>
                            <p class="custom-p">{{ moment( this.reportsPerUser[0].start_time_of_inspection, "hh").format('LT') + ' - ' + moment( this.reportsPerUser[0].end_time_of_inspection, "hh").format('LT') }}</p>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Accompanied By</label><br>
                            <p class="custom-p">{{ this.reportsPerUser[0].accompanied_by }} </p>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Area Owner</label><br>
                            <p class="custom-p">{{ this.reportsPerUser[0].process_owner.name }} </p>
                        </div>
                        <div class="form-group form-group-report">
                            <label for="exampleInputEmail1">Department Head</label><br>
                            <p class="custom-p">{{ this.reportsPerUser[0].department_head.name }} </p>
                        </div>
                        <div class="form-group form-group-report mb-4">
                            <label for="exampleInputEmail1">Rating</label><br>
                            <p class="custom-p">{{ countRating(this.reportsPerUser[0].report_detail) }} %</p>
                        </div>
                    </div>
                    <div class="col-md-10" style="height: 800px">
                        <div class="table-responsive" style="height: 800px">
                            <div class="col-md-12 card mb-3" v-for="(checklist, c) in reportsPerUser[0].report_detail" v-bind:key="c" style="min-height: 300px;">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr class="d-flex">
                                                    <th scope="col" class="col-1">ID</th>
                                                    <th scope="col" class="col-6">Requirement</th>
                                                    <th scope="col" class="col-2">Upload Picture</th>
                                                    <th scope="col" class="col-1">Rating</th>
                                                    <th scope="col" class="col-1">Recurrence No.</th>
                                                    <th scope="col" class="col-1">Criticality</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="d-flex">
                                                    <td scope="row" class="col-sm-1">{{ c + 1 }}</td>
                                                    <td class="col-sm-6" style="white-space: inherit;">{{ checklist.name  }}</td>
                                                    <td class="col-sm-2">
                                                        <input type="file" multiple="multiple" :id="'attachments'+c"  class="attachments ml-2 mt-2" accept="image/*" placeholder="Attach file" @change="uploadFileChange($event,c,checklist.id,checklist.checklist_id)">
                                                    </td>
                                                    <td class="col-sm-1">
                                                        <select class="form-control select-points" v-model="checklist.points" :disabled="reportsPerUser[0].status > 3" @change="checkRating($event,c)">
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

                                    <div class="row mt-2 text-center">
                                        <div class="col-md-3" :id="'photo-'+uploadFile.id" v-for="(uploadFile, u) in checklist.uploaded_files" :key="u">
                                            <div class="text-right mr-5">
                                                <span @click="removePhoto(uploadFile.id, 'photo-'+uploadFile.id)" class="cursor-pointer text-danger"><i class="fas fa-times"></i></span>
                                            </div>
                                            <img class="report-img mb-2"  :src="attachmentLink + uploadFile.file_path"><br>
                                            <!-- <span>{{ c + 1 +'.' }} </span> <span> {{  u + 1  }} </span> -->
                                            <textarea cols="5" rows="3" :disabled="reportsPerUser[0].inspector_id != userId" :id="uploadFile.id" class="form-control description-input"  placeholder="Type Description of Non-conformity" v-model="uploadFile.description"></textarea>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-margin text-right mt-3">
                            <div class="col-md-12">
                                <button type="button" id="btn-approved" class="btn btn-primary btn- btn-lg" data-toggle="modal" data-target="#updateModal" v-if="this.reportsPerUser[0].status < 3">UPDATE REPORT</button>
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
                    <button class="btn btn-primary" @click="updateReport" id="updateReport">Update</button>
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
                points: [],
                reportsPerUser: [],
                descriptions: [],
                points_errors: [],
                recurrence_number_errors: [],
                criticality_errors: [],
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
            moment,
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

                // Check size of each image
                var fileSizeErrors = 0;
                for (var i = files.length - 1; i >= 0; i--){
                    // this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
                    var imageSize = files[i].size / 1024 / 1024;

                    if(imageSize > 5){
                        fileSizeErrors = fileSizeErrors + 1;
                    }
                }
                
                // If no errors attach file to an array
                if(fileSizeErrors == 0){
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
                    }
                }else{//Remove attachment
                    alert('File size exceeds 5 MB');
                    document.getElementById('attachments'+index).value = "";
                }
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
                this.final_rating = total_points / (denominator * 2) * 100;
                return this.final_rating.toFixed(2);
            },
            updateReport(){

                document.getElementById("updateReport").disabled = true;
                this.loading = true;
                this.enabledBtn();
                this.errors = [];
                let t = this;
                this.points_errors = [];
                this.recurrence_number_errors = [];
                this.criticality_errors = [];
                this.reportsPerUser[0].report_detail.filter(function(item,index) {
                    if(item.points !== null){
                        t.points.push(item.points);
                        if(item.points !== 'N/A' && item.points !== '2'){
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
                    document.getElementById("updateReport").disabled = false;
                    $('#updateModal').modal('hide');
                    return false;
                }

                $(".description-input").map(function() { // Get all inputted description
                    t.descriptions.push({
                        id: $(this).attr('id'),
                        text: $(this).val()
                    });
                }).get();

                this.prepareFields();
                this.formData.append('id', this.reportsPerUser[0].id ? this.reportsPerUser[0].id : '');
                this.formData.append('process_owner_id', this.reportsPerUser[0].process_owner_id ?  this.reportsPerUser[0].process_owner_id : '');
                this.formData.append('final_rating', this.final_rating ? this.final_rating : '');
                this.formData.append('points', this.points.length == 0 ? '' : this.points);
                this.formData.append('deleted_uploaded_id', this.deleted_uploaded_id ? this.deleted_uploaded_id : '');
                this.formData.append('attachment_ids',this.attachment_ids.length > 0 ? this.attachment_ids : '');  
                this.formData.append('attachment_index',this.attachment_index.length > 0 ? this.attachment_index : '');
                this.formData.append('checklist_ids',this.checklist_ids.length > 0 ? this.checklist_ids : '');
                this.formData.append('report_details', this.reportsPerUser.length > 0 ? JSON.stringify(this.reportsPerUser[0].report_detail) : '');
                this.formData.append('descriptions', this.descriptions.length > 0 ? JSON.stringify(this.descriptions) : '');

                axios.post('/report-update', this.formData)
                .then(response => {
                    this.show_approved = true;
                    this.disabledBtn();
                    this.reportsPerUser = response.data;
                    $('#updateModal').modal('hide');
                    this.loading = false;
                    window.location.reload();
                    document.getElementById("updateReport").disabled = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                    this.enabledBtn();
                    $('#updateModal').modal('hide');
                    this.loading = false;
                    document.getElementById("updateReport").disabled = false;
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
            publicPath(){
                return window.location.origin;
            },
            attachmentLink(){
                 return window.location.origin+'/storage/';
            }
        }
    }
</script>

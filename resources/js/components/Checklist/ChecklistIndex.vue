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
                    <navbarRight :user-role="userRole"></navbarRight>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="div-spacing"></div>     
            <div class="header">
                <h1 class="page-header">
                    <img class="lafil-logo" :src="logoLink">
                    <b>5S PORTAL - CHECKLIST</b>
                </h1>
                <breadcrumb></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Checklist List</h3>
                        </div> 
                        <div class="col text-right">
                            <a href="javascript.void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">Add new</a>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-xl-4 mb-2 mt-3 float-right">
                            <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="keywords">
                        </div> 
                    </div>
                </div>
                <!-- Checklst table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <!-- <th scope="col">ID</th> -->
                            <th scope="col">Name</th>
                            <th scope="col">Batch</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(checklist, c) in filteredQueues" v-bind:key="c">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(checklist,c)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(checklist,c)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td>{{ checklist[0].name }}</td>
                            <td>{{ checklist[0].batch }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mb-3 mt-3 ml-3" v-if="filteredQueues.length ">
                <div class="col-6">
                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                    <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                </div>
                <div class="col-6 text-right">
                    <span>{{ filteredQueues.length }} Filtered User(s)</span><br>
                    <span>{{ checklists.length }} Total User(s)</span>
                </div>
            </div>
        </div>
        <!-- Add Checklist Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Checklist</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="checklist_added">
                            <strong>Success!</strong> Checklist succesfully added
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <span class="col-sm-2 ">Name:</span>
                                        <div class="col-sm-10">
                                            <input type="text" id="checklist_name" class="form-control" v-model="checklist_name" placeholder="Checklist name">
                                            <span class="text-danger" v-if="errors.checklist_name">{{ errors.checklist_name[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-2">
                                    <i @click="addRow('Add')" class="fa fa-plus-circle text-green" style="font-size:30px;cursor:pointer; margin-right:23px; float:right"></i>
                                </div>
                            </div>
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>ID</th>
                                    <th>Requirements</th>
                                    <th>Descriptions</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(checklistAdd, c) in checklistAdds" v-bind:key="c">
                                        <td>{{ c + 1 }}</td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Requirement" v-model="checklistAdd.requirement">
                                            <span class="text-danger" v-if="errors['checklistAdds.'+c+'.requirement']">This field is required</span>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Description" v-model="checklistAdd.description">
                                            <span class="text-danger" v-if="errors['checklistAdds.'+c+'.description']">This field is required</span>
                                        </td>
                                        <td>
                                            <i @click="deleteRow(c,'','Add')" class="fa fa-times-circle text-red" style="font-size:30px;cursor:pointer"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addChecklist(checklistAdds)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Checklist Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit Checklist</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="checklist_updated">
                            <strong>Success!</strong> Checklist succesfully updated
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <span class="col-sm-2 ">Name:</span>
                                        <div class="col-sm-10">
                                            <input type="text" id="checklist_name_copied" class="form-control" v-model="checklist_name_copied" placeholder="Checklist name">
                                            <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-2">
                                    <i @click="addRow('Update')" class="fa fa-plus-circle text-green" style="font-size:30px;cursor:pointer; margin-right:23px; float:right"></i>
                                </div>
                            </div>
                            <table class="table table-hover table-striped">
                                <thead>
                                    <th>ID</th>
                                    <th>Requirements</th>
                                    <th>Descriptions</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(checklist_copied, c) in checklist_copieds" v-bind:key="c">
                                        <td>{{ parseInt(c) + parseInt(1) }}</td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Requirement" v-model="checklist_copied.requirement">
                                            <span class="text-danger" v-if="errors['checklist_copieds.'+c+'.requirement']">This field is required</span>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Description" v-model="checklist_copied.description">
                                            <span class="text-danger" v-if="errors['checklist_copieds.'+c+'.description']">This field is required</span>
                                        </td>
                                        <td>
                                            <i @click="deleteRow(c,checklist_copied.id,'Update')" class="fa fa-times-circle text-red" style="font-size:30px;cursor:pointer"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateChecklist(checklist_copieds)">Save</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delete User Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Checklist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Checklist?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteChecklist">Delete</button>
                </div>
                </div>
            </div>
        </div>

</div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
    import Multiselect from 'vue-multiselect';
    import vSelect from 'vue-select'
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import loader from '../Loader';
    export default {
        props: ['userName', 'userRole'],
        components:{
            Multiselect,
            vSelect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                checklists : [],
                checklist: [],
                checklists_copied: [],
                default_checklists_id: [],
                checklists_id: '',
                checklist_added: false,
                checklist_updated: false,
                checklist_name: '',
                checklist_name_copied: '',
                checklistAdds: [{
                    requirement: '',
                    description: '',
                }],
                checklist_copieds: [{
                    requirement: '',
                    description: '',
                }],
                checklist_copieds_index: '', 
                companies: [],
                company:[],
                locations: [],
                location: [],
                roles: [],
                role: [], 
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
            }
        },
        created(){
            this.fetchChecklists();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            copyObject(checklist,index){
                this.default_checklists_id = [];
                this.errors = [];
                this.checklist_add = false;
                this.checklist_updated = false;
                this.checklist_copieds =  checklist;
                this.checklist_copieds_index = index;              
                this.checklist_copieds_batch = checklist[0].batch;
                this.checklist_name_copied = checklist[0].name;

                checklist.forEach((item) => {
                    this.default_checklists_id.push(item.id);
                });  

            },
            addRow(action){
                if(action == 'Add'){
                    // this.checklistAdds.splice(c + 1, 0,{
                    //     requirement: '',
                    //     description: ''
                    // })
                    this.checklistAdds.push({
                        requirement: '',
                        description: ''
                    });
                }else{
                    this.checklist_copieds.push({
                        requirement: '',
                        description: ''
                    });
                }
            },
            deleteRow(index,id,action) {
                const ids = []
                if(action == 'Add'){
                    this.checklistAdds.length < 2 ? alert('Unable to delete all row') : this.checklistAdds.splice(index,1);
                }else{
                    if(this.checklist_copieds.length < 2){
                        alert('Unable to delete all row')
                    }else{
                        this.checklist_copieds.splice(index,1);
                        ids.unshift(id);
                    }
                }
            },
            addChecklist(checklistAdds){
                document.getElementById('add_btn').disabled = true;
                this.checklist_added = false;
                this.errors = [];
                axios.post('/checklist', {
                    name: this.checklist_name,
                    checklistAdds: checklistAdds
                })
                .then(response => {
                    this.checklists.unshift(response.data);
                    this.checklist_added = true;
                    document.getElementById('add_btn').disabled = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.checklist_added = false;
                    document.getElementById('add_btn').disabled = false;
                })
            },
            updateChecklist(checklist_copieds){
                var batch = checklist_copieds.length == 1 ? checklist_copieds[0].batch : checklist_copieds[1].batch;
                document.getElementById('edit_btn').disabled = true;
                this.checklist_updated = false;
                this.errors = [];
                var remained_id = [];
                 checklist_copieds.forEach((item) => {
                     if(item.id){
                        remained_id.push(item.id);
                     }
                });

                axios.post(`/checklist/${batch}`, {
                    checklist_copieds: checklist_copieds,
                    default_checklists_id: this.default_checklists_id,
                    remained_id: remained_id,
                    name: this.checklist_name_copied,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.checklist_updated = true;
                    document.getElementById('edit_btn').disabled = false;
                    window.location.reload();
                })
                .catch(error => {
                    this.checklist_updated = false;
                    document.getElementById('edit_btn').disabled = false;
                })
            },
            deleteChecklist(){
                axios.delete(`/checklist/${this.checklist_copieds_batch}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Checklist successfully deleted');
                    window.location.reload();
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            fetchChecklists (){
                axios.get('/checklists-all')
                .then(response => { 
                    this.checklists = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
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
            }   
        },  
        computed:{
            totalPages() {
                return Math.ceil(Object.values(this.checklists).length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = Object.values(this.checklists).slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
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

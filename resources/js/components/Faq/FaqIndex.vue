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
                    <navbarRight :user-role="userRole" :user-id="userId"></navbarRight>
                </div>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="div-spacing"></div>     
            <div class="header">
                <h1 class="page-header">
                    <img class="lafil-logo" :src="logoLink">
                    <b>5S PORTAL - FAQs</b>
                </h1>
                <breadcrumb></breadcrumb>
            </div>
            <div id="page-inner">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">FAQs List</h3>
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
                <!-- Locations table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th scope="col">ID</th>
                            <th scope="col">Created by</th>
                            <th scope="col">Question</th>
                            <th scope="col">Answer</th>
                            <th scope="col">Created date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(faq, f) in filteredQueues" v-bind:key="f">
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(faq)">Edit</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(faq)">Delete</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="row">{{ faq.id }}</td>
                            <td>{{ faq.user.name }}</td>
                            <td>{{ faq.question }} </td>
                            <td>{{ faq.answer }} </td>
                            <td>{{ faq.created_at }} </td>
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
                    <span>{{ filteredQueues.length }} Filtered Faq(s)</span><br>
                    <span>{{ faqs.length }} Total Faq(s)</span>
                </div>
            </div>
        </div>
        <!-- Add Faq Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add FAQ</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="faq_added">
                            <strong>Success!</strong> FAQ succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="question">Question*</label> 
                                    <input type="text" id="question" class="form-control" v-model="faq.question" placeholder="Question">
                                    <span class="text-danger" v-if="errors.question">{{ errors.question[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="answer">Answer*</label> 
                                    <input type="text" id="answer" class="form-control" v-model="faq.answer" placeholder="Answer">
                                    <span class="text-danger" v-if="errors.answer">{{ errors.answer[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addFaq(faq)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Faq Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Edit FAQ</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="faq_updated">
                            <strong>Success!</strong> FAQ succesfully updated
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="question">Question*</label> 
                                    <input type="text" id="question" class="form-control" v-model="faq_copied.question" placeholder="Question">
                                    <span class="text-danger" v-if="errors.question">{{ errors.question[0] }}</span>
                                </div>
                            </div>
                           <div class="col-md-12">
                                <div class="form-group">
                                    <label for="answer">Answer*</label> 
                                    <input type="text" id="answer" class="form-control" v-model="faq_copied.answer" placeholder="Answer">
                                    <span class="text-danger" v-if="errors.answer">{{ errors.answer[0] }}</span>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateFaq(faq_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete FAQ Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete FAQ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this FAQ?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteFaq">Delete</button>
                </div>
                </div>
            </div>
        </div>

</div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
    import Multiselect from 'vue-multiselect';
    import  navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import loader from '../Loader';
    export default {
        props: ['userName','userRole', 'userId'],
        components:{
            Multiselect,
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                faqs : [],
                faq: [],
                faq_copied: [],
                faq_id: '',
                faq_added: false,
                faq_updated: false,
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
            }
        },
        created(){
            this.fetchFAQs();
        },
        methods:{
            showLoader(){
               this.loading = true;
            },
            copyObject(faq){
                this.errors = [];
                this.faq_updated = false;
                this.faq_id = faq.id;
                this.faq_copied = Object.assign({}, faq);
            },
            fetchFAQs(){
                axios.get('faqs-all')
                .then(response => {
                    this.faqs = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            addFaq(faq){
                this.showLoader();
                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                axios.post('/faq', {
                    question: faq.question,
                    answer: faq.answer
                })
                .then(response => {
                    this.faqs.unshift(response.data);
                    this.faq_added = true;
                    this.faq = [];
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.faq_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            updateFaq(faq_copied){
                this.showLoader();
                document.getElementById('edit_btn').disabled = true;
                this.faq_updated = false;
                this.errors = [];
                var index = this.faqs.findIndex(item => item.id == faq_copied.id);
                axios.post(`/faq/${faq_copied.id}`, {
                    question: faq_copied.question,
                    answer: faq_copied.answer,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.faq_updated = true;
                    this.faqs.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
                .catch(error => {
                    this.faq_updated = false;
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteFaq(){
                this.showLoader();
                var index = this.faqs.findIndex(item => item.id == this.faq_id);
                axios.delete(`/faq/${this.faq_id}`)
                .then(response => {
                    this.loading = false;
                    $('#deleteModal').modal('hide');
                    alert('FAQ successfully deleted');
                    this.faqs.splice(index,1);
                })
                .catch(error => {
                    this.loading = false;
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
            filteredFaqs(){
                let self = this;
                return self.faqs.filter(faq => {
                    return faq.question.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.faqs.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredFaqs.slice(index, index + this.itemsPerPage);

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

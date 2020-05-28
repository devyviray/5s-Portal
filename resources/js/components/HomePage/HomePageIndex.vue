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
                         <div class="card">
                            <div id="page-inner">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="mb-0">Image List</h3>
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
                                            <th scope="col">Name</th>
                                            <th scope="col">Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(image, i) in filteredQueues" v-bind:key="i">
                                            <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(image)">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td scope="row">{{ image.id }}</td>
                                            <td>{{ image.file_name }}</td>
                                            <td><img class="rounded_circle" :src="imageLink+image.file_path" style="height: 40px"></td>
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
                                    <span>{{ filteredQueues.length }} Filtered Image(s)</span><br>
                                    <span>{{ images.length }} Total Image(s)</span>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Modal -->
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
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Add Image</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="image_added">
                            <strong>Success!</strong> Image succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Attachment*</label>
                                    <input type="file" id="attachments" class="form-control attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                    <span class="text-danger" v-if="errors.image">{{ errors.image[0] }}</span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addImage(image)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete  Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Image?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteImage">Delete</button>
                </div>
                </div>
            </div>
        </div>

</div>
</template>

<script>
    import navbarRight from '../NavbarRight';
    import breadcrumb from '../Breadcrumb';
    import loader from '../Loader';
    export default {
        props: ['userName', 'userRoleLevel', 'userId'],
        components:{
            navbarRight,
            breadcrumb,
            loader
        },
        data(){
            return {
                images : [],
                image: [],
                image_copied: [],
                attachments: [],
                image_id: '',
                image_added: false,
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: '',
                loading: false,
                formData: new FormData(),
            }
        },
        created(){
            this.fetchImages();
        },
        methods:{
            prepareFields(){
                if(this.attachments.length > 0){
                    for(var i = 0; i < this.attachments.length; i++){
                        let attachment = this.attachments[i];
                        this.formData.append('image[]', attachment);
                    }
                } 
            },
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
            showLoader(){
               this.loading = true;
            },
            copyObject(image){
                this.image_added = false;
                this.image_id = image.id;
                this.image_copied = Object.assign({}, image);
            },
            fetchImages(){
                axios.get('home-page-all')
                .then(response => {
                    this.images = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            addImage(image){
                this.showLoader();
                document.getElementById('add_btn').disabled = true;
                this.errors = [];
                this.image_added = false;
                this.prepareFields();
                axios.post('/home-page', this.formData)
                .then(response => {
                    this.images.unshift(response.data);
                    this.image_added = true;
                    document.getElementById('add_btn').disabled = false;
                    this.attachments = [];
                    this.formData = new FormData;
                    this.loading = false;
                    var removeElements = (elms) => [...elms].forEach(el => el.value = "");
                    removeElements( document.querySelectorAll(".attachments"));
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.image_added = false;
                    document.getElementById('add_btn').disabled = false;
                    this.loading = false;
                })
            },
            deleteImage(){
                this.showLoader();
                var index = this.images.findIndex(item => item.id == this.image_id);
                axios.delete(`/home-page/${this.image_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    alert('Image successfully deleted');
                    this.images.splice(index,1);
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
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
            filteredImages(){
                let self = this;
                return self.images.filter(image => {
                    return image.file_name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(this.images.length / this.itemsPerPage);
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredImages.slice(index, index + this.itemsPerPage);

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
            imageLink(){
                return window.location.origin+'/storage/';
            }
        }
    }
</script>

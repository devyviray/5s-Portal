<template>
    <div>
        <loader v-if="loading"></loader>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fas fa-cogs text-dark"></i> SETTINGS
                    <!-- <i class="fa fa-caret-down"></i> -->
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li> <a href="#changePasswordModal" data-toggle="modal"><i class="fa fa-user fa-fw"></i> Change Password</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="homePageLink"><i class="fa fa-trello fa-fw"></i> Home Page</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="userLink"><i class="fa fa-users fa-fw"></i> Users</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="roleLink"><i class="fa fa-user-md fa-fw"></i> Roles</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="companyLink"><i class="fa fa-building-o fa-fw"></i> Companies</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="locationLink"><i class="fa fa-globe fa-fw"></i> Locations</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="departmentLink"><i class="fa fa-trello fa-fw"></i> Departments</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="operationLink"><i class="fa fa-road"></i> Operation lines</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="companyOperationLink"><i class="fa fa-eject"></i> Company Operation lines</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="categoryLink"><i class="fa fa-files-o"></i> Categories</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="areaLink"><i class="fa fa-sitemap"></i> Areas</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="companyAreaLink"><i class="fa fa-building-o"></i> Company Areas</a> </li>
                    <li v-if="userRoleLevel > 2"> <a :href="checklistLink"><i class="fa fa-list-ol"></i> Checklist</a> </li> 
                    <li v-if="userRoleLevel > 2"> <a :href="faqLink"><i class="fa fa-info-circle"></i> FAQs</a> </li>
                    <li class="divider"></li>
                    <li> <a href="#" @click="logoutForm"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
                </ul>
            </li>
        </ul>

        <!-- Change password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title" id="addCompanyLabel">Change Password</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="password_changed">
                            <strong>Success!</strong> Password succesfully changed
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">New password*</label> 
                                    <input type="password"  class="form-control" v-model="user.new_password">
                                    <span class="text-danger" v-if="errors.new_password">{{ errors.new_password[0] }}</span>
                                </div>
                            </div>  
                        </div>
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Confirm password*</label> 
                                    <input type="password" class="form-control" v-model="user.new_password_confirmation">
                                    <span class="text-danger" v-if="errors.new_password_confirmation">{{ errors.new_password_confirmation[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="changePassword(user)">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import loader from './Loader';
export default {
    props: ['userRoleLevel', 'userId'],
    components:{
        loader
    },
    data(){
        return {
            user: [],
            password_changed: false,
            loading: false,
            errors: []
        }
    },
    methods:{
        logoutForm(){
            this.$parent.showLoader();
            axios.post('/logout')
            .then(response =>{
                if(response.status == 200){
                    window.location.href = window.location.origin+'/login';
                }
            })
            .catch(error => { 
                this.errors = error.response.data.errors;
            })
        },
         changePassword(user){
            this.errors = [];
            axios.post('/change-password', {
                user_id: this.userId,
                new_password: user.new_password,
                new_password_confirmation: user.new_password_confirmation
            })
            .then(response => {
                this.password_changed = true;
                this.resetForm();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        resetForm(){
            this.errors = [];
            this.user = [];
        },
    },
    computed:{
        logoLink(){
            return window.location.origin+'/img/lafil-logo.png';
        },
        homePageLink(){
            return window.location.origin+'/home-page'
        },
        departmentLink(){
            return window.location.origin+'/departments'
        },
        locationLink(){
            return window.location.origin+'/locations'
        },
        companyLink(){
            return window.location.origin+'/companies'
        },
        userLink(){
            return window.location.origin+'/users'
        },
        roleLink(){
            return window.location.origin+'/roles'     
        },
        checklistLink(){
            return window.location.origin+'/checklists' 
        },
        faqLink(){
            return window.location.origin+'/faqs' 
        },
        operationLink(){
            return window.location.origin+'/operation-lines' 
        },
        companyOperationLink(){
            return window.location.origin+'/company-operation-lines'
        },
        categoryLink(){
            return window.location.origin+'/categories'
        },
        areaLink(){
            return window.location.origin+'/areas'
        },
        companyAreaLink(){
              return window.location.origin+'/company-areas'
        }
    }
}
</script>


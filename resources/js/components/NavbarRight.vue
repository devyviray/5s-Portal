<template>
    <div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li> <a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a> </li>
                    <li> <a :href="userLink"><i class="fa fa-users fa-fw"></i> Users</a> </li>
                    <li> <a :href="roleLink"><i class="fa fa-user-md fa-fw"></i> Roles</a> </li>
                    <li> <a :href="companyLink"><i class="fa fa-building-o fa-fw"></i> Companies</a> </li>
                    <li> <a :href="locationLink"><i class="fa fa-globe fa-fw"></i> Locations</a> </li>
                    <li> <a :href="departmentLink"><i class="fa fa-trello fa-fw"></i> Departments</a> </li>
                    <li> <a :href="checklistLink"><i class="fa fa-list-ol"></i> Checklist</a> </li>
                    <li> <a :href="operationLink"><i class="fa fa-road"></i> Operation Line</a> </li>
                    <li> <a :href="categoryLink"><i class="fa fa-files-o"></i> Categories</a> </li>
                    <li> <a :href="areaLink"><i class="fa fa-sitemap"></i> Areas</a> </li>
                    <li> <a :href="companyAreaLink"><i class="fa fa-building-o"></i> Company Areas</a> </li>
                    <li> <a :href="faqLink"><i class="fa fa-info-circle"></i> FAQs</a> </li>
                    <li class="divider"></li>
                    <li> <a href="#" @click="logoutForm"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    methods:{
        logoutForm(){
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
    },
    computed:{
        logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
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


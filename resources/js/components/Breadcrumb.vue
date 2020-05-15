<template>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a :href="publicPath+'/home'"><i class="fas fa-home text-dark"></i> HOME</a></li><span>|</span>
            <li class="breadcrumb-item"><a :href="publicPath+'/create-report'"><i class="fas fa-file-signature"></i>CREATE REPORT</a></li><span>|</span>
            <li class="breadcrumb-item">
                <div class="dropdown" id="dropdown">
                    <a style=" color: #003300;  font-weight: bolder;" class="dropdown-toggle"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        REPORT & RATING
                    </a>
                    <span v-if="notifications !== 0" class="badge text-white" style="background-color: #f80031;">{{ notifications }}</span>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div v-for="(company, c) in companiesWithLocations" :key="c">
                            <div v-for="(location, l) in company.locations" :key="l">
                                <a class="dropdown-item" :href="reportLink+company.id+'/'+location.id">{{ company.name + ' - ' + location.name }}</a>
                            </div>   
                        </div>
                    </div>
                </div>
            </li> 
            <span>|</span>
            <!-- <li class="breadcrumb-item"><a :href="faqPageLink">FAQs</a></li><span>|</span> -->
            <li class="breadcrumb-item"><a :href="contactUsLink">CONTACT US</a></li><span>|</span>
            <li class="breadcrumb-item"><!-- <i class="fas fa-cogs text-dark"></i> -->
                <navbarRight :user-role-level="userRoleLevel" :user-id="userId"></navbarRight>
            </li>
            <div style="width: 38%; text-align: right; font-weight:bolder"><a href="#" @click="logoutForm">LOG-OUT</a></div>
        </ol>
    </div>
</template>

<script>
import navbarRight from './NavbarRight';
export default {
    props: ['userRoleLevel','userId'],
    components: { navbarRight },
    data(){
        return {
            companiesWithLocations: [],
            companyLocations: [],
            notifications: 0
        }
    },
    created(){
        this.fetchCompanies();
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
        fetchCompanies(){
            axios.get('/companies-all')
            .then(response => {
                this.userRoleLevel < 3 ?  this.fetchSpecificCompany() : this.companiesWithLocations = response.data;
                this.fetchNotification();
            })
            .catch(error => { 
                this.errors = error.response.data.errors;
            })
        },
        fetchSpecificCompany(){
            axios.get('/company-user')
            .then(response => {
                 this.companiesWithLocations = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.errors;
            })
        },
        fetchNotification(){
            axios.get('/reports-notification')
            .then(response => { 
                this.notifications = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.errors;
            })
        }
    },
    computed:{
        publicPath(){
            return window.location.origin;
        },
        reportLink(){
            return window.location.origin+'/reports/' 
        },
        faqPageLink(){
            return window.location.origin+'/faqs-page' 
        },
        contactUsLink(){
            return window.location.origin+'/contact-us' 
        },
    }
}
</script>
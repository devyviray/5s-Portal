<template>
    <div>
        <ol class="breadcrumb">
            <li><a :href="homeLink">Home</a></li><span style="color: #FFFF">|</span>
            <li>
                <div class="dropdown" id="dropdown">
                    <a style=" color: #ffff;" class="dropdown-toggle"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Report & Rating
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div v-for="(company, c) in companiesWithLocations" :key="c">
                            <div v-for="(location, l) in company.locations" :key="l">
                                <a class="dropdown-item" :href="reportLink+company.id+'/'+location.id">{{ company.name + ' - ' + location.name }}</a>
                            </div>   
                        </div>
                    </div>
                </div>
            </li> 
            <span  style="color: #FFFF">|</span>
            <li><a :href="faqPageLink">FAQs</a></li><span style="color: #FFFF">|</span>
            <li><a href="#">Contact Us</a></li>
        </ol>
    </div>
</template>

<script>
export default {
    props: ['userRoleLevel'],
    data(){
        return {
            companiesWithLocations: [],
            companyLocations: []
        }
    },
    created(){
        this.fetchCompanies();
    },
    methods:{
        fetchCompanies(){
            axios.get('/companies-all')
            .then(response => {
                this.userRoleLevel < 3 ? this.fetchSpecificCompany() : this.companiesWithLocations = response.data;
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
        }
    },
    computed:{
        homeLink(){
            return window.location.origin+'/home'
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
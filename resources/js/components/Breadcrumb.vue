<template>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">
                <a :href="publicPath+'/home'"><i class="fas fa-home text-dark"></i> HOME</a>
            </li><span>|</span>
            <li class="breadcrumb-item">
                <a :href="publicPath+'/create-report'"><i class="fas fa-file-signature"></i> CREATE REPORT</a>
            </li><span>|</span>
            <li class="breadcrumb-item">
                <a :href="publicPath+'/reports-notification'"><i class="fas fa-bell"></i> NOTIFICATIONS</a>
                <span v-if="notifications.length !== 0" class="badge text-white" style="background-color: #f80031;">{{ notifications.length }}</span>
            </li><span>|</span>
            <li class="breadcrumb-item"><a :href="publicPath+'/reports-summary'"><i class="fas fa-inbox"></i> SUMMARY</a></li><span>|</span>
            <li class="breadcrumb-item">
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
            notifications: 0
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
        fetchNotification(){
            axios.get('/reports-notification-all')
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
        }
    }
}
</script>
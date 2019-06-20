<template>
    <div>
        <ol class="breadcrumb">
            <li><a :href="homeLink">Home</a></li><span style="color: #FFFF">|</span>
            <li><a :href="reportLink">Report & Rating</a></li> <span  style="color: #FFFF">|</span>
            <li><a :href="faqPageLink">FAQs</a></li><span style="color: #FFFF">|</span>
            <li><a href="#">Contact Us</a></li>
        </ol>
    </div>
</template>

<script>
export default {
    computed:{
        homeLink(){
            return window.location.origin+'/home'
        },
        reportLink(){
            return window.location.origin+'/reports' 
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
<template>
    <div>
        <ol class="breadcrumb">
            <li><a :href="homeLink">Home</a></li><span style="color: #FFFF">|</span>
            <li><a :href="reportLink">Report & Rating</a></li> <span  style="color: #FFFF">|</span>
            <li><a href="#">Orientation</a></li><span style="color: #FFFF">|</span>
            <li><a href="#">Exam</a></li><span style="color: #FFFF">|</span>
            <li><a :href="faqPageLink">FAQs</a></li>
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
    }
}
</script>
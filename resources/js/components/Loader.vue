<template>
    <div class="outer">
        <div class="loader-div">
            <climbing-box-loader class="custom-class" color="#A8C897" ></climbing-box-loader>
        </div>
    </div>
</template>

<script>
import { ClimbingBoxLoader } from '@saeris/vue-spinners'
export default {
    components:{
       ClimbingBoxLoader
    }
    
}
</script>

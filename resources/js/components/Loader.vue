<template>
    <div class="outer">
        <div class="loader-div">
            <!-- <moon-loader class="custom-class" color="#A8C897" ></moon-loader> -->
            <moon-loader class="custom-class" ></moon-loader>
        </div>
    </div>
</template>

<script>
import { MoonLoader } from '@saeris/vue-spinners'
export default {
    components:{
       MoonLoader
    }
    
}
</script>

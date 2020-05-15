<template>
    <div>
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="row">
                <div class="col-md-4">
                    <img class="mt-3 ml-3 mb-3" :src="publicPath +'/img/new_design/5sPortal.png'">
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4 text-right">
                    <img class="mt-3 mr-3 mb-3" :src="publicPath +'/img/new_design/lfuggoc.png'">
                </div>
            </div>
        </nav>
    </div>
</template>
<script>
export default {
  computed:{
        publicPath(){
            return window.location.origin;
        }
    }
}
</script>

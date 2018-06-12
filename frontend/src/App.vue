<template>
    <div id="app">       
        <div id="main">           
            <router-view/>                                        
        </div>
    </div>

</template>

<script>
export default {
    name: 'App',
}
</script>

<style>
    @import 'https://www.w3schools.com/w3css/4/w3.css';
    @import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css';
    #enFlag{
        display: inline-block;
        background: url(https://akela.mendelu.cz/~xdvorak/dist/static/img/en.png) no-repeat center center;
        background-size: cover;       
        width: 40px; 
        height: 25px;
        margin-right: 15px;
    }
    #czFlag{
        display: inline-block;
        background: url(https://akela.mendelu.cz/~xdvorak/dist/static/img/cz.png) no-repeat center center;
        background-size: cover;       
        width: 40px; 
        height: 25px;
        margin-right: 15px;
    }
</style>
<template>
    <div id="pre">
        <div class="w3-blue">
        <h1 class="w3-center w3-jumbo" id='mainTitle'>
            <span class="w3-text-amber" style="font-family: 'Arial Black', Gadget, sans-serif">Chit</span>Chat
        </h1></div>
        <div class="w3-panel w3-light-grey" id="myPanel">                                       

        <div class="w3-row">
            <div class="w3-col s6 m1 l1">
                <div class="w3-dropdown-hover">
                    <button class="w3-button w3-xlarge w3-round w3-light-gray w3-hover-amber">
                        <span id='langSelect'>{{labels[2].text}}</span> <i class="fa fa-globe"></i> 
                    </button>
                    <div class="w3-dropdown-content w3-bar-block">
                      <a v-on:click.prevent="switchCz" class="w3-bar-item w3-large w3-button"><img id="czFlag">Čeština</a>
                      <a v-on:click.prevent="switchEn" class="w3-bar-item w3-large w3-button"><img id="enFlag">English</a>
                    </div>
                    
                </div>
            </div>
            <div class="w3-col s0 m10 l10" style="visibility: hidden">s</div>
            <div class="w3-col s6 m1 l1">            
                <button v-on:click="switchR" class="w3-hover-amber w3-right w3-round w3-xlarge w3-button">
                    <i class="fa fa-arrow-right"></i> <span v-if="this.re==='Registrace'">{{labels[1].text}}</span> <span v-else>{{labels[0].text}}</span>
                </button>
            </div>

        </div>
            <div class="w3-col l3" style="visibility:hidden">s</div>
            <div class="w3-col l6" v-if="this.re==='Registrace'">                
                <login>
                </login>    
            </div>    
            <div class="w3-col l6" v-else>
                <register @new="switchR">
                </register>
            </div>


     
        </div>     
    </div>
</template>

<script>
import Login from './Login.vue';
import Register from './Register.vue';
export default {
    components: {
        Login,
        Register
    },               
    name: 'Layout',
    data: function(){        
        return{
            re: 'Registrace',
            lang: 'en',
            labels: [],
        }
    },
    beforeDestroy(){
        localStorage.setItem('lang', this.lang);
    },
    mounted(){
        this.lang = localStorage.getItem('lang');
        this.reload();
    },
    methods: {
        reload(){            
            this.$http.get('api/ui/'+this.lang+'/PreLayout')
                .then((response) =>{
                    this.labels = response.data;
                }); 
        },
        switchR(){             
            if(this.re ==='Registrace'){
                this.re = 'Přihlášení';                  
            }else if(this.re === 'Přihlášení'){
                this.re= 'Registrace'                 
            }               
        },
        switchEn(){ 
                this.lang = 'en';                             
                localStorage.setItem('lang', this.lang);
                this.$children[0].reload();
                this.reload();
        },
        switchCz(){
                this.lang= 'cz';            
                localStorage.setItem('lang', this.lang);
                this.$children[0].reload();
                this.reload();
        }
    }    
}

</script>

<style>
    #pre{
        position:fixed;
        padding:0;
        margin:0;
        
        top:0;
        left:0;

        width: 100%;
        height: 100%;
        overflow: auto;
    }
    #mainTitle{
        font-family: 'Impact', sans-serif;
        margin-top: 0;
        margin-bottom: 0;
    }
    #myPanel{
        margin-left: 15%;
        margin-top: 0%;
        margin-right: 15%;
        padding-top: 1%;
        height: 750px;
        width: 70%;
    }
</style>

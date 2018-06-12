<template>
    <div id="Chat" v-on:click="langClose">       
        
        <div class="w3-sidebar w3-border-left w3-border-blue w3-bar-block w3-light-gray w3-animate-right" style="display:none; right:0" id="mySidebar">
            <div class="w3-row">
                <button id="closeNav" v-on:click="navclose" class="w3-bar-item w3-button w3-center w3-xlarge">
                    {{labels[21].text}} <i class="fa fa-times"></i>
                </button>
            </div>               
            <button v-for="room in myrooms" v-on:click.prevent="redirRoom(room.id_rooms); navclose()" class="w3-button w3-large w3-bar-item w3-hover-amber">
                {{room.title}}<span class="w3-right w3-tag w3-blue w3-round-xlarge">{{room.count}}</span>
            </button>
        </div> 
        
        <div id="in">
            
            <div class="w3-bar w3-brown" id="myNavbar"> 
                
                <span class="w3-left w3-bar-item w3-hover-text-amber" v-on:click.prevent="redirRooms" id='mainTitle'>
                    <span class="w3-text-amber" style="font-family: 'Arial Black', Gadget, sans-serif">Chit</span>Chat
                </span>
                
                <button v-on:click.prevent="redirProfile" class="w3-hover-amber w3-bar-item w3-button w3-xlarge">
                    {{labels[18].text}}
                </button>
                
                <button v-on:click.prevent="redirRooms" class="w3-hover-amber w3-bar-item w3-button w3-xlarge">
                    {{labels[19].text}}
                </button>                   
                
                <button id="openNav" class="w3-button w3-right w3-brown w3-animate-left w3-hover-amber w3-xlarge" v-on:click="navopen">
                    {{labels[20].text}} <i class="fa fa-bars"></i>
                </button>
                
                <div class="w3-dropdown-click w3-brown w3-right">
                    <button v-on:click="langDropdown" class="w3-button w3-xlarge w3-hover-amber">
                        <span id='langSelect'></span> <i class="fa fa-globe"></i> 
                    </button>
                    <div id="langMenu" class="w3-dropdown-content w3-brown w3-bar-block w3-card-4 w3-animate-zoom">
                      <a v-on:click.prevent="switchCz" class="w3-bar-item w3-large w3-button"><img id="czFlag">Čeština</a>
                      <a v-on:click.prevent="switchEn" class="w3-bar-item w3-large w3-button"><img id="enFlag">English</a>
                    </div>                    
                </div> 
                
            </div>
            <div  v-on:click.prevent="navclose">
                <div v-if="this.comp==='Profile'">
                    <Profile v-bind:labels="this.labels"></Profile>
                </div>            
                <div v-else-if="this.comp==='Rooms'">
                    <Rooms v-on:change="redirRoom" v-bind:labels="this.labels"></Rooms> 
                </div>   
                <div v-else-if="this.comp==='Room'">
                    <Room v-bind:rid="this.rid" v-on:leave="redirRooms" v-bind:labels="this.labels"></Room> 
                </div>  
            </div> 
        </div>              
        
    </div>
</template>

<script>
import Rooms from './Rooms.vue';
import Profile from './Profile.vue';    
import Room from './Room.vue';     
export default {    
    components: {
        Rooms,
        Profile,
        Room    
    }, 
    name: 'InLayout',
    data() {       
        return{
            comp: 'Rooms',
            myrooms: [],
            rid: null,
            lang: 'en',
            labels: []
        }
    },
    mounted(){
        this.lang = localStorage.getItem('lang');
        this.reload();
    },
    methods:{
        redirProfile(){
            this.comp = 'Profile';
        },
        redirRooms(){
            this.comp = 'Rooms';
        },
        redirRoom(rid){  
            this.$http.post('api/auth/in-room/'+rid,{})
                .then((response) => {        
            });
            this.rid = rid;             
            this.comp = 'Room';
        },
        navopen() { 
            this.reloadRooms();
            mySidebar.style.width = "20%";
            mySidebar.style.display = "block";
            openNav.style.display = "none";
        },
        navclose() {
            mySidebar.style.display = "none";
            openNav.style.display = "block";
        },
        reloadRooms(){
                this.$http.get('api/auth/my-rooms')
                    .then((response ) =>{
                        this.myrooms = response.data;
                    });  
        },
        reload(){            
            this.$http.get('api/ui/'+this.lang+'/InLayout')
                .then((response) =>{
                    this.labels = response.data;
                }); 
        },
        switchEn(){ 
                this.lang = 'en';                             
                localStorage.setItem('lang', this.lang);             
                this.reload();
        },
        switchCz(){
                this.lang= 'cz';            
                localStorage.setItem('lang', this.lang);                
                this.reload();
        },
        langDropdown(){            
            if (langMenu.className.indexOf("w3-show") === -1) {
                langMenu.className += " w3-show";
                langMenu.className += " w3-show";
            } else { 
                this.langClose();
            }
        },
        langClose(){  
            if (langMenu.className.indexOf("w3-show") > -1){
               langMenu.className = langMenu.className.replace(" w3-show", "");            
            }
        }
    }
}

</script>

<style>
    #mainTitle{
        font-family: 'Impact', sans-serif;
        font-size:32px;
        padding:0;
            
        margin-top: 0;
        margin-bottom: 0;
        margin-left: 10px;
        margin-right: 10px;
        
        cursor:pointer;
    }
    #in{
        position:fixed;
        overflow: auto;
        
        padding:0;
        margin:0;

        top:0;
        left:0;

        width: 100%;
        height: 100%;
    }    
</style>

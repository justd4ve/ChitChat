<template>
  <div>
        <div class="w3-panel w3-blue w3-animate-right" style="margin-top:0">
            <h1 class="w3-center">{{labels[0].text}} <button v-on:click.prevent="refreshRooms" class="w3-button w3-hover-amber w3-round w3-large"><i id="refreshIco" class="fa fa-refresh w3-xlarge"></i></button></h1>

        </div>
        <div class="w3-row w3-animate-left">
            <div class="w3-col m1 l3" style="visibility:hidden">left</div>
            <div class="w3-col m10 l6 w3-center">
                <table class="w3-table-all">
                    <thead class="w3-light-gray">
                        <td class="w3-xlarge w3-center">{{labels[1].text}}</td>
                        <td class="w3-xlarge">{{labels[2].text}}</td>
                        <td class="w3-xlarge">{{labels[3].text}}</td>
                        <td></td>
                    </thead>
                    <tbody>
                        <tr v-for="room in rooms.rooms" class="w3-hover-amber w3-large">
                            <td v-if="room.language==='cz'" class="w3-center" style='width:10%'>
                                <img id="czFlag">
                            </td>
                            <td v-else-if="room.language==='en'" class="w3-center" style='width:10%'>
                                <img id="enFlag">                               
                            </td>
                            <td v-else class="w3-center" style='width:10%'>
                                
                            </td>
                            <td>
                                {{room.title}}
                            </td>
                            <td>
                                <a href="#" v-on:click.prevent="showUser(room.id_users_owner)">
                                    {{room.login}}
                                </a>
                                <div id="userModal" class="w3-modal">
                                   <div class="w3-modal-content w3-border w3-border-indigo">      
                                        <header class="w3-container w3-blue"> 
                                            <span v-on:click.prevent="hideUser" 
                                            class="w3-button w3-hover-amber w3-display-topright"> <i class="fa fa-times"></i></span>
                                            <h2>{{labels[7].text}}</h2>
                                        </header>
                                        <User v-bind:uid="room.id_users_owner" v-bind:labels="labels" v-bind:user="user"></User>
                                   </div>
                                 </div>                                 
                            </td>
                            <td>                
                                <a v-if="rooms.info[room.row-1].lock===false" href='#' v-on:click="enter(room.id_rooms)"><i class="fa fa-arrow-right"></i></a>
                                <a v-else title="lock"><i class="w3-xlarge fa fa-lock"></i></a>
                            </td>                
                        </tr>  
                    </tbody>
                </table>
                <hr>
                <new-room @new="reload" v-bind:labels="this.labels">          
                </new-room>
            </div>
        </div>
  </div>
</template>

<script>
    import NewRoom from './NewRoom.vue';
    import User from './User.vue';
    
    export default {
        components: {
            NewRoom, 
            User
        },
        name: "rooms",
        props: ['labels'],
        data() { 
            return { 
                rooms: [],  
                uid: null,
                user: [],
                timer: null
            };
        },
        methods: {
            reload(){
                this.$http.get('api/auth/rooms')
                    .then((response ) =>{
                        this.rooms = response.data;
                    });  
            },
            enter(rid){   
                this.$http.post('api/auth/in-room/'+rid,{})
                        .then((response) => {                   
                    this.$emit('change', rid);
                }, () => {
                    alert(this.labels[49].text);//locked
                });

            },
            showUser(uid){
                this.$http.get('api/auth/user/'+uid)
                    .then((response ) =>{
                        this.user = response.data;
                    });
                userModal.style.display="block";    
            },
            hideUser(){
                userModal.style.display="none";
            },
            refreshRooms(){
                this.reload();
                refreshIco.className += " w3-spin";
                this.timer = setInterval(() => {
                        refreshIco.className=refreshIco.className.replace(" w3-spin","");                         
                                        clearInterval(this.timer);
                    }, 2000);
            }
        },
        mounted(){
            this.reload();
        }
    }
</script>

<style scoped>

</style>


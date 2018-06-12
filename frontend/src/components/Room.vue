<template>
    <div>
        <div class="w3-row">
            <div class="w3-container w3-blue w3-animate-right">
                <h2 class="w3-center">
                    {{labels[22].text}} {{messages.rooms[0].title}}   
                    <span v-if="lock" class="w3-large w3-hover-text-brown w3-right">
                        {{labels[39].text}}<br><i class="fa fa-lock"></i>
                   </span>
                </h2>
            </div> 
            <div class="w3-col m2 l3" style="scroll:auto">
                <div class="w3-display-container">
                    <div class="w3-row w3-center w3-animate-left" style="max-height: 700px">
                        <div class="w3-col s1 m1 l4" style="visibility:hidden">left</div>
                        <div class="w3-col s10 m10 l4">
                        <span class="w3-tiny">{{labels[28].text}}:</span>
                        <div v-for="usr in messages.users">
                            
                            <div v-if="usr.id_users===owner" title="admin" 
                                 class="w3-card w3-round w3-hover-amber w3-text-brown" 
                                 v-on:click.prevent="showUserL(usr.id_users)" 
                                 style="cursor:pointer">                               
                                    {{usr.login}}                               
                            </div>
                            
                            <div v-else>
                                <span v-on:click.prevent="kickUser(usr.id_users)" v-if="uid===owner" class="w3-left" style="margin-left:5px">
                                    <i class="fa fa-times w3-hover-text-red"></i>
                                </span>
                                <div class="w3-card w3-round w3-hover-amber" 
                                     v-on:click.prevent="showUserL(usr.id_users)" 
                                     style="cursor:pointer">                        
                                        {{usr.login}}                                                             
                                </div>
                            </div>
                            
                            <div id="userModalL" class="w3-modal">
                               <div class="w3-modal-content w3-border w3-border-indigo">
                                    <header class="w3-container w3-blue"> 
                                        <span v-on:click.prevent="hideUserL" 
                                        class="w3-button w3-hover-amber w3-display-topright"><i class="fa fa-times"></i></span>
                                        <h2>{{labels[7].text}}</h2>
                                    </header>
                                    <User v-bind:uid="usr.id_users" v-bind:labels="labels" v-bind:user="user"></User>
                               </div>
                            </div> 
                        </div>
                        </div>
                    </div> 
                    <div class="w3-container">
                        <div class="w3-row w3-center w3-animate-left">
                            <div class="w3-col m0 l4" style="visibility:hidden">left</div>
                            <div class="w3-col m12 l4">
                                <span class="w3-tiny">{{labels[40].text}}</span>
                                <UserFilter v-bind:labels="this.labels" v-bind:rid="this.rid" v-bind:lock="this.lock">                                    
                                </UserFilter>                           
                            </div>
                        </div>                     
                    </div>
                </div>        
            </div>
            <div class="w3-col m8 l6">

                <div class="w3-display-container">
                <form>                               
                    <div class="w3-center">                                    
                        <div class="w3-row">                   
                            <div class="w3-col m3 l2">                
                                <select v-model="userTo" class="w3-round w3-select">
                                    <option value='' selected>{{labels[48].text}}:</option>
                                    <option v-for="usr in messages.users" v-bind:value="usr.id_users">{{usr.login}}</option>
                                </select>  
                            </div>                            
                            <div class="w3-col m8 l9">
                            <input type="text" v-bind:placeholder="labels[24].text+'...'" class="w3-input w3-light-gray" v-model="msg">
                            </div>
                            <div class="w3-col m1 l1">
                                <button type="submit" v-on:click.prevent="sendMessage" class="w3-button w3-amber w3-round w3-hover-gray">
                                {{labels[23].text}}                             
                                </button>  
                            </div>
                        </div>                                                
                    </div>
                </form>
                <div class="w3-container" id="msgPanel" style="overflow-y: auto; height: 700px; max-width:100%; overflow-x:hidden">
                    <div v-for="msg in messages.messages">
                        <div v-if="msgDisplay(msg.id_users_from,msg.id_users_to)" class="w3-row">                          
                          <div class="w3-col s12 m4 l4 w3-text-gray">
                              {{msg.created_formated}}                            
                          </div>
                            
                          <div class="w3-col s12 m2 l2 w3-hover-text-amber" style="cursor:pointer">
                                <span v-if="msg.id_users_to===null" href="#" v-on:click.prevent="showUser(msg.id_users_from)">                                                                 
                                    {{msg.login_from}}:  
                                </span>
                              <span v-else>
                                <span href="#" class="w3-hover-text-amber w3-text-purple" v-on:click.prevent="showUser(msg.id_users_from)">                                                                 
                                    {{msg.login_from}}:   
                                </span>
                                <span href="#" class="w3-hover-text-amber w3-text-purple" v-on:click.prevent="showUser(msg.id_users_to)">                                                                 
                                    {{msg.login_to}}  
                                </span>
                              </span>
                          </div>
                                <div id="userModal" class="w3-modal">
                                   <div class="w3-modal-content w3-border w3-border-indigo">      
                                        <header class="w3-container w3-blue"> 
                                            <span v-on:click.prevent="hideUser" 
                                            class="w3-button w3-hover-amber w3-display-topright"><i class="fa fa-times"></i></span>
                                            <h2>{{labels[7].text}}</h2>
                                        </header>
                                        <User v-bind:uid="msg.id_users_from" v-bind:labels="labels" v-bind:user="user"></User>
                                   </div>
                                </div> 
                          
                          <div class="w3-col s12 m6 l6 w3-border-left w3-border-light-gray" style="padding-left:5px">
                              <span style="word-wrap: break-word">
                                  {{msg.message}}
                              </span>
                          </div>
                        </div>
                    </div>  
                </div>
                    


                </div>
                </div>
            <div class="w3-col m2 l3">
                <div class="w3-display-container">
                    <div class="w3-row w3-center">
                        <div class="w3-col s0 m0 l4" style="visibility:hidden">left</div>
                        <div class="w3-col s12 m12 l4" style="margin-top:2%">                            
                            <button v-on:click.prevent="showRename" class="w3-button w3-tooltip w3-round w3-hover-gray w3-amber w3-animate-right">
                                <i class="w3-xlarge fa fa-pencil"></i><span class="w3-text w3-tag w3-gray" style="padding: 0; padding-left:5px">{{labels[29].text}}</span>
                            </button>                            
                            
                            <div id="renameModal" class="w3-modal">
                                <div class="w3-modal-content w3-border w3-border-indigo">
                              
                                    <header class="w3-container w3-blue">
                                        <span v-on:click.prevent="hideRename" class="w3-button w3-hover-amber w3-display-topright"><i class="fa fa-times"></i></span>
                                        <h3>{{labels[33].text}}</h3>                                        
                                    </header>                                    
                                    <input id="renameIn" v-model="title" class="w3-input" v-bind:placeholder="labels[34].text+'...'"></input>
                                    <button v-on:click.prevent="renameRoom" class="w3-button w3-amber w3-hover-gray">
                                        {{labels[29].text}}
                                    </button>                               
                                </div>
                            </div>
                            
                            <br>
                            <button v-if="this.uid === this.owner" v-on:click.prevent="lockRoom" class="w3-button w3-tooltip w3-round w3-hover-gray w3-amber w3-section w3-animate-right">
                                 <i v-if="!lock" class="w3-xlarge fa fa-lock"></i>
                                 <i v-else class="w3-xlarge fa fa-unlock"></i>
                                <span v-if="lock" class="w3-text w3-tag w3-gray" style="padding: 0; padding-left:5px">
                                    {{labels[42].text}}
                                </span>
                                <span v-else class="w3-text w3-tag w3-gray" style="padding: 0; padding-left:5px">
                                    {{labels[41].text}}
                                </span>
                            </button>
                            <button v-else class="w3-button w3-tooltip w3-round w3-hover-light-gray w3-light-gray w3-section w3-animate-right">
                                 <i v-if="!lock" class="w3-xlarge fa fa-lock"></i>
                                 <i v-else class="w3-xlarge fa fa-unlock"></i>
                                <span class="w3-text w3-tag w3-light-gray" style="padding: 0; padding-left:5px">
                                    {{labels[38].text}}
                                </span>
                            </button>
                                <br>
                            <button v-on:click.prevent="leaveRoom" class="w3-button w3-tooltip w3-round w3-hover-gray w3-amber w3-animate-right">
                                <span class="w3-large">&crarr;&#91</span>
                                <span class="w3-text w3-tag w3-gray" style="padding: 0; padding-left:5px">
                                    {{labels[32].text}}
                                </span>
                            </button>
                        </div>                        
                    </div>
                </div>
            </div>               
        </div>
     </div>
</template>

<script>
    import User from './User.vue';
    import UserFilter from './UserFilter.vue';
export default {
        name: "room",
        components: {
            User,
            UserFilter
        },
        props: ['rid', 'labels'],
        data() { 
            return { 
                msg: '',
                messages: [],
                timer: null,    
                users: [],
                user: [],
                title: '',
                lock: null,
                owner: null,
                uid: null,
                userTo: null
            }; 
        },
        mounted(){
            this.timer = setInterval(() => {
                this.getMessages();
            }, 5000);
            this.getMessages();
        },
        beforeDestroy() {
            clearInterval(this.timer);
        },
        methods: {
            getMessages(){
                this.$http.get('api/auth/messages/'+ this.rid)
                        .then((response) => {
                            this.messages = response.data;
                            this.lock = this.messages.rooms[0].lock;
                            this.owner = this.messages.rooms[0].id_users_owner;
                            this.uid = this.messages.user;
                            this.users = this.messages.users;
                            this.inRoom(this.uid);
                            if(this.inRoom(this.uid)){
                                this.$emit('leave');
                                alert(this.labels[51].text);
                            }                            
                        });
            },          
            sendMessage(){   
                if(this.userTo===''){
                    this.userTo=null;
                }
                if(this.msg !== ''){
                    this.$http.post('api/auth/messages/' + this.rid,{
                        message: this.msg,
                        userTo: this.userTo
                    }).then( (response) => {
                        this.msg = '';  
                        this.messages = response.data;                    
                    });   
                }else{
                    alert(this.labels[52].text);
                }
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
                this.user = null;
            },
            showUserL(uid){
                this.$http.get('api/auth/user/'+uid)
                    .then((response ) =>{
                        this.user = response.data;
                    });
                userModalL.style.display="block";    
            },
            hideUserL(){
                userModalL.style.display="none";
                this.user = null;
            },
            leaveRoom(){
                if(confirm(this.labels[37].text)){                   
                    this.$http.post('api/auth/leave-room/' + this.rid)
                        .then( () => {
                            this.$emit('leave');
                        }); 
                }
            },
            renameRoom(){
                if (this.title !== ''){
                    if(this.title !== this.messages.rooms[0].title){
                        this.$http.post('api/auth/rename-room/' + this.rid + '/' + this.title)
                            .then(() => {
                                this.messages.rooms[0].title=this.title; 
                                alert(this.labels[17].text);
                            });
                    }else{
                        alert(this.labels[35].text);
                    }
                }else{
                    alert(this.labels[36].text);                    
                }
            },
            lockRoom(){
                this.$http.post('api/auth/lock-room/' + this.rid)
                    .then( () => {
                        if(this.lock===true){
                            this.lock = false;
                            alert(this.labels[43].text);
                        }else{
                            this.lock = true; 
                            alert(this.labels[39].text);
                        }
                    }, () => {
                    alert(this.labels[44].text);
                });  
            },
            showRename(){ 
                renameModal.style.display="block";
            },
            hideRename(){           
                renameModal.style.display="none";                                      
            },
            msgDisplay(from,to){
                return(
                    to === this.uid || 
                    to === null || 
                    from === this.uid
                )
            },
            kickUser(uid){
                this.$http.post('api/auth/kick-user/' + this.rid +'/'+uid)
                    .then( (response) => {
                        this.messages = response.data;
                        return true;
                    }, () => {
                        return false;
                });                
            },
            inRoom(uid){
                //filtrace aktualnich uzivatelu
                var valObj = this.users.filter(function(elem){
                    if(elem.id_users === uid) return elem.id_users;
                });
                return !(valObj.length > 0);            
            }
        }
}
</script>

<style scoped>
                
</style>
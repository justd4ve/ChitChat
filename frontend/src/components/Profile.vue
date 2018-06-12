<template>
    <div>
        <div class="w3-panel w3-animate-right w3-blue" style="margin-top:0">
        <h1 class="w3-center">{{labels[7].text}} {{user.login}}</h1> 
        </div>
        <div class="w3-panel w3-animate-zoom" v-if="this.edited===true">
        <h3 class="w3-center w3-text-brown">{{labels[17].text}}</h3> 
        </div>
        <div class="w3-row w3-animate-left">
            <div class="w3-col m2 l3" style="visibility:hidden">left</div>
            <div class="w3-col m8 l6 w3-center">
            <table class="w3-table w3-border">
                <thead>                   
                </thead>
                <tbody>
                    <tr>
                        <td class="w3-right w3-text-gray">{{labels[8].text}}</td><td class="w3-text-gray">{{user.id_users}}</td>
                    </tr>
                    <tr>
                        <td class="w3-right w3-text-gray">E-mail</td><td class="w3-text-gray">{{user.email}}</td>
                    </tr>
                    <tr>
                        <td class="w3-right w3-text-gray">{{labels[9].text}}</td><td class="w3-text-gray">{{user.regist}}</td>
                    </tr>
                    <tr>
                        <td class="w3-right">{{labels[10].text}}</td><td><input type="text" class="w3-round" v-model="user.name" required></td>
                    </tr>
                    <tr>
                        <td class="w3-right">{{labels[11].text}}</td><td><input type="text" class="w3-round" v-model="user.surname" required></td>
                    </tr>
                    <tr>
                        <td class="w3-right">{{labels[12].text}}</td>
                        <td>
                            <select class="w3-round" v-model="user.gender">
                                <option value="male">{{labels[13].text}}</option>
                                <option value="female">{{labels[14].text}}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button class="w3-button w3-hover-gray w3-round w3-amber" v-on:click.prevent="saveChanges">
                                {{labels[15].text}}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="w3-col m2 l3 w3-center">
                <button v-on:click.prevent="logout" class="w3-amber w3-round w3-button w3-hover-gray w3-large">
                    {{labels[16].text}} &crarr;&#91
                </button>
            </div>
        </div>                   
     </div>
</template>

<script>
export default {
    name: 'Profile',
    props: ['labels'],
    data(){
        return{          
            user:[],
            edited: false,
            timer: null
        }
    },
    mounted(){
        this.edited = false;
        this.$http.get('api/auth/profile')
                    .then((response ) =>{
                        this.user = response.data;
                    });  
    },
    methods:{
        saveChanges(){
             this.$http.post('api/auth/edit-profile', {
                    name: this.user.name,
                    surname: this.user.surname,
                    gender: this.user.gender                  
                }).then((response)=> {                      
                        this.edited = true; 
                        this.timer = setInterval(() => {
                             this.edited=false;
                        }, 7000);
                    }, () => {
                        alert(this.labels[50].text);
                    });
        },
        logout(){
            localStorage.removeItem('token');            
            this.$http.get('logout')
                    .then((response) =>{                        
                        this.$router.push({name: 'preLayout'});
                        location.reload();
                    }); 

        }
    }
}
</script>

<style scoped>
    
</style>


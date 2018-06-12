<template>
    <div>
        <div class="w3-panel w3-blue w3-round" style="margin-top:0">
            <h1 class="w3-center">{{labels[4].text}}</h1>
        </div>
        
        <div class="w3-row">
            <div class="w3-col l3" style="visibility:hidden">left</div>
            <div class="w3-col l6 w3-center">
            <form @submit.prevent="register" class="w3-container">
                <label>Login</label>
                <input class="w3-input" type="text" v-model="login"></input><br>
                <label>{{labels[0].text}}</label>
                <input class="w3-input" type="password" v-model="password"></input><br>   
                <label>Email</label>
                <input class="w3-input" type="email" v-model="email"></input><br>
                <label>{{labels[2].text}}</label>
                <input class="w3-input" type="text" v-model="name"></input><br>
                <label>{{labels[3].text}}</label>
                <input class="w3-input" type="text" v-model="surname"></input><br>
                <label>{{labels[1].text}}</label>
                <select class="w3-input w3-center" v-model="gender">
                    <option value="male">{{labels[5].text}}</option>
                    <option value="female">{{labels[6].text}}</option>
                    
                </select><br>                 
                <div class="w3-display-container">
                    <button class="w3-button w3-hover-gray w3-amber w3-round w3-display-topmiddle" type="submit">{{labels[7].text}}</button>
                </div>
            </form>
            </div>
       </div>
    </div>
</template>

<script>
    export default {
        name: "Register",
        data(){
            return{
               name: '',
               surname: '',
               gender: 'male',
               email: '',
               password: '',
               login: '',
               lang: localStorage.getItem('lang'),
               labels: []               
            };
        },
        mounted(){
            this.reload();
        },       
        methods: {
            reload(){                
                this.$http.get('api/ui/'+localStorage.getItem('lang')+'/Register')
                    .then((response) =>{
                        this.labels = response.data;
                    }); 
            },           
            register(){
                this.$http.post('api/register', {
                    login: this.login,
                    password: this.password,
                    email: this.email,
                    name: this.name,
                    surname: this.surname,
                    gender: this.gender                  
                }).then((response)=> {
                      this.login = '';
                      this.password = '';
                      this.email = '';
                      this.name = '';
                      this.surname = '';
                      this.gender = 'male';
                      this.$emit('new');                    
                      this.$router.push({name: 'preLayout'});
                    }, () => {
                        alert(this.labels[8].text);
                    } );//
            } 
        }
    }
</script>
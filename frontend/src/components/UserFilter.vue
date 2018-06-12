<template>
    <div>  
        <div class='w3-display-container'>
            <input id="searchUser" v-if="this.lock===false"class="w3-input w3-center w3-light-gray w3-round" v-bind:placeholder="labels[45].text+'...'" type="text" v-model="search">
            <input v-else class="w3-input w3-center w3-light-gray w3-round" disabled v-bind:placeholder="labels[39].text" type="text">
            <table class='w3-table w3-bordered'>
                <thead>
                    
                </thead>
                <tbody>
                    <tr v-for="item in filteredItems">                
                        <td class="w3-center w3-tooltip w3-hover-text-indigo">
                            
                            <a v-if="added(item.id_users)" href="#" class="w3-text-green" v-on:click.prevent="addUser(item.id_users)" style="cursor:default">
                                ({{labels[47].text}})<br>{{item.login}}
                            </a>
                            
                            <a v-else href="#" v-on:click.prevent="addUser(item.id_users)">{{item.login}}
                                <span class="w3-text w3-small" v-bind:title="labels[46].text">
                                    ({{ item.name }} {{ item.surname }})
                                </span>
                            </a>
                            
                        </td>
                    </tr>                    
                </tbody>

            </table>      
        </div>
    </div>
</template>

<script> 
export default {
    name:"userfilter",  
    props: ['labels','rid','lock'],
    data() {
        return{            
            search: '',
            items: [],   
            addedUser: [],
            timer: null,            
        }
    },
    computed: {
        filteredItems() {
            if(this.search!==''){
                return this.items.filter(item => {
                    return (
                        item.login.toLowerCase().indexOf(this.search.toLowerCase()) > -1 ||
                        item.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1 ||
                        item.surname.toLowerCase().indexOf(this.search.toLowerCase()) > -1
                    )
                })
            }else{
                return null;
            }
        }
    },
    mounted(){
        this.reload();
    },
    methods:{
        reload(){
            this.$http.get('api/auth/invite-list/'+this.rid)
                .then((response) => {
                    this.items = response.data;
            }); 
        },
        addUser(uid){
            this.$http.post('api/auth/add-user/'+this.rid+'/'+uid).
                    then((response)=> {
                        this.$emit('addreload');                         
                        this.addedUser.push(uid);                       
                        this.timer = setInterval(() => {
                            var index = this.addedUser.indexOf(uid);                                                                             
                            this.addedUser.splice(index,1);  
                            this.search='';
                            clearInterval(this.timer);
                        }, 2000);
                    });
        },
        added(uid){
            return(this.addedUser.indexOf(uid) > -1);
        }
    }
}
</script>

<style scoped>

</style>

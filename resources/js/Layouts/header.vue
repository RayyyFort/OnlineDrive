<script>
    export default {
        props: {
            logged: Boolean,
        },
        methods:{
            randomcolor: function (){
                return "#" + Math.floor(Math.random() * 16777215).toString(16)
            },
            logoutMethod: function (){
                $.ajax({
                  type: "post",
                  headers:{
                            'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
                          },
                  url: route('logout')
                });
            }
        },
        computed:{
            styleObject: function () {
                return{
                    'background-color':this.randomcolor(),
                    color:this.randomcolor(),
                    'min-height':'50px'
                }
            }
        },
    }
</script>

<template>
    <header :style="styleObject">
        <meta id="header_csrf" name="csrf-token" content="">
        <div class="d-flex flex-row justify-content-around">
            <p>logo</p>
            <div>
                <div v-if="logged=='1'" class="d-flex flex-row">
                    <a :href="route('Files')" class="btn btn-secondary mr-3 mt-1" type="button">Files</a>
                    <button class="btn btn-secondary dropdown-toggle mt-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" :href="route('profile.edit')">profile</a></li>
                        <li><a @click="logoutMethod()" class="dropdown-item" type="submit">logout</a></li>
                    </ul>
                </div>
                <div v-else class="d-flex flex-row">
                    <a :href="route('login')">Login</a>&nbsp;&nbsp;&nbsp;
                    <a :href="route('register')">Register</a>
                </div>
            </div>
        </div>
    </header>
</template>
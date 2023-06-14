<script>
    export default {
        props: {
            logged: Boolean,
        },
        methods:{
            randomcolor: function (){
                return "#" + Math.floor(Math.random() * 16777215).toString(16)
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
        <div class="d-flex flex-row justify-content-around">
            <p>logo</p>
            <div>
                <div v-if="logged=='1'" class="d-flex flex-row">
                    <a :href="route('Files')" class="btn btn-secondary mr-3 mt-1" type="button">Files</a>
                    <button class="btn btn-secondary dropdown-toggle mt-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="dashboard">dashboard</a></li>
                        <li><a class="dropdown-item" href="profile">profile</a></li>
                        <li><a class="dropdown-item" href="#">logout</a></li>
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
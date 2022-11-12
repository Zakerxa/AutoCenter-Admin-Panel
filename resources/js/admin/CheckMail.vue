<template>

</template>

<script>
export default {
    data () {
        return {
            mail: this.email
        }
    },
    props:['email'],
    methods:{

    },
    mounted(){
        console.log(this.email);
    }
}
</script>

<style></style>

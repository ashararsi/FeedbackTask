<template>
    <div>
        <div class="row" v-if="errors.length>0">
            <div class="alert alert-danger" role="alert">
                <div v-for="error in errors" :key="errors.id" class="error-message">
                    {{ errors.message }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        errors: {
            type: Array,
        },
    },
};
</script>

<style>
.error-message {
    color: red;
}
</style>

<!-- components/Pagination.vue -->
<template>
    <div class="pagination">
        <ul>
            <li v-if="data.current_page > 1">
                <button class="btn btn-info btn-sm" @click="changePage(data.current_page - 1)">Previous</button>
            </li>
            <li v-for="page in data.last_page" :key="page">
                <button class="btn btn-info btn-sm"  :class="{ 'active': data.current_page === page }" @click="changePage(page)" >{{ page }}</button>
            </li>
            <li v-if="data.current_page <data.last_page">
                <button class="btn btn-info btn-sm" @click="changePage(data.current_page + 1)">Next</button>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        data: {
            type: Object,
            required: true,
        },
    },
    methods: {
        changePage(page) {
            // Emit an event to notify the parent component about the page change
            this.$emit('page-changed', page);
        },
    },
};
</script>

<style scoped>
.pagination {
    text-align: center;
}

.pagination ul {
    list-style: none;
    display: inline-flex;

}

.pagination li {
    margin: 5px;

}
.pagination button{
    border-color:black !important;
}
.pagination .active {
    font-weight: bold;
    background-color: #159db7;
    border-color:black;
}

</style>

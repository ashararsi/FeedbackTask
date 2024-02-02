<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12   " style="text-align: right">
                                <a href="/user/feedback/create" class="btn btn-primary btn-sm">Create Feedback</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table w-100  table-bordered text-center">
                            <thead class="table-dark" style="background-color: black;">
                            <td>Id</td>
                            <td>Title</td>
                            <td>User id</td>
                            <td>Category</td>
                            <td>Created At</td>
                            <td>Action</td>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in feedback_data.data" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>{{ item.title }}</td>
                                <td>{{ item.user_id }}</td>
                                <td>{{ item.category }}</td>
                                <td>{{
                                        new Date(item.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric'
                                        })
                                    }}
                                </td>
                                <td>
                                    <a :href="'/user/feedback/' + item.id + '/edit'" class="btn btn-primary"
                                       style="margin-right: 7px;"><span class="fa fa-edit"></span></a>
                                    <button @click="feedbackDelete(item.id)" href="" class="btn btn-primary"><span
                                        class="fa fa-trash"></span></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-md-12  text-center">
                                <pagination :data="feedback_data" @page-changed="fetchData"></pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import Pagination from '@/components/pagination.vue';
export default {
    components: {
        Pagination,
    },
    data() {
        return {
            feedback_data: [],
        }
    },
    props: {
        data: null, // Define a prop named "message" with a String type
    },

    mounted() {
        console.log('Component mounted.')
        this.feedback_data = this.data;
    },
    methods: {
        fetchData(page) {
            try {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const vm = this;
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
                axios
                    .get('/user/feedback/page/getdata/?page=' + page, {})
                    .then((response) => {
                        vm.feedback_data = response.data;
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            } catch (error) {

            }
        }, feedbackDelete(id) {
          const  vm=this;
            const confirmed = window.confirm('Are you sure you want to delete?');
            if (confirmed) {
                try {
                    axios
                        .delete('/user/feedback/' + id, {})
                        .then((response) => {

                         vm.fetchData(1);
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                } catch (error) {

                }
            } else {
                // User canceled the deletion
            }
        },
    }
}
</script>

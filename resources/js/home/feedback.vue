<template>
    <div class="container">
        <div class="row" v-for="(item, index) in feedback_data.data" :key="item.id">
            <div class="col-md-12">
                <div class="row mt-5">
                    <div class="card mb-3" style="border-radius:30px ">

                        <div class="card-body">
                            <div class="row mt-5">
                                <h5>{{ item.title }} </h5>
                                <p class="card-text" v-html="item.description"></p>
                            </div>
                            <div class="row">
                                <div class="col-md-2 offset-10">
                                    <button style="margin-left: 5px; float: right" @click="like(item.id)"
                                            class="btn btn-primary btn-sm">
                                        <i class="fa fa-thumbs-up"     aria-hidden="true"></i>
                                        ({{ item.like.length }})
                                    </button>
                                    <button style=" float: right" @click="dislike(item.id)"
                                            class="btn btn-info btn-sm mr-2 ml-2"><i
                                        class="fa fa-thumbs-down" aria-hidden="true"></i> ({{ item.dislike.length }})
                                    </button>
                                    <a class="btn btn-primary btn-sm" data-bs-toggle="collapse" :href="'#collapse-'+item.id" role="button" aria-expanded="false" :aria-controls="'#collapse-'+item.id">
                                        Comments
                                    </a>
                                </div>
                            </div>
                            <div class="collapse" :id="'collapse-'+item.id">
                                <div class="row mt-5" v-if="item.comment_data.length>0">

                                    <div class="card" style="border-radius:30px ">
                                        <div class="card-body">
                                            <div class="row mt-3 border p-3" style="border-radius:30px "
                                                 v-for="(item1, index) in item.comment_data.slice(0, 5)" :key="item1.id">
                                                <h6 class="card-subtitle mb-2 text-muted">
                                                    {{ item1.user.name }}
                                                </h6>
                                                <p class="card-text" v-html="item1.content"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <form class="mt-2 col-md-12 col-sm-12" @submit.prevent="submit" :data-id="item.id">
                                <div class="row">
                                    <div class="form-group mb-2">
                                        <Mentionable
                                            :keys="['@', '#']"
                                            :items="items"
                                            offset="6"
                                            insert-space
                                            @open="onOpen"
                                        >
                                    <textarea
                                        v-model="content[item.id]" class="form-control"
                                    />
                                            <template #no-result>
                                                <div class="dim">
                                                    No result
                                                </div>
                                            </template>
                                            <template #item-@="{ item }">
                                                <div class="user">
                                                    {{ item.name }}
                                                    <span class="dim">
                                                  ({{ item.email }})
                                                </span>
                                                </div>
                                            </template>
                                            <template #item-#="{ item }">
                                                <div class="issue">
                                                <span class="number">
                                                  #{{ item.value }}
                                                </span>
                                                    <span class="dim">
                                                  {{ item.label }}
                                                </span>
                                                </div>
                                            </template>
                                        </Mentionable>
                                    </div>
                                </div>
                                <div class="row m-2 " style="text-align: right">
                                    <div class="col-md-2 offset-10">
                                        <button type="submit" class="btn btn-primary mb-2">Post Comment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="row">
                <div class="col-md-12  text-center">
                    <pagination :data="feedback_data" @page-changed="fetchData"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>
.user {
    padding: 10px;
}
</style>

<script>
import moment from 'moment';
import 'floating-vue/dist/style.css';
import Pagination from '@/components/pagination.vue';
import {Mentionable} from 'vue-mention';


const issues = [
    {
        value: 123,
        label: 'Error with foo bar',
        searchText: 'foo bar'
    },
    {
        value: 42,
        label: 'Cannot read line',
        searchText: 'foo bar line'
    },
    {
        value: 77,
        label: 'I have a feature suggestion',
        searchText: 'feature'
    }
]
export default {
    components: {
        Pagination,
        Mentionable,


    },
    data() {
        return {
            feedback_data: [],
            content: [],
            comment_data: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                content: '',
            },
            text: '',
            items: [],
            comment: '',
            input: '',
            search: '',
        }
    },
    props: {
        data: null,
        users: null,
    },
    computed: {
        formattedComment() {
            // Convert the comment with emojis to HTML
            return this.comment;
        },
    },
    mounted() {

        this.feedback_data = this.data;
    },
    methods: {
        handleEmojiClick(event) {
            // Handle emoji click event
            const emoji = event.native;
            this.comment += emoji;
        },
        onOpen(key) {
            this.items = key === '@' ? this.users : issues
        },
        fetchData(page) {
            try {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                const vm = this;
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
                axios
                    .get('/user/feedback/page/getdata/?page=' + page, {})
                    .then((response) => {
                        // console.log(response.data)
                        vm.feedback_data = response.data;

                    })
                    .catch((error) => {
                        console.error(error);
                    });
            } catch (error) {

            }
        },
        like(id) {
            const vm = this;
            try {
                axios
                    .get('/user/feedback/like/' + id, {})
                    .then((response) => {
                        vm.fetchData(vm.feedback_data.current_page)
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            } catch (error) {

            }

        }, dislike(id) {
            const vm = this;
            try {
                axios
                    .get('/user/feedback/dislike/' + id, {})
                    .then((response) => {
                        vm.fetchData(vm.feedback_data.current_page)
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            } catch (error) {

            }

        },
        submit(event) {
            const id = event.currentTarget.getAttribute('data-id');
            const formData1 = new FormData();
            const vm = this;
            this.comment_data.content = this.content[id];
            this.content[id] = null;
            try {
                axios
                    .post('/user/comment/feedback/' + id, this.comment_data, {})
                    .then((response) => {
                        vm.fetchData(vm.feedback_data.current_page)
                        vm.comment_data.content = null;
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            } catch (error) {

            }

        },

        selectedEmoji(args) {
            console.log(args); /* return {...}*/
        }
    }
}
</script>

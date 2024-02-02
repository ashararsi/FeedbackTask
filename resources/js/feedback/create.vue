<template>
    <div className="container">
        <div className="row justify-content-center">
            <div className="col-md-12">
                <div className="card">
                    <div className="card-header">
                        <div class="row">
                            <div class="col-md-6   ">
                                <b>Create</b>
                            </div>
                            <div class="col-md-6   " style="text-align: right">
                                <a href="/user/feedback" class="btn btn-primary btn-sm"> Back</a>
                            </div>
                        </div>
                    </div>
                    <div className="card-body">
                        <form @submit.prevent="submit">
                            <input type="hidden" name="_token" :value="formData.csrf">
                            <div className="row mt-2">
                                <div className="col-md-12">
                                    <div className="form-group">
                                        <label for="title" className="form-label">Title</label>
                                        <input type="text" v-model="formData.title" className="form-control" id="title"
                                               placeholder="title">
                                    </div>
                                </div>
                            </div>
                            <div className="row mt-2">
                                <div className="col-md-6">
                                    <div className="form-group">
                                        <label for="Category" className="form-label">Category</label>
                                        <select v-model="formData.category" className="form-control" id="Category">
                                            <option> select Option</option>
                                            <option value="bug report"> Bug Report</option>
                                            <option value="feature request"> Feature Request</option>
                                            <option value="improvement">Improvement</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div className="row mt-2">
                                <div className="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1" className="form-label">Description</label>
<!--                                        <textarea ref="editor" v-model="formData.description" class="form-control  "></textarea>-->
                                        <ckeditor :editor="editor" v-model="formData.description" />
                                    </div>
                                </div>
                            </div>
                            <div className="row mt-2">
                                <div className="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1" className="form-label">Attachments</label>
                                        <input class="form-control" @change="onFileChange" multiple name="attachments"
                                               type="file"/>
                                    </div>
                                </div>
                            </div>
                            <div className="row mt-5">
                                <div className="col-1 offset-11">
                                    <button className="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import CKEditor from '@ckeditor/ckeditor5-vue';
export default {
    data() {
        return {
            editor: ClassicEditor,
            myValue: '',
            formData: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                title: '',
                category: '',
                description: '',
                attachments: '',
            },
        }
    },
    components: {

        ckeditor: CKEditor.component,
    },
    methods: {
        onFileChange(event) {
            this.formData.attachments = event.target.files;
        },
        submit() {
            const formData1 = new FormData();
            try {
                for (const key in this.formData) {
                    if (key === 'attachments') {
                        for (let i = 0; i < this.formData.attachments.length; i++) {
                            formData1.append('attachments[]', this.formData.attachments[i]);
                        }
                    } else {
                        formData1.append(key, this.formData[key]);
                    }
                }
                axios
                    .post('/user/feedback', formData1, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    })
                    .then((response) => {

                        window.location.href = '/user/feedback';
                    })
                    .catch((error) => {
                        console.error(error);
                    });


            } catch (error) {

            }


        }

    },
    mounted() {

        $(this.$refs.editor).summernote({
            height: 300, // Set the height of the editor
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['para', ['ul', 'ol', 'paragraph']],
            ],
        });

    }, beforeDestroy() {
        // Make sure to destroy Summernote instance to prevent memory leaks
        $(this.$refs.editor).summernote('destroy');
    },
}
</script>

<template>
    <div className="container">
        <div className="row justify-content-center">
            <div className="col-md-12">
                <div className="card">
                    <div className="card-header">
                        <div class="row">
                            <div class="col-md-6   ">
                                <b>Profile </b>
                            </div>
                            <div class="col-md-6 " style="text-align: right">
                                <a href="/user/home" class="btn btn-primary btn-sm"> Back</a>
                            </div>
                        </div>
                    </div>
                    <div className="card-body">
                        <div class="row container" v-if="errors.length>0">
                            <div class="col-md-12 alert alert-danger" role="alert">
                                <div v-for="error in errors"  class="error-message">
                                    {{ error.message }}
                                </div>
                            </div>
                        </div><div class="row container" v-if="messages.length>0">
                            <div class="col-md-12 alert alert-info  " role="alert">
                                <div v-for="message in messages"  class="">
                                    {{ message.message }}
                                </div>
                            </div>
                        </div>
                        <form @submit.prevent="submit">
                            <input type="hidden" name="_token" :value="formData.csrf">
                            <div className="row mt-2">
                                <div className="col-md-12">
                                    <div className="form-group">
                                        <label for="title" className="form-label">Name</label>
                                        <input type="text" required v-model="formData.name" className="form-control"
                                               id="title"
                                               placeholder="Name">
                                    </div>
                                </div>
                            </div>
                            <div className="row mt-2">
                                <div className="col-md-12">
                                    <div className="form-group">
                                        <label for="email" className="form-label">Email</label>
                                        <input required type="email" v-model="formData.email" className="form-control"
                                               id="email"
                                               placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div className="row mt-2">
                                <div className="col-md-12">
                                    <div className="form-group">
                                        <label for="password" className="form-label">Password</label>
                                        <input type="password" v-model="formData.password" className="form-control"
                                               id="password"
                                               placeholder="*****">
                                    </div>
                                </div>
                            </div>
                            <div className="row mt-2">
                                <div className="col-md-12">
                                    <div className="form-group">
                                        <label for="confirm_password" className="form-label">Confirm Password</label>
                                        <input type="password" v-model="formData.confirm_password"
                                               className="form-control"
                                               id="password"
                                               placeholder="*****">
                                    </div>
                                </div>
                            </div>
                            <div className="row mt-5">
                                <div className="col-1 offset-11">
                                    <button className="btn btn-primary" type="submit">Update</button>
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


export default {
    props: {
        user: null,
    },
    data() {
        return {
            errors: [],
            messages: [],
            myValue: '',
            formData: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                name: '',
                email: '',
                password: '',
                confirm_password: '',
                attachments: '',
            },
        }
    },
    components: {},
    methods: {
        validatePassword() {
            if (this.formData.password !== this.formData.confirm_password) {
                this.errors.push({message: 'Password and Confirm Password do not match'});
                return false;
            }
            return true;
        },

        submit() {
            this.errors = [];
            this.messages = [];
            if (this.validatePassword()) {
                this.save();
            }
            console.log(this.errors)
        },
        save() {
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
                    .post('/user/profile', formData1, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    })
                    .then((response) => {
                        this.messages.push({message: 'Update Successfully '});
                        // window.location.href = '/feedback';
                    })
                    .catch((error) => {
                        console.error(error);
                    });


            } catch (error) {

            }
        }

    },
    mounted() {
        // user.id;
        this.formData.name = this.user.name;
        this.formData.email = this.user.email;


    },
}
</script>

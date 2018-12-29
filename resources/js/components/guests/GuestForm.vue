<template>
    <form @submit.prevent>
        <div class="modal-card" style="width: 700px">
            <header class="modal-card-head">
                <p class="modal-card-title">Add Room Type</p>
            </header>

            <section class="modal-card-body">
                <b-field label="First Name">
                    <b-input
                        placeholder="First name..."
                        v-model="formData.first_name"
                        required
                        autofocus
                    >
                    </b-input>
                </b-field>

                <b-field label="Last Name">
                    <b-input
                        placeholder="Last name..."
                        v-model="formData.last_name"
                        required
                        autofocus
                    >
                    </b-input>
                </b-field>

                <b-field label="Email" message="(optional)">
                    <b-input
                        placeholder="Email..."
                        v-model="formData.email"
                        autofocus
                    >
                    </b-input>
                </b-field>

                <b-field label="Phone">
                    <b-input
                        placeholder="Phone..."
                        v-model="formData.phone"
                        required
                        autofocus
                    >
                    </b-input>
                </b-field>

            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button class="button is-primary" @click="submit" :disabled="!canSubmit">Submit</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        name: 'GuestForm',

        props: ['guest'],

        data () {
            return {
                formData: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: ''
                }
            }
        },

        computed: {
            canSubmit () {
                return this.formData.first_name !== '' && this.formData.last_name !== '' && this.formData.phone !== ''
            },

            isEditing () {
                return !!this.guest
            },
        },

        methods: {
            addGuest () {
                axios.post('/api/guests', this.formData)
                    .then(({data}) => {
                        this.$emit('new-guest', data.guest)

                        this.$parent.close()

                        this.$toast.open({
                            message: data.message,
                            type: 'is-success'
                        })
                    })
                    .catch(err => {
                        this.$toast.open({
                            message: err.response.data.message,
                            type: 'is-danger'
                        })
                    })
            },

            updateGuest () {
                this.formData._method = 'patch'

                axios.post(`/api/guests/${this.guest.id}`, this.formData)
                    .then(({data}) => {
                        this.$emit('guest-updated', data.guest)

                        this.$parent.close()

                        this.$toast.open({
                            message: data.message,
                            type: 'is-success'
                        })
                    })
                    .catch(err => {
                        this.$toast.open({
                            message: err.response.data.message,
                            type: 'is-danger'
                        })
                    })
            },

            submit () {
                if (this.isEditing) {
                    return this.updateGuest()
                }

                this.addGuest ()
            }
        },

        mounted () {
            if (this.isEditing) {
                this.formData.first_name = this.guest.first_name
                this.formData.last_name = this.guest.last_name
                this.formData.email = this.guest.email
                this.formData.phone = this.guest.phone
            }
        }
    }
</script>

<template>
    <form @submit.prevent>
        <div class="modal-card" style="width: 700px">
            <header class="modal-card-head">
                <p class="modal-card-title">Add Room Type</p>
            </header>

            <section class="modal-card-body">
                <b-field label="Name">
                    <b-input
                        placeholder="Room type name..."
                        v-model="formData.name"
                        required
                        autofocus
                    >
                    </b-input>
                </b-field>

                <b-field label="Price">
                    <b-input
                        placeholder="Room type price..."
                        v-model="formData.price"
                        required
                        icon="currency-usd"
                    >
                    </b-input>
                </b-field>

                <b-field label="Capacity">
                    <b-input
                        placeholder="Room type capacity..."
                        type="number"
                        v-model="formData.capacity"
                        min="1"
                        required
                    >
                    </b-input>
                </b-field>

                <b-field label="Description">
                    <b-input
                        v-model="formData.description"
                        maxlength="200"
                        type="textarea"
                        required
                    ></b-input>
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
        name: 'RoomTypeForm',

        data () {
            return {
                formData: {
                    name: '',
                    description: '',
                    price: 0,
                    capacity: 1
                }
            }
        },

        computed: {
            canSubmit () {
                return this.formData.name !== '' && this.formData.description !== '' && this.formData.price > 0
            }
        },

        methods: {
            submit () {
                axios.post('/api/room-types', this.formData)
                    .then(({data}) => {
                        this.$emit('new-room-type', data.type)

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
            }
        },
    }
</script>

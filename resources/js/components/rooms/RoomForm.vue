<template>
    <form @submit.prevent>
        <div class="modal-card" style="width: 960px">
            <header class="modal-card-head">
                <p class="modal-card-title" v-if="isEditing">Edit Room</p>
                <p class="modal-card-title" v-else>New Room</p>
            </header>

            <section class="modal-card-body">
                <div class="columns">
                    <div class="column is-8">
                        <b-field label="Name">
                            <b-input
                                type="text"
                                v-model="name"
                                placeholder="Room name"
                                :disabled="!inputsEnabled"
                                required
                                autofocus
                            >
                            </b-input>
                        </b-field>

                        <b-field label="Type">
                            <b-field grouped>
                                <b-select
                                    v-model="type"
                                    placeholder="Select room type"
                                    :loading.sync="loadingTypes"
                                    :disabled="!hasTypes"
                                    required
                                    expanded
                                >
                                    <template v-for="type in types">
                                        <option :value="type" :key="type.id">{{ type.name }}</option>
                                    </template>
                                </b-select>
                                <p class="control">
                                    <button
                                        class="button is-info is-outlined border-circle"
                                        @click="openRoomTypeFormModal"
                                    >
                                        <b-icon icon="plus" size="is-small"></b-icon>
                                    </button>
                                </p>
                            </b-field>
                        </b-field>

                        <b-field label="Comment" message="(optional)">
                            <b-input
                                v-model="comment"
                                maxlength="200"
                                type="textarea"
                                :disabled="!inputsEnabled"
                            ></b-input>
                        </b-field>

                        <div class="field">
                            <b-switch
                                v-model="available"
                                :disabled="!inputsEnabled"
                            >
                                Available
                            </b-switch>
                        </div>
                    </div>
                    <div class="column is-4" v-if="type">
                        <ul>
                            <li><b>Room type:</b> {{ type.name }}</li>
                            <li><b>Price per night:</b> ${{ type.price }}</li>
                            <li><b>Capacity:</b> {{ type.capacity }}</li>
                            <li><b>Description:</b></li>
                            <li>{{ type.description }}</li>
                        </ul>
                    </div>
                </div>
            </section>

            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button class="button is-primary" @click="submit" :disabled="!inputsEnabled">Submit</button>
            </footer>
        </div>
    </form>
</template>

<script>
    import RoomTypeForm from './../roomTypes/RoomTypeForm'

    export default {
        name: 'RoomForm',

        components: {RoomTypeForm},

        props: ['type_id', 'room', 'types'],

        data () {
            return {
                loadingTypes: false,
                type: this.types.find(t => t.id === this.type_id),
                name: '',
                comment: '',
                available: true
            }
        },

        computed: {
            formData () {
                return {
                    type_id: this.type.id,
                    name: this.name,
                    comment: this.comment,
                    available: this.available,
                    _method: this.isEditing ? 'patch' : 'post'
                }
            },

            hasTypes () {
                return this.types.length > 0
            },

            isEditing () {
                return !!this.room
            },

            inputsEnabled () {
                return !this.loadingTypes && this.hasTypes
            }
        },

        methods: {
            openRoomTypeFormModal () {
                this.$modal.open({
                    parent: this,
                    component: RoomTypeForm,
                    hasModalCard: true,
                    width: 700,
                    events: {
                        'new-room-type': type => {
                            this.types.push(type)

                            this.type = type
                        }
                    },
                })
            },

            addRoom () {
                axios.post('/api/rooms', this.formData)
                    .then(({data}) => {
                        this.$emit('new-room', data.room)

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

            updateRoom () {
                axios.post(`/api/rooms/${this.room.id}`, this.formData)
                    .then(({data}) => {
                        this.$emit('updated-room', data.room)

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
                    return this.updateRoom()
                }

                this.addRoom ()
            },
        },

        mounted () {
            if (this.isEditing) {
                this.name = this.room.name
                this.comment = this.room.comment
                this.available = this.room.available
            }
        }
    }
</script>

<style scoped>
    ul li:not(:last-child) {
        line-height: 2rem;
    }
</style>

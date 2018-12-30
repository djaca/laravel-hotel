<template>
    <form @submit.prevent>
        <div class="modal-card" style="width: 800px;height: 700px">
            <header class="modal-card-head">
                <p class="modal-card-title">Add Reservation</p>
            </header>

            <section class="modal-card-body">
                <b-field label="Guest">
                    <b-autocomplete
                        v-model="name"
                        :data="guests"
                        placeholder="Guest name"
                        field="name"
                        :loading="loadingGuests"
                        @keyup.native="getAsyncGuests"
                        @select="option => selectedGuest = option"
                    >
                    </b-autocomplete>
                </b-field>

                <b-field grouped>
                    <b-field label="Check In" expanded>
                        <b-datepicker
                            v-model="checkIn"
                            placeholder="Click to select date..."
                            icon="calendar-today">
                        </b-datepicker>
                    </b-field>

                    <b-field label="Check Out" expanded>
                        <b-datepicker
                            v-model="checkOut"
                            placeholder="Click to select date..."
                            icon="calendar-today">
                        </b-datepicker>
                    </b-field>
                </b-field>

                <button class="button is-primary" @click="checkRooms">Check available rooms</button>

                <room-types-tabs :rooms="rooms" v-if="rooms.length > 0">
                    <template slot-scope="rooms">
                        <div class="columns is-multiline">
                            <div class="column is-2" v-for="room in rooms.rooms" :key="room.id">
                                <b-field>
                                    <b-checkbox-button v-model="selectedRooms"
                                                       :native-value="room"
                                                       type="is-info"
                                                       size="is-medium"
                                    >
                                        {{ room.name }}
                                    </b-checkbox-button>
                                </b-field>
                            </div>
                        </div>
                    </template>
                </room-types-tabs>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button class="button is-primary" @click="submit" :disabled="!canSubmit">Submit</button>
            </footer>
        </div>
    </form>
</template>

<script>
    import RoomTypesTabs from './../RoomTypesTabs'
    import debounce from 'lodash/debounce'

    export default {
        name: 'ReservationForm',

        components: {RoomTypesTabs},

        props: ['reservation'],

        data () {
            return {
                loadingGuests: false,
                guests: [],
                selectedGuest: null,
                name: '',
                checkIn: null,
                checkOut: null,
                activeTab: 0,
                selectedRooms: [],
                rooms: [],
            }
        },

        computed: {
            formData () {
                return {
                    guest_id: this.selectedGuest ? this.selectedGuest.id : '',
                    start_date: this.checkIn ? this.$moment(this.checkIn).format("YYYY-MM-DD") : null,
                    end_date: this.checkIn ? this.$moment(this.checkOut).format("YYYY-MM-DD") : null,
                    rooms: this.selectedRooms.map(r => r.id)
                }
            },
            canSubmit () {
                return this.formData.guest_id !== '' && this.formData.start_date && this.formData.end_date && this.formData.rooms.length > 0
            },

            isEditing () {
                return !!this.reservation
            },
        },

        methods: {
            getAsyncGuests: debounce(function () {
                if (!this.name.length) {
                    this.guests = []

                    return
                }

                this.loadingGuests = true

                axios.get(`/api/guests?search=${this.name}`)
                    .then(({data}) => {
                        this.guests = data
                    })
                    .catch(({message}) => {
                        this.guests = []

                        this.$toast.open({
                            message: message,
                            type: 'is-danger'
                        })
                    })
                    .finally(() => {
                        this.loadingGuests = false
                    })
            }, 500),

            checkRooms () {
                axios.get('/api/rooms', {params: {start_date: this.formData.start_date, end_date: this.formData.end_date}})
                    .then(({data}) => {
                        this.rooms = data
                    })
                    .catch(err => console.log(err))
            },

            addReservation () {
                axios.post('/api/reservations', this.formData)
                    .then(({data}) => {
                        this.$emit('new-reservation', data.reservation)

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

            updateReservation () {
                this.formData._method = 'patch'

                axios.post(`/api/reservations/${this.reservation.id}`, this.formData)
                    .then(({data}) => {
                        this.$emit('reservation-updated', data.reservation)

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
                    return this.updateReservation()
                }

                this.addReservation()
            }
        },

        mounted () {
            if (this.isEditing) {
                // this.formData.first_name = this.reservation.first_name
                // this.formData.last_name = this.reservation.last_name
                // this.formData.email = this.reservation.email
                // this.formData.phone = this.reservation.phone
            }
        }
    }
</script>

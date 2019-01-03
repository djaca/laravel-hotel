<template>
    <section>
        <div class="columns">
            <div class="column is-6">
                <div class="box">
                    <div class="card-header-title is-centered">Reservation Details</div>

                    <b-field label="Guest">
                        <b-autocomplete
                            v-model="name"
                            :data="guests"
                            field="name"
                            placeholder="Guest name"
                            :loading="loadingGuests"
                            @keyup.native="getAsyncGuests"
                            @select="option => selectedGuest = option"
                            :disabled="isEditing"
                        >
                        </b-autocomplete>
                    </b-field>

                    <b-field grouped>
                        <b-field label="Check In" expanded>
                            <b-datepicker
                                v-model="checkIn"
                                placeholder="Click to select date..."
                                icon="calendar-today"
                                :min-date="this.$moment().subtract(1, 'day').toDate()"
                                @input="clearRooms"
                                :disabled="isEditing"
                            >
                            </b-datepicker>
                        </b-field>

                        <b-field label="Check Out" expanded>
                            <b-datepicker
                                v-model="checkOut"
                                placeholder="Click to select date..."
                                icon="calendar-today"
                                :min-date="this.$moment(checkIn).add(1, 'day').toDate()"
                                @input="clearRooms"
                                :disabled="isEditing"
                            >
                            </b-datepicker>
                        </b-field>
                    </b-field>

                    <button
                        class="button is-primary"
                        :disabled="!hasDates || isEditing"
                        @click="checkRooms"
                    >
                        Check available rooms
                    </button>
                </div>

                <div class="box">
                    <div class="card-header-title is-centered">Rooms</div>

                    <room-types-tabs :rooms="rooms" v-if="rooms.length > 0">
                        <template slot-scope="rooms">
                            <b-field grouped group-multiline>
                                <b-checkbox-button v-for="room in rooms.rooms"
                                                   :key="room.id"
                                                   v-model="selectedRooms"
                                                   :native-value="room.name"
                                                   type="is-info"
                                                   size="is-medium"
                                >
                                    {{ room.name }}
                                </b-checkbox-button>
                            </b-field>

                            <div class="content has-text-grey has-text-centered" v-if="rooms.rooms.length === 0">
                                <p>No available rooms.</p>
                            </div>
                        </template>
                    </room-types-tabs>
                </div>
            </div>

            <div class="column">
                <invoice-box :rooms="parsedSelectedRooms" :quantity="quantity"></invoice-box>
            </div>
        </div>

        <button class="button is-primary" type="button" @click="submit" :disabled="!canSubmit">Submit</button>
    </section>
</template>

<script>
    import RoomTypesTabs from './../components/RoomTypesTabs'
    import InvoiceBox from './../components/InvoiceBox'
    import debounce from 'lodash/debounce'

    export default {
        name: 'CreateReservation',

        components: {RoomTypesTabs, InvoiceBox},

        props: ['id'],

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
            parsedSelectedRooms () {
                return this.selectedRooms.map(r => this.rooms.find(room => room.name === r))
            },

            quantity () {
                return this.$moment(this.checkOut).diff(this.checkIn, 'days')
            },

            formData () {
                return {
                    guest_id: this.selectedGuest ? this.selectedGuest.id : '',
                    start_date: this.checkIn ? this.$moment(this.checkIn).format('YYYY-MM-DD') : null,
                    end_date: this.checkIn ? this.$moment(this.checkOut).format('YYYY-MM-DD') : null,
                    rooms: this.parsedSelectedRooms.map(r => r.id),
                    _method: this.isEditing ? 'patch' : 'post'
                }
            },

            canSubmit () {
                return this.formData.guest_id !== '' && this.formData.start_date && this.formData.end_date && this.formData.rooms.length > 0
            },

            hasDates () {
                return !!this.formData.start_date && !!this.formData.end_date
            },

            isEditing () {
                return !!this.id
            },
        },

        methods: {
            clearRooms () {
                this.rooms = []
                this.selectedRooms = []
            },

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
                axios.get('/api/rooms', {
                    params: {
                        start_date: this.formData.start_date,
                        end_date: this.formData.end_date
                    }
                })
                    .then(({data}) => {
                        data.forEach(room => {
                            this.rooms.push(room)
                        })
                    })
                    .catch(err => console.log(err))
            },

            submit () {
                let uri = '/api/reservations'

                if (this.isEditing) {
                    uri += `/${this.id}`
                }

                axios.post(uri, this.formData)
                    .then(({data}) => {
                        if (!this.isEditing) {
                            this.resetForm()
                        }

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

            populateFields (data) {
                this.checkIn = new Date(data.start_date)
                this.checkOut = new Date(data.end_date)
                this.selectedGuest = data.guest
                this.name = data.guest.name

                // because of method 'clearRooms' triggered on dates input
                this.$nextTick()
                    .then(() => {
                        this.checkRooms()
                        this.selectedRooms = data.rooms.map(r => r.name)
                        data.rooms.forEach(room => {
                            this.rooms.push(room)
                        })
                    })
            },

            resetForm () {
                this.guests = []
                this.selectedGuest = null
                this.name = ''
                this.checkIn = null
                this.checkOut = null
                this.activeTab = 0
                this.selectedRooms = []
                this.rooms = []
            }
        },

        mounted () {
            if (this.isEditing) {
                axios.get(`/api/reservations/${this.$route.params.id}`)
                    .then(({data}) => {
                        this.populateFields(data)
                    })
                    .catch(err => {
                        this.$toast.open({
                            message: err.response.data.message,
                            type: 'is-danger'
                        })
                    })
            }
        }
    }
</script>

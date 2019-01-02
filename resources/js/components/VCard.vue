<template>
    <div class="card is-shady" style="width: 100%">
        <div class="card-content" style="font-size: 0.9rem;">

            <ul>
                <li class="has-text-link">{{ name }}</li>
                <li>{{ phone }}</li>
                <li>{{ numberOfNights }} nights, {{ checkInOrCheckOutText }}</li>
                <li class="has-text-info">{{ reservationId }}</li>
            </ul>

            <b-taglist style="margin-top: 1.5rem">
                <b-tag type="is-info" rounded>{{ room }}</b-tag>
                <b-tooltip v-if="hasMoreRooms" :label="tooltipRooms" type="is-light">
                    <b-tag rounded>+{{ roomsCount }}</b-tag>
                </b-tooltip>
            </b-taglist>

        </div>
    </div>
</template>

<script>
    export default {
        name: 'VCard',

        props: ['reservation'],

        computed: {
            reservationId () {
                return `#${this.reservation.id}`
            },

            startDate () {
                return this.reservation.start_date
            },

            endDate () {
                return this.reservation.end_date
            },

            name () {
                return this.reservation.guest.name
            },

            phone () {
                return this.reservation.guest.phone
            },

            room () {
                return this.reservation.rooms[0].name
            },

            hasMoreRooms () {
                return this.reservation.rooms.length > 1
            },

            roomsCount() {
                if (this.hasMoreRooms) {
                    return this.reservation.rooms.length - 1
                }
            },

            tooltipRooms () {
                if (this.hasMoreRooms) {
                    return this.reservation.rooms.slice(1).map(r => r.name).toString()
                }
            },

            numberOfNights () {
                return this.$moment(this.endDate).diff(this.startDate, 'days')
            },

            checkInOrCheckOutText () {
                if (this.$moment(this.endDate).isSame(this.$moment(), 'day')) {
                    return `from ${this.$moment(this.startDate).format('MMM DD')}`
                }

                return `till ${this.$moment(this.endDate).format('MMM DD')}`
            }
        },

        methods: {
            //
        }
    }
</script>

<style scoped>
    .is-shady {
        border-radius: 4px;
        display: inline-block;
        position: relative;
        transition: all .2s ease-in-out;
    }

    .is-shady:hover {
        box-shadow: 0 10px 16px rgba(0, 0, 0, .13), 0 6px 6px rgba(0, 0, 0, .19);
        cursor: pointer;
    }

    ul > li:not(:last-child) {
        margin-bottom: 3px;
    }
</style>

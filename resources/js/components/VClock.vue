<template>
    <div class="is-flex has-text-grey clock">
        <div style="margin-right: 1.5rem">
            <b-icon icon="calendar" size="is-small"></b-icon>
            <span v-text="date"></span>
        </div>

        <div>
            <b-icon icon="clock-outline" size="is-small"></b-icon>
            <span>{{ time }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VClock',

        data () {
            return {
                date: new Date().toLocaleDateString(),
                hours: '--',
                minutes: '--',
                hourtime: ''
            }
        },

        computed: {
            time () {
                return `${this.hours}:${this.minutes} ${this.hourtime}`
            }
        },

        methods: {
            getHourTime (h) {
                return h >= 12 ? 'pm' : 'am'
            },

            getZeroPad (n) {
                return (parseInt(n, 10) >= 10 ? '' : '0') + n
            },

            updateDateTime () {
                let now = new Date()
                this.hours = now.getHours()
                this.minutes = this.getZeroPad(now.getMinutes())
                this.hourtime = this.getHourTime(this.hours)
                this.hours = this.hours % 12 || 12
            }
        },

        beforeDestroy () {
            clearInterval(this.$options.interval)
        },

        mounted () {
            this.$options.interval = setInterval(this.updateDateTime, 1000)
        }
    }
</script>

<style scoped>
    .clock {
        font-size: 14px;
        font-weight: 700;
    }
</style>

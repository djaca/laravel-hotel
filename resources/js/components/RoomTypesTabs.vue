<template>
    <div>
        <b-tabs v-model="activeTab" position="is-centered">
            <template v-for="tab in tabs">
                <b-tab-item :label="tab.label" :key="tab.id">
                    <slot :rooms="tab.data">

                    </slot>
                </b-tab-item>
            </template>
        </b-tabs>
    </div>
</template>

<script>
    import groupBy from 'lodash/groupBy'

    export default {
        name: 'RoomTypesTabs',

        props: ['rooms'],

        data () {
            return {
                activeTab: 0,
                types: [],
            }
        },

        computed: {
            tabs () {
                let tabs = this.types.map(t => {
                    return {
                        label: t.name,
                        id: t.id,
                        data: []
                    }
                })

                let grouped = groupBy(this.rooms, room => room.type.id)

                tabs.forEach(t => {
                    if (t.id in grouped) {
                        t.data = grouped[t.id]
                    }
                })

                return tabs
            },
        },

        methods: {
            getTypes () {
                axios.get('/api/room-types')
                    .then(({data}) => {
                        this.types = data
                    })
                    .catch(err => {
                        this.$toast.open({
                            message: err.response.data.message,
                            type: 'is-danger'
                        })
                    })
            },
        },

        mounted () {
            this.getTypes()
        }
    }
</script>

<style scoped>

</style>

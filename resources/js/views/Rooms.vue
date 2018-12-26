<template>
    <section>
        <b-loading :active.sync="loading"></b-loading>

        <div class="columns" v-show="!loading">
            <div class="column is-8">
                <div class="box">
                    <div class="card-header-title is-centered">Rooms</div>
                    <b-tabs v-model="activeTab" position="is-centered" v-if="tabs.length > 0">
                        <template v-for="tab in tabs">
                            <b-tab-item :label="tab.label" :key="tab.id">
                                <b-table :data="tab.data" narrowed striped>
                                    <template slot-scope="props">
                                        <b-table-column field="id" label="ID" width="60">
                                            {{ props.row.id }}
                                        </b-table-column>

                                        <b-table-column field="name" label="Name" width="200">
                                            {{ props.row.name }}
                                        </b-table-column>

                                        <b-table-column field="available" label="Status" centered>
                                            <b-tag :type="roomStatusColor(props.row.available)">{{
                                                roomStatusText(props.row.available) }}
                                            </b-tag>
                                        </b-table-column>

                                        <b-table-column centered>
                                            <button class="button is-small is-outlined is-primary"
                                                    @click="openEditRoomModal(props.row)">Edit
                                            </button>
                                            <button class="button is-small is-outlined is-danger"
                                                    @click="confirmDelete(props.row)">Delete
                                            </button>
                                        </b-table-column>
                                    </template>
                                </b-table>
                            </b-tab-item>
                        </template>
                    </b-tabs>

                    <div style="margin-bottom: 3rem;" v-else>Sorry, no rooms...</div>

                    <button
                        class="button is-medium is-info is-outlined"
                        style="border-radius: 50%"
                        @click="openNewRoomModal"
                    >
                        <b-icon icon="plus" size="is-small"></b-icon>
                    </button>
                </div>
            </div>
            <div class="column is-4">
                <div class="box" v-if="stats">
                    <div class="card-header-title is-centered">Stats</div>

                    <table class="stats">
                        <tbody>
                        <tr>
                            <td>Available rooms:</td>
                            <td>
                                <b-tag type="is-success">{{ stats.available || 0 }}</b-tag>
                            </td>
                        </tr>
                        <tr>
                            <td>Occupied rooms:</td>
                            <td>
                                <b-tag type="is-danger">{{ stats.occupied || 0}}</b-tag>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import RoomForm from './../components/rooms/RoomForm'
    import groupBy from 'lodash/groupBy'
    import countBy from 'lodash/countBy'

    export default {
        name: 'Rooms',

        components: {RoomForm},

        data () {
            return {
                loading: false,
                activeTab: 0,
                types: [],
                rooms: []
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

            stats () {
                return countBy(this.rooms, room => room['available'] ? 'available' : 'occupied')
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

            getRooms () {
                this.loading = true

                axios.get(`/api/rooms`)
                    .then(({data}) => {
                        this.rooms = data
                    })
                    .catch(err => {
                        this.$toast.open({
                            message: err.response.data.message,
                            type: 'is-danger'
                        })
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },

            openNewRoomModal () {
                this.$modal.open({
                    parent: this,
                    component: RoomForm,
                    hasModalCard: true,
                    width: 960,
                    events: {
                        'new-room': room => {
                            this.rooms.push(room)
                        }
                    },
                    props: {
                        types: this.types,
                        type_id: this.tabs[this.activeTab] ? this.tabs[this.activeTab].id : null
                    }
                })
            },

            openEditRoomModal (room) {
                this.$modal.open({
                    parent: this,
                    component: RoomForm,
                    hasModalCard: true,
                    width: 960,
                    events: {
                        'updated-room': room => {
                            this.$set(this.rooms, this.rooms.findIndex(r => r.id === room.id), room)
                        }
                    },
                    props: {
                        types: this.types,
                        room,
                        type_id: this.tabs[this.activeTab].id
                    }
                })
            },

            confirmDelete (room) {
                this.$dialog.confirm({
                    title: `Deleting room: ${room.name}`,
                    message: 'Are you sure you want to <b>delete</b> this room? This action cannot be undone.',
                    confirmText: 'Delete Room',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        this.delete(room)
                    }
                })
            },

            delete (room) {
                this.loading = true

                axios.delete(`/api/rooms/${room.id}`)
                    .then(({data}) => {
                        this.rooms.splice(this.rooms.findIndex(r => r.id === room.id), 1)

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
                    .finally(() => {
                        this.loading = false
                    })

            },

            roomStatusColor (available) {
                if (available) {
                    return 'is-success'
                }

                return 'is-danger'
            },

            roomStatusText (available) {
                if (available) {
                    return 'Available'
                }

                return 'Occupied'
            }
        },

        mounted () {
            this.getTypes()
            this.getRooms()
        }
    }
</script>

<style scoped>
    .stats td {
        padding: 0 1rem 0 0
    }
</style>

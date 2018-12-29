<template>
    <section>
        <b-loading :active.sync="loading"></b-loading>

        <div class="columns" v-show="guests.length > 0">
            <div class="column is-8">
                <div class="box">
                    <div class="card-header-title is-centered">Guests</div>

                    <b-table
                        :data="guests"
                        :loading="loadingGuests"
                        selectable
                        striped
                        hoverable
                        paginated
                        backend-pagination
                        :total="total"
                        :per-page="perPage"
                        @page-change="onPageChange"
                    >
                        <template slot-scope="props">
                            <b-table-column field="id" label="ID" width="60">
                                {{ props.row.id }}
                            </b-table-column>

                            <b-table-column field="name" label="Name">
                                {{ props.row.name }}
                            </b-table-column>

                            <b-table-column field="email" label="Email">
                                {{ props.row.email }}
                            </b-table-column>

                            <b-table-column field="phone" label="Phone">
                                {{ props.row.phone }}
                            </b-table-column>
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
  export default {
    name: 'Guests',

      data () {
          return {
              loading: false,
              loadingGuests: false,
              guests: [],
              total: 0,
              page: 1,
              perPage: 0
          }
      },

      methods: {
          onPageChange (page) {
              this.page = page

              this.getGuests()
          },

          getGuests () {
              this.loadingGuests = true

              axios.get(`/api/guests?page=${this.page}`)
                  .then(({data}) => {
                      this.guests = data.data
                      this.perPage = data.per_page
                      this.total = data.total
                  })
                  .catch(err => {
                      this.$toast.open({
                          message: err.response.data.message,
                          type: 'is-danger'
                      })
                  })
                  .finally(() => {
                      this.loadingGuests = false
                  })
          }
      },

      mounted () {
        this.getGuests()
      }
  }
</script>

<style scoped>

</style>

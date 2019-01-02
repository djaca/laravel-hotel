<template>
    <div class="box">
        <div class="card-header-title is-centered">Invoice</div>

        <div v-if="!isEmpty">
            <div style="margin-bottom: 3rem">
                <b-table :data="items" narrowed striped>
                    <template slot-scope="props">

                        <b-table-column field="description" label="Description" width="300">
                            {{ props.row.description }}
                        </b-table-column>

                        <b-table-column field="quantity" label="Quantity" numeric width="40">
                            {{ props.row.quantity }}
                        </b-table-column>

                        <b-table-column field="price" label="Price">
                            ${{ props.row.price }}
                        </b-table-column>

                        <b-table-column field="line_total" label="Line Total">
                            ${{ props.row.line_total }}
                        </b-table-column>
                    </template>
                </b-table>
            </div>

            <table style="width: 100%">
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="has-text-right">Subtotal:</td>
                    <td class="has-text-right">${{ subtotal }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="has-text-right">Tax:</td>
                    <td class="has-text-right">${{ tax }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="has-text-right has-text-weight-semibold">Total:</td>
                    <td class="has-text-right has-text-weight-semibold">${{ total }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'InvoiceBox',

        props: ['rooms', 'quantity'],

        computed: {
            isEmpty () {
                return this.items.length < 1
            },

            subtotal () {
                return +(this.items.reduce((acc, val) => acc + val.line_total, 0)).toFixed(2)
            },

            tax () {
                return +((this.subtotal * 18) / 100).toFixed(2)
            },

            total () {
                return +(this.subtotal + this.tax).toFixed(2)
            },

            items () {
                return this.rooms.map(room => {
                    return {
                        description: room.name,
                        price: room.type.price,
                        quantity: this.quantity,
                        line_total: this.quantity * room.type.price
                    }
                })
            },
        }
    }
</script>

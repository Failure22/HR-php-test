<template>
    <div class="container-fluid" style="width: 80%">
        <div class="panel panel-info">
            <div class="panel-heading">Редактирование заказа {{ model.id }}</div>
            <div class="panel-body">
                <form>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="client-email">Email клиента (*)</label>
                                <input type="email" class="form-control" id="client-email" v-model="model.client_email" required/>
                                <span v-if="errors.includes('email')" class="error-text">Поле обязательно для заполнения</span>
                            </div>
                            <div class="col-md-6">
                                <label for="partner">Партнер (*)</label>
                                <select class="form-control" id="partner" v-model="model.partner_id" required>
                                    <option v-for="partner in partners" v-bind:value="partner.id">{{ partner.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <hr />
                        <div class="col-md-12">
                            <label>Товарный состав</label>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Название товара</th>
                                    <th>Количество</th>
                                    <th>Цена, руб</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="orderProduct in model.products">
                                    <td>{{ orderProduct.product.name }}</td>
                                    <td>{{ orderProduct.quantity }}</td>
                                    <td>{{ orderProduct.price }}</td>
                                </tr>
                                </tbody>
                            </table>
                            <label>Итоговая стоимость заказа - {{ total_price }} руб.</label>
                        </div>
                    </div>
                    <div class="row">
                        <hr />
                        <div class="col-md-6">
                            <label for="status">Статус (*)</label>
                            <select class="form-control" id="status" v-model="model.status" required>
                                <option v-for="(status,code) in statuses" v-bind:value="code">{{ status }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <hr />
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-lg btn-block" v-on:click.prevent.stop="save">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default
    {
        props: ['order'],
        data: function()
        {
            return {
                model: {},
                partners: [],
                statuses: [],
                errors: []
            };
        },
        computed: {
            total_price: function()
            {
                var price = 0;

                if (this.model.products !== undefined)
                {
                    this.model.products.forEach(function(orderProduct, index, products)
                    {
                        price += orderProduct.price * orderProduct.quantity;
                    });
                }

                return price;
            }
        },
        methods:
        {
            loadPartners: function()
            {
                let instance = this;

                axios.get('/api/partners/all')
                        .then(function(response)
                        {
                            instance.partners = response.data;
                        });
            },
            loadStatuses: function()
            {
                let instance = this;

                axios.get('/api/orders/statuses')
                        .then(function(response)
                        {
                            instance.statuses = response.data;
                        });
            },
            save: function()
            {
                if (this.validate())
                {
                    axios.put('/api/orders/save', this.model);
                }
            },
            validate: function()
            {
                this.errors = [];

                if (this.model.client_email.length === 0)
                {
                    this.errors.push('email');
                }

                return this.errors.length === 0;
            }
        },
        mounted: function()
        {
            this.model = this.order;
            this.loadPartners();
            this.loadStatuses();
        }
    }
</script>
<style>
    .error-text {
        color: red;
        font-style: italic;
    }
</style>
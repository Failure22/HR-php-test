<template>
    <div class="container-fluid" style="width: 90%">
        <div class="panel panel-info">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a
                                v-bind:class="{active: tab === 'outdated'}"
                                v-on:click.stop.prevent="tab = 'outdated'"
                                class="nav-link" href="#">Просроченные</a>
                    </li>
                    <li class="nav-item">
                        <a
                                v-bind:class="{active: tab === 'current'}"
                                v-on:click.stop.prevent="tab = 'current'"
                                class="nav-link" href="#">Текущие</a>
                    </li>
                    <li class="nav-item">
                        <a
                                v-bind:class="{active: tab === 'new'}"
                                v-on:click.stop.prevent="tab = 'new'"
                                class="nav-link" href="#">Новые</a>
                    </li>
                    <li class="nav-item">
                        <a
                                v-bind:class="{active: tab === 'completed'}"
                                v-on:click.stop.prevent="tab = 'completed'"
                                class="nav-link" href="#">Завершенные</a>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" style="background-color: white">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Партнер</th>
                        <th scope="col">Стоимость</th>
                        <th scope="col">Состав</th>
                        <th scope="col">Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(order,index) in list" v-bind:key="index">
                        <td><a v-bind:href="'/orders/edit/' + order.id" target="_blank">{{ order.id }}</a></td>
                        <td>{{ order.partner }}</td>
                        <td>{{ order.sum }} руб.</td>
                        <td>
                            {{
                            order.products.map((product, index, products) =>
                            {
                            return product.name + ' - ' + product.quantity + ' шт.';
                            }).join(', ')
                            }}
                        </td>
                        <td>{{ order.status }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="previous">
                        <a href="#" v-on:click.prevent.stop="page-=1"><<</a>
                    </li>
                    <li v-bind:class="{selected: i === page}" v-for="i in (1, page_count)">
                        <a href="#" v-on:click.prevent.stop="page=i">{{ i }}</a>
                    </li>
                    <li class="next">
                        <a href="#" v-on:click.prevent.stop="page+=1">>></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    export default
    {
        props: {
            startingPage: 'Number'
        },

        data: function()
        {
            return {
                page: 1,
                list: [],
                page_count: 1,
                page_size: 0,
                tab: 'current'
            };
        },

        methods: {
            loadList: function()
            {
                let instance = this;

                axios.get('/api/orders/list?page=' + instance.page + '&tab=' + instance.tab)
                        .then(function(response)
                        {
                            instance.list = response.data.list;
                            instance.page_count = response.data.page_count;
                            instance.page_size = response.data.page_size;
                        });
            }
        },

        mounted: function()
        {
            if (this.startingPage !== undefined) { this.page = this.startingPage; }

            this.loadList();
        },

        watch: {
            'page': function(newPage, oldPage)
            {
                if (newPage < 1) { this.page = 1; }

                this.loadList();
            },
            'tab': function(newTab, oldTab)
            {
                if (newTab !== oldTab)
                {
                    this.loadList();
                }
            }
        }
    }
</script>
<style>
    .selected > a {
        color: black;
        cursor: default;
    }

    .active {
        color: #6c757d;
    }
</style>
<template>
    <div class="col-lg-12 position-relative">
        <div class="errors text-danger position-absolute start-0 top-minus">
            <span v-for="(error) in errors">{{ error }}</span>
        </div>
        <form method="post" @submit.prevent="submit">
            <div class="row">
                <div class="col">
                    <div class="row mb-3">
                        <label for="ref" class="col-sm-3 col-form-label pe-0">Ref. <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input id="ref" type="text" name="reference" v-model="form.reference"
                                   placeholder="Write reference..."
                                   class="form-control ">
                            <span class="text-danger">{{ validation.firstError('form.reference') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row mb-3">
                        <label for="ref" class="col-sm-3 col-form-label pe-0">Supplier <span
                            class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select v-model="form.supplier_id" name="supplier_id" class="form-select ">
                                <option value=""> ― Choose ―</option>
                                <option v-for="(supplier, sind) in suppliers" :value="supplier.id" :key="sind">
                                    {{ supplier.name }}
                                </option>
                            </select>
                            <span class="text-danger">{{ validation.firstError('form.supplier_id') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row mb-3">
                        <label for="ref" class="col-sm-3 col-form-label">Date <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <VueDatePicker v-model="form.purchase_date"></VueDatePicker>
                            <span class="text-danger">{{ validation.firstError('form.purchase_date') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="position-relative">
                        <form @submit.prevent="searchProduct">
                            <div class="row">
                                <div class="mb-3 align-items-center row col-lg-4">
                                    <div class="row align-items-center">
                                        <label for="65567a8d59484" class="form-label mb-0 col-md-3 pe-0">
                                            Category </label>
                                        <div class="col-md-9">
                                            <select id="65567a8d59484" name="category_id" class="form-select">
                                                <option value=""> ― Choose ―</option>
                                                <option v-for="(category, cind) in categories" :value="category.id" :key="cind">
                                                    {{ category.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7 position-relative">
                                    <div class="autocomplete-input-wrapper w-100 position-relative">
                                        <input
                                            type="text"
                                            v-model="keyword"
                                            placeholder="Search product by sku or name"
                                            class="form-control shadow-none"
                                            @focus="isListVisible = true"
                                        >
                                    </div>

                                    <div class="product-search-result" v-if="isListVisible">
                                        <div class="d-flex justify-content-between">
                                            <strong class="fw-bold text-muted">#Latest Medicines</strong>
                                            <a href="javascript:" title="Close" @click="isListVisible = false" class="me-3 text-danger"><i
                                                class="bi bi-x-lg"></i></a>
                                        </div>
                                        <ul class="list-unstyled mt-3">
                                            <li v-for="(product,index) in products" :key="index" class="d-flex justify-content-between align-content-center border-bottom pb-1 mb-2">
                                                <div class="d-flex gap-2">
                                                    <img :src="product.image" height="40" width="40" alt="">
                                                    <div class="d-flex flex-column gap-1">
                                                        <strong>{{ product.name }}</strong>
                                                        <small class="text-muted">SKU: {{ product.sku }}</small>
                                                    </div>
                                                </div>
                                                <a href="javascript:" @click="addToCart(product)" title="Add To Cart" class="btn btn-link text-success">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <button type="submit" class="btn btn-dark">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table mb-0 purchase-table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Image</th>
                        <th>Name</th>
                        <th>Purchase Price</th>
                        <th>Sale Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Tax</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody v-if="form.purchase_details.length">
                    <tr v-for="(item, ind) in form.purchase_details" :key="item.product_id">
                        <td class="align-middle text-center">{{ ind + 1 }}</td>
                        <td class="align-middle text-center">
                            <img :src="item.product ? item.product.image : ''" height="30" width="30" loading="lazy"
                                 alt="img">
                        </td>
                        <td class="align-middle">{{ item.product ? item.product.name : '' }}</td>
                        <td class="align-middle">
                            <input type="number" v-model.number="item.purchase_price" class="number" step="any">
                        </td>
                        <td class="align-middle">
                            <input type="number" v-model.number="item.sale_price"
                                   @input="() => calculateItemTotalAmount(item)" class="number" step="any">
                        </td>
                        <td class="align-middle">
                            <input type="number" v-model.number="item.quantity"
                                   @input="() => calculateItemTotalAmount(item)" class="number" step="any">
                        </td>
                        <td class="align-middle">{{ Number(item.subtotal).toFixed(2) }}</td>
                        <td class="align-middle">
                            <div class="d-flex">
                                <input type="number" v-model.number="item.tax"
                                       @input="() => calculateItemTotalAmount(item)" class="number" step="any">
                                <select v-model="item.tax_value_type" @change="() => calculateItemTotalAmount(item)"
                                        class="type-select">
                                    <option value="percent">%</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex">
                                <input type="number" v-model.number="item.discount"
                                       @input="() => calculateItemTotalAmount(item)" class="number" step="any">
                                <select v-model="item.discount_value_type"
                                        @change="() => calculateItemTotalAmount(item)" class="type-select">
                                    <option value="percent">%</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </td>
                        <td class="align-middle">{{ Number(item.total).toFixed(2) }}</td>
                        <td class="align-middle text-center">
                            <button type="button" @click.prevent="() => removeFromCart(ind)"
                                    class="btn btn-link text-danger">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tbody v-else>
                    <tr>
                        <td class="text-center fst-italic" colspan="11">
                            <h5 class="py-5">Cart is Empty.</h5>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mt-3">
                <div class="col-md-7">
                    <div class="row mb-2">
                        <label for="note" class="col-md-3">Note</label>
                        <div class="col-md-9">
                            <textarea id="note" v-model="form.note" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="" class="col-md-3">Status<span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <div>
                                <div class="form-check-sm form-check-inline form-check">
                                    <input type="radio" name="status" v-model="form.status" id="check-0"
                                           class="form-check-input"
                                           value="received">
                                    <label for="check-0" class="form-check-label">Received</label>
                                </div>
                                <div class="form-check-sm form-check-inline form-check">
                                    <input type="radio" name="status" v-model="form.status" id="check-1"
                                           class="form-check-input"
                                           value="pending">
                                    <label for="check-1" class="form-check-label">Pending</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 clearfix">
                    <div class="float-end">
                        <table class="table table-borderless purchase-table">
                            <tbody>
                            <tr>
                                <td class="fw-bold">Subtotal</td>
                                <td>:</td>
                                <td>{{ Number(form.subtotal).toFixed(2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tax</td>
                                <td>:</td>
                                <td>
                                    <div class="d-flex">
                                        <input type="number" v-model="form.tax" @input="() => calculateTotalAmount()"
                                               class="number" step="any">
                                        <select v-model="form.tax_value_type" @change="() => calculateTotalAmount()"
                                                class="type-select">
                                            <option value="percent">%</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Discount</td>
                                <td>:</td>
                                <td>
                                    <div class="d-flex">
                                        <input type="number" v-model="form.discount"
                                               @input="() => calculateTotalAmount()" class="number"
                                               step="any">
                                        <select v-model="form.discount_value_type"
                                                @change="() => calculateTotalAmount()" class="type-select">
                                            <option value="percent">%</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Total</td>
                                <td>:</td>
                                <td>{{ Number(form.total).toFixed(2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary d-block w-100">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import {calculateCharge} from './utils/helpers';
    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'
    import {mixin, Validator} from 'simple-vue3-validator'
    import {toRaw} from "vue";

    export default {
        name: "PurchaseForm",
        components: {VueDatePicker},
        mixins: [mixin],
        data() {
            return {
                form: {
                    reference: '',
                    supplier_id: '',
                    purchase_date: new Date(),
                    subtotal: 0,
                    tax: 0,
                    tax_value_type: 'percent',
                    discount: 0,
                    discount_value_type: 'percent',
                    total: 0,
                    note: '',
                    status: 'received',
                    purchase_details: []
                },
                waiting: false,
                loading: false,
                isEdit: false,
                suppliers: [],
                categories: [],
                products: [],
                productLoading: false,
                keyword: '',
                debounceTimeoutId: null,
                isListVisible: false,
                errors: {
                    reference: null,
                    supplier_id: null,
                    purchase_date: null,
                },
            };
        },

        created() {
            this.getSuppliers();
            this.getCategories();
            this.getLatestProduct();

            // if (this.$route.params.id) {
            //     const id = this.$route.params.id;
            //     this.form["id"] = id;
            //     this.getPurchase(id);
            //     this.isEdit = true;
            // }
        },

        methods: {
            submit() {
                this.$validate().then(async (success) => {
                    if (!success) {
                        return this.$toast.warning(`
                        Please fill out all required fields. <br/>
                        Required fields are indicated by a red asterisk.
                    `);
                    }

                    if (this.form.purchase_details.length == 0) {
                        this.$toast.warning('Cart can not be empty.');
                        return false;
                    }

                    const rawData = toRaw(this.form);
                    const form = structuredClone(rawData);
                    form.purchase_details = form.purchase_details.map(item => {
                        delete item.product;
                        return item;
                    });

                    this.waiting = true;
                    const method = this.isEdit ? "put" : "post";
                    const url = this.isEdit ? "purchase/" + form.id : "purchase";

                    await axios[method](url, form)
                        .then(({data: {message}}) => {
                            this.$toast.success(message);
                            window.location.href = this.$root.baseurl + '/purchase';
                        })
                        .catch(() => ({}));

                    this.waiting = false;
                });
            },

            async getLatestProduct() {
                this.loading = true;
                await axios
                    .get('/products')
                    .then(({data}) => {
                        this.products = data;
                    })
                    .catch(() => ({}));
                this.loading = false;
            },

            async getPurchase(id) {
                this.loading = true;
                await axios
                    .get(`purchase/${id}`)
                    .then(({data}) => {
                        Object.keys(this.form).forEach((k) => {
                            this.form[k] = data[k] ?? "";
                        });
                    })
                    .catch(() => ({}));
                this.loading = false;
            },

            autocomplete(keyword) {
                this.products = [];

                if (keyword) {
                    clearTimeout(this.debounceTimeoutId);
                    this.debounceTimeoutId = setTimeout(async () => {
                        this.productLoading = true;
                        await axios.get('products', {params: {keyword}})
                            .then(({data}) => this.products = data)
                            .catch(() => ({}))
                        this.productLoading = false;
                    }, 300);
                }
            },

            async searchProduct() {
                if (this.keyword) {
                    this.productLoading = true;
                    await axios.get('products', {
                        params: {keyword: this.keyword, first: true}
                    }).then(({data}) => {
                        if (Object.keys(data).length) {
                            this.addToCart(data);
                            this.keyword = '';
                        } else {
                            this.$toast.warning('Product not found.');
                        }
                    }).catch(() => ({}))
                    this.productLoading = false;
                }
            },

            addToCart(product) {
                const found = this.form.purchase_details.find(x => {
                    return x.product_id == product.id;
                });

                if (found) {
                    this.$toast.warning('Already added in cart!');
                    return false;
                }

                this.form.purchase_details.push({
                    product_id: product.id,
                    quantity: 1,
                    purchase_price: product.purchase_price,
                    sale_price: product.sale_price,
                    subtotal: product.sale_price,
                    tax: 0,
                    tax_value_type: 'percent',
                    discount: 0,
                    discount_value_type: 'percent',
                    total: product.sale_price,
                    product: {
                        name: product.name,
                        image: product.image
                    },
                });
                this.isListVisible = false;
                this.calculateTotalAmount();
            },

            removeFromCart(index) {
                this.form.purchase_details.splice(index, 1)
                this.calculateTotalAmount();
            },

            calculateItemTotalAmount(item) {
                let price = isNaN(item.sale_price) ? 0 : Number(item.sale_price);
                let qty = isNaN(item.quantity) ? 0 : Number(item.quantity);
                let tax = isNaN(item.tax) ? 0 : Number(item.tax);
                let discount = isNaN(item.discount) ? 0 : Number(item.discount);
                let total = price * qty;

                item.subtotal = total;

                total += calculateCharge(tax, total, item.tax_value_type);
                total -= calculateCharge(discount, total, item.discount_value_type);

                item.total = total;

                this.calculateTotalAmount();
            },

            calculateTotalAmount() {
                let total = 0;
                let tax = isNaN(this.form.tax) ? 0 : Number(this.form.tax);
                let discount = isNaN(this.form.discount) ? 0 : Number(this.form.discount);

                for (let i = 0; i < this.form.purchase_details.length; i++) {
                    const item = this.form.purchase_details[i];
                    total += isNaN(item.total) ? 0 : Number(item.total);
                }

                this.form.subtotal = total;

                total += calculateCharge(tax, total, this.form.tax_value_type);
                total -= calculateCharge(discount, total, this.form.discount_value_type);

                this.form.total = total;
            },

            getSuppliers() {
                axios.get('suppliers')
                    .then(({data}) => this.suppliers = data)
                    .catch(() => ({}))
            },
            getCategories() {
                axios.get('categories')
                    .then(({data}) => this.categories = data)
                    .catch(() => ({}))
            },
        },

        validators: {
            "form.reference": function (value) {
                return Validator.value(value).required('Reference field is required');
            },
            "form.supplier_id": function (value) {
                return Validator.value(value).required('Supplier field is required');
            },
            "form.purchase_date": function (value) {
                return Validator.value(value).required('Purchase date field is required');
            },
        },
    };
</script>

<style lang="scss" scoped>
    .type-select,
    .number {
        outline: none;
        border: 1px solid #bfbdbd;
    }

    .number {
        width: 80px;
    }

    .product-list {
        position: absolute;
        top: 38px;
        left: 0;
        width: 96.5%;
        max-height: 400px;
        overflow: auto;
    }

    .top-minus {
        top: -50px;
    }
</style>

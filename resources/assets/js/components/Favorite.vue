<template>
    <button type="submit" :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                count: this.data.favoritesCount,
                active: this.data.isFavorited
            }
        },
        methods: {
            toggle() {
                return this.active ? this.unfavorite() : this.favorite();
            },
            unfavorite() {
                axios.delete(this.endpoint);
                this.active = false;
                this.count--;
            },
            favorite() {
                axios.post(this.endpoint);
                this.active = true;
                this.count++;
            }
        },
        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-default'];
            },
            endpoint() {
                return '/replies/' + this.data.id + '/favorites';
            }
        }
    }
</script>

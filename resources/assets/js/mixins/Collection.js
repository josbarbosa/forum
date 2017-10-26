export default {
    data() {
        return {
            items: []
        };
    },
    methods: {
        remove(index) {
            this.items.splice(index, 1);

            this.$emit('removed');

            flash('Reply was deleted!');
        },
        add(item) {
            this.items.push(item);

            this.$emit('added');
        }
    }
}
